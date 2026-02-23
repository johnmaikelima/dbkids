<?php

namespace App\Controllers;

class OrderController {
    
    public function index() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $order = new \Order();
        $orders = $order->paginate($_GET['page'] ?? 1, 20);

        view('admin/orders/index', ['orders' => $orders]);
    }

    public function show($id) {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $order = new \Order();
        $orderData = $order->find($id);

        if (!$orderData) {
            $_SESSION['error'] = 'Pedido não encontrado';
            redirect('/admin/pedidos');
        }

        $items = $order->getItems($id);

        view('admin/orders/show', [
            'order' => $orderData,
            'items' => $items
        ]);
    }

    public function updateStatus() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/pedidos');
        }

        $orderId = $_POST['order_id'] ?? '';
        $status = $_POST['status'] ?? '';

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Status inválido';
            redirect('/admin/pedidos/' . $orderId);
        }

        try {
            $order = new \Order();
            $order->updateStatus($orderId, $status);
            $_SESSION['success'] = 'Status do pedido atualizado!';
            redirect('/admin/pedidos/' . $orderId);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/pedidos/' . $orderId);
        }
    }
}
