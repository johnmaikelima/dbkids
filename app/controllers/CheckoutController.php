<?php

namespace App\Controllers;

class CheckoutController {
    
    public function index() {
        // Verificar se tem itens no carrinho
        if (empty($_SESSION['cart'])) {
            redirect('/carrinho');
        }

        // Calcular total
        $total = $this->calculateTotal();

        // Obter opções de parcelamento
        $installments = [];
        try {
            // Verificar se o SDK do Mercado Pago está disponível
            if (class_exists('App\Helpers\MercadoPagoHelper')) {
                $mpHelper = new \App\Helpers\MercadoPagoHelper();
                $installmentsData = $mpHelper->getInstallments($total);
                
                if (!empty($installmentsData)) {
                    $installments = $installmentsData;
                }
            }
        } catch (\Exception $e) {
            error_log("Erro ao obter parcelamento: " . $e->getMessage());
        }

        // Dados para a view
        $data = [
            'cart' => $_SESSION['cart'],
            'total' => $total,
            'mercadoPagoPublicKey' => getSettingValue('MERCADO_PAGO_PUBLIC_KEY'),
            'installments' => $installments
        ];

        view('checkout/index', $data);
    }

    public function process() {
        // Verificar se é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/checkout');
        }

        try {
            // Obter dados do cliente
            $customerData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'city' => $_POST['city'] ?? '',
                'state' => $_POST['state'] ?? '',
                'zip_code' => $_POST['zip_code'] ?? ''
            ];

            // Validar dados
            if (!$this->validateCustomerData($customerData)) {
                $_SESSION['error'] = 'Dados inválidos. Preencha todos os campos.';
                redirect('/checkout');
            }

            // Criar ou obter cliente
            $customer = new \Customer();
            $customerId = $customer->createOrUpdate($customerData);

            // Criar pedido
            $order = new \Order();
            $orderId = $order->create([
                'customer_id' => $customerId,
                'items' => $_SESSION['cart'],
                'shipping_address' => $customerData['address'],
                'shipping_cost' => $_POST['shipping_cost'] ?? 0
            ]);

            // Marcar pedido como pendente de pagamento
            $order->updateStatus($orderId, 'pending');

            // Calcular total do pedido
            $total = $this->calculateTotal() + (float)($_POST['shipping_cost'] ?? 0);
            $installments = $_POST['installments'] ?? 1;

