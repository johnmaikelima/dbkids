<?php

namespace App\Controllers;

class DashboardController {
    
    public function index() {
        // Verificar autenticação
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $userModel = new \User();
        $productModel = new \Product();
        $customerModel = new \Customer();
        $orderModel = new \Order();

        // Obter estatísticas
        $stats = [
            'total_users' => $userModel->count(),
            'total_products' => $productModel->count(),
            'total_customers' => $customerModel->count(),
            'total_orders' => $orderModel->count(),
            'total_sales' => $orderModel->getTotalSales(),
            'pending_orders' => count($orderModel->getByStatus('pending')),
            'recent_orders' => $orderModel->paginate(1, 5),
            'recent_customers' => $customerModel->paginate(1, 5)
        ];

        view('admin/dashboard', $stats);
    }
}
