<?php

class MercadoPago {
    private $token;
    private $publicKey;
    private $apiUrl = 'https://api.mercadopago.com';

    public function __construct() {
        $this->token = Env::get('MERCADO_PAGO_TOKEN');
        $this->publicKey = Env::get('MERCADO_PAGO_PUBLIC_KEY');

        if (!$this->token || !$this->publicKey) {
            throw new Exception('Mercado Pago não configurado. Verifique .env');
        }
    }

    /**
     * Criar preferência de pagamento
     * @param array $items - Itens do pedido
     * @param float $shippingCost - Custo de frete
     * @param string $orderId - ID do pedido
     * @return array - Dados da preferência
     */
    public function createPreference($items, $shippingCost, $orderId) {
        $preference = [
            'items' => $items,
            'payer' => [
                'email' => $_SESSION['customer_email'] ?? 'customer@example.com'
            ],
            'shipments' => [
                'cost' => $shippingCost
            ],
            'back_urls' => [
                'success' => Env::get('APP_URL') . '/checkout/sucesso',
                'failure' => Env::get('APP_URL') . '/checkout/falha',
                'pending' => Env::get('APP_URL') . '/checkout/pendente'
            ],
            'external_reference' => $orderId,
            'auto_return' => 'approved'
        ];

        return $this->makeRequest('POST', '/checkout/preferences', $preference);
    }

    /**
     * Obter informações de pagamento
     * @param string $paymentId - ID do pagamento
     * @return array - Dados do pagamento
     */
    public function getPayment($paymentId) {
        return $this->makeRequest('GET', '/v1/payments/' . $paymentId);
    }

    /**
     * Fazer requisição à API
     * @param string $method - GET, POST, etc
     * @param string $endpoint - Endpoint da API
     * @param array $data - Dados a enviar
     * @return array - Resposta da API
     */
    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->apiUrl . $endpoint;

        $options = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Authorization: Bearer ' . $this->token,
                    'Content-Type: application/json'
                ],
                'timeout' => 30
            ]
        ];

        if ($data && in_array($method, ['POST', 'PUT'])) {
            $options['http']['content'] = json_encode($data);
        }

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception('Erro ao conectar com Mercado Pago');
        }

        return json_decode($response, true);
    }

    /**
     * Obter chave pública para checkout transparente
     * @return string
     */
    public function getPublicKey() {
        return $this->publicKey;
    }
}
