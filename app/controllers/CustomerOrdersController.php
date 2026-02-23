<?php

namespace App\Controllers;

class CustomerOrdersController {
    
    public function index() {
        // Obter email do cliente (pode ser da sessão ou de um formulário)
        $email = $_GET['email'] ?? $_POST['email'] ?? '';

        if (empty($email)) {
            view('customer-orders/search');
            return;
        }

        // Buscar cliente por email
        $customer = new \Customer();
        $customerData = $customer->findByEmail($email);

        if (!$customerData) {
            $_SESSION['error'] = 'Cliente não encontrado';
            view('customer-orders/search');
            return;
        }

        // Buscar pedidos do cliente
        $order = new \Order();
        $orders = $order->getByCustomer($customerData->id);

        view('customer-orders/index', [
            'customer' => $customerData,
            'orders' => $orders
        ]);
    }

    public function detail() {
        $orderId = $_GET['id'] ?? '';

        if (empty($orderId)) {
            redirect('/meus-pedidos');
        }

        $order = new \Order();
        $orderData = $order->find($orderId);

        if (!$orderData) {
            $_SESSION['error'] = 'Pedido não encontrado';
            redirect('/meus-pedidos');
        }

        // Obter itens do pedido
        $items = $order->getItems($orderId);

        view('customer-orders/detail', [
            'order' => $orderData,
            'items' => $items
        ]);
    }
}
?>