            // Redirecionar para Mercado Pago
            $this->redirectToMercadoPago($orderId, $total, $customerData, $installments);
            return;

        } catch (Exception $e) {
            error_log("CHECKOUT ERROR: " . $e->getMessage());
            error_log("Stack: " . $e->getTraceAsString());
            $_SESSION['error'] = 'Erro ao processar pedido: ' . $e->getMessage();
            redirect('/checkout');
        }
    }

    public function success() {
        // Página de sucesso após finalizar compra
        $orderId = $_GET['order_id'] ?? null;

        if ($orderId) {
            // Limpar carrinho
            unset($_SESSION['cart']);

            $_SESSION['success'] = 'Pedido criado com sucesso! Aguardando pagamento.';
            view('checkout/success', ['orderId' => $orderId]);
        } else {
            redirect('/');
        }
    }

    public function failure() {
        $_SESSION['error'] = 'Pagamento falhou. Tente novamente.';
        redirect('/checkout');
    }

    public function pending() {
        $_SESSION['pending'] = 'Pagamento pendente. Você receberá uma confirmação em breve.';
        redirect('/');
    }

    private function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    private function prepareItemsForMercadoPago($cart) {
        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'id' => $item['id'],
                'title' => $item['name'],
                'description' => $item['name'],
                'picture_url' => $item['image'] ?? '',
                'category_id' => 'products',
                'quantity' => $item['quantity'],
                'unit_price' => floatval($item['price'])
            ];
        }
        return $items;
    }

    private function validateCustomerData($data) {
        return !empty($data['name']) && 
               !empty($data['email']) && 
               isValidEmail($data['email']) &&
               !empty($data['cpf']) &&
               !empty($data['phone']) &&
               !empty($data['address']) &&
               !empty($data['city']) &&
               !empty($data['state']) &&
               !empty($data['zip_code']);
    }

    public function webhook() {
        // Receber notificação do Mercado Pago
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Log para debug
        error_log("Webhook Mercado Pago recebido: " . $input);

        // Validar assinatura do Mercado Pago (segurança)
        $secret = getSettingValue('MERCADO_PAGO_WEBHOOK_SECRET');
        if ($secret) {
            $xSignature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
            $xRequestId = $_SERVER['HTTP_X_REQUEST_ID'] ?? '';
            
            // Extrair ts e hash da assinatura
            $parts = explode(',', $xSignature);
            $ts = null;
            $hash = null;
            
            foreach ($parts as $part) {
                $keyValue = explode('=', $part, 2);
                if (count($keyValue) == 2) {
                    $key = trim($keyValue[0]);
                    $value = trim($keyValue[1]);
                    if ($key === 'ts') {
                        $ts = $value;
                    } elseif ($key === 'v1') {
                        $hash = $value;
                    }
                }
            }
            
            // Validar assinatura
            if ($ts && $hash) {
                $manifest = "id:{$xRequestId};request-id:{$xRequestId};ts:{$ts};";
                $expectedHash = hash_hmac('sha256', $manifest, $secret);
                
                if ($hash !== $expectedHash) {
                    error_log("Assinatura inválida do webhook");
                    http_response_code(401);
                    echo json_encode(['error' => 'Invalid signature']);
                    return;
                }
            }
        }

        // Verificar se é notificação de pagamento
        if (!isset($data['type']) || $data['type'] !== 'payment') {
            http_response_code(200);
            echo json_encode(['status' => 'ignored']);
            return;
        }

        // Obter ID do pagamento
        $paymentId = $data['data']['id'] ?? null;

        if (!$paymentId) {
            http_response_code(400);
            echo json_encode(['error' => 'Payment ID not found']);
            return;
        }

        try {
            // Buscar informações do pagamento no Mercado Pago
            $accessToken = getSettingValue('MERCADO_PAGO_TOKEN');
            
            $ch = curl_init("https://api.mercadopago.com/v1/payments/{$paymentId}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$accessToken}",
                "Content-Type: application/json"
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                error_log("Erro ao buscar pagamento: HTTP {$httpCode}");
                http_response_code(500);
                return;
            }

            $payment = json_decode($response, true);
            
            // Obter ID do pedido (external_reference)
            $orderId = $payment['external_reference'] ?? null;
            
            if (!$orderId) {
                error_log("Order ID não encontrado no pagamento");
                http_response_code(200);
                return;
            }

            // Atualizar status do pedido
            $db = getDB();
            $newStatus = '';
            
            switch ($payment['status']) {
                case 'approved':
                    $newStatus = 'paid';
                    break;
                case 'pending':
                case 'in_process':
                    $newStatus = 'pending';
                    break;
                case 'rejected':
                case 'cancelled':
                    $newStatus = 'cancelled';
                    break;
                default:
                    $newStatus = 'pending';
            }

            // Atualizar pedido no banco
            $stmt = $db->prepare("UPDATE orders SET status = ?, payment_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$newStatus, $paymentId, $orderId]);

            error_log("Pedido #{$orderId} atualizado para status: {$newStatus}");

            // Se aprovado, enviar email de confirmação
            if ($newStatus === 'paid') {
                $this->sendOrderConfirmationEmail($orderId);
            }

            http_response_code(200);
            echo json_encode(['status' => 'success', 'order_id' => $orderId, 'new_status' => $newStatus]);

        } catch (\Exception $e) {
            error_log("Erro no webhook: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function sendOrderConfirmationEmail($orderId) {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$orderId]);
            $order = $stmt->fetch(\PDO::FETCH_OBJ);

            if (!$order) {
                return;
            }

            $mailer = new \Mail();
            $subject = "Pedido #{$orderId} - Pagamento Confirmado!";
            $message = "
                <h2>Pagamento Confirmado!</h2>
                <p>Olá {$order->customer_name},</p>
                <p>Seu pagamento foi confirmado com sucesso!</p>
                <p><strong>Pedido:</strong> #{$orderId}</p>
                <p><strong>Total:</strong> R$ " . number_format($order->total, 2, ',', '.') . "</p>
                <p>Em breve você receberá informações sobre o envio.</p>
                <p>Obrigado por comprar na DbKids!</p>
            ";

            $mailer->send($order->customer_email, $subject, $message);
            error_log("Email de confirmação enviado para: {$order->customer_email}");

        } catch (\Exception $e) {
            error_log("Erro ao enviar email de confirmação: " . $e->getMessage());
        }
    }

    public function generatePaymentLink() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/meus-pedidos');
            return;
        }

        $orderId = $_POST['order_id'] ?? '';
        
        if (empty($orderId)) {
            $_SESSION['error'] = 'Pedido não encontrado';
            redirect('/meus-pedidos');
            return;
        }

        // Buscar pedido
        $orderModel = new \Order();
        $order = $orderModel->find($orderId);

        if (!$order || $order->status !== 'pending') {
            $_SESSION['error'] = 'Pedido não encontrado ou já foi pago';
            redirect('/meus-pedidos/detalhe?id=' . $orderId);
            return;
        }

        // Buscar itens do pedido
        $items = $orderModel->getItems($orderId);

        // Preparar dados do cliente
        $customerData = [
            'name' => $order->customer_name,
            'email' => $order->customer_email,
            'phone' => '',
            'address' => $order->shipping_address,
            'city' => '',
            'state' => '',
            'zip_code' => ''
        ];

        // Criar preferência no Mercado Pago
        $total = $order->total_price + $order->shipping_cost;
        $this->redirectToMercadoPago($orderId, $total, $customerData, 12);
    }

    private function redirectToMercadoPago($orderId, $total, $customerData, $installments = 1) {
        try {
            $accessToken = getSettingValue('MERCADO_PAGO_TOKEN');
            
            error_log("=== MERCADO PAGO DEBUG ===");
            error_log("Token length: " . strlen($accessToken));
            error_log("Token value: " . $accessToken);
            error_log("Token (first 50 chars): " . substr($accessToken, 0, 50));
            error_log("Total: " . $total);
            error_log("Order ID: " . $orderId);
            
            if (!$accessToken) {
                // Se não tiver token, redireciona para sucesso (modo teste)
                error_log("Mercado Pago token não configurado - modo teste");
                redirect('/checkout/sucesso?order_id=' . $orderId);
                return;
            }

            // Preparar dados da preferência
            $preference = [
                'items' => [],
                'payer' => [
                    'name' => $customerData['name'],
                    'email' => $customerData['email'],
                    'phone' => [
                        'area_code' => '11',
                        'number' => $customerData['phone']
                    ],
                    'address' => [
                        'street_name' => $customerData['address'],
                        'city_name' => $customerData['city']
                    ]
                ],
                'external_reference' => (string)$orderId,
                'auto_return' => 'approved'
            ];

            // Adicionar itens do carrinho ou do pedido
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $preference['items'][] = [
                        'id' => $item['id'],
                        'title' => $item['name'],
                        'quantity' => $item['quantity'],
                        'unit_price' => (float)$item['price']
                    ];
                }
            } else {
                // Se não tiver carrinho, buscar itens do pedido
                $orderModel = new \Order();
                $items = $orderModel->getItems($orderId);
                foreach ($items as $item) {
                    $preference['items'][] = [
                        'id' => $item->product_id,
                        'title' => $item->product_name,
                        'quantity' => $item->quantity,
                        'unit_price' => (float)$item->price
                    ];
                }
            }

            // Enviar para API do Mercado Pago
            $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preference));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$accessToken}",
                "Content-Type: application/json"
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            error_log("HTTP Code: " . $httpCode);
            error_log("Response: " . $response);

            if ($httpCode === 201) {
                $preferenceData = json_decode($response, true);
                $initPoint = $preferenceData['init_point'] ?? null;

                error_log("Preference Data: " . json_encode($preferenceData));
                error_log("Init Point: " . $initPoint);

                if ($initPoint) {
                    error_log("Preferência criada: " . $preferenceData['id']);
                    
                    // Salvar link de pagamento no pedido
                    try {
                        $db = getDB();
                        $stmt = $db->prepare("UPDATE orders SET payment_link = ? WHERE id = ?");
                        $stmt->execute([$initPoint, $orderId]);
                        error_log("Link de pagamento salvo no pedido #{$orderId}");
                    } catch (\Exception $e) {
                        error_log("Erro ao salvar link de pagamento: " . $e->getMessage());
                    }
                    
                    // Redirecionar para Mercado Pago
                    header('Location: ' . $initPoint);
                    exit;
                }
            }

            error_log("Erro ao criar preferência Mercado Pago: HTTP {$httpCode} - {$response}");
            // Se falhar, redireciona para sucesso (modo teste)
            redirect('/checkout/sucesso?order_id=' . $orderId);

        } catch (\Exception $e) {
            error_log("Erro ao redirecionar para Mercado Pago: " . $e->getMessage());
            redirect('/checkout/sucesso?order_id=' . $orderId);
        }
    }
}
