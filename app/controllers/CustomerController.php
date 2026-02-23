<?php

namespace App\Controllers;

class CustomerController {
    
    public function index() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $customer = new \Customer();
        $customers = $customer->paginate($_GET['page'] ?? 1, 20);

        view('admin/customers/index', ['customers' => $customers]);
    }

    public function show($id) {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $customer = new \Customer();
        $customerData = $customer->find($id);

        if (!$customerData) {
            $_SESSION['error'] = 'Cliente não encontrado';
            redirect('/admin/clientes');
        }

        $order = new \Order();
        $orders = $order->getByCustomer($id);

        view('admin/customers/show', [
            'customer' => $customerData,
            'orders' => $orders
        ]);
    }
}
