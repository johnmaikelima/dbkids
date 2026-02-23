<?php

namespace App\Controllers;

class CartController {
    
    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        $total = $this->calculateTotal($cart);

        view('cart/index', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/carrinho');
        }

        $productId = $_POST['product_id'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 1);

        if (empty($productId) || $quantity < 1) {
            $_SESSION['error'] = 'Dados inválidos';
            redirect('/carrinho');
        }

        // Obter dados do produto
        $product = new \Product();
        $productData = $product->find($productId);

        if (!$productData) {
            $_SESSION['error'] = 'Produto não encontrado';
            redirect('/carrinho');
        }

        // Inicializar carrinho se não existir
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Verificar se produto já está no carrinho
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // Se não encontrou, adicionar novo item
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $productData->id,
                'name' => $productData->name,
                'slug' => $productData->slug,
                'price' => $productData->price,
                'quantity' => $quantity,
                'image' => $this->getMainImage($productData->id)
            ];
        }

        $_SESSION['success'] = 'Produto adicionado ao carrinho!';
        redirect('/carrinho');
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/carrinho');
        }

        $productId = $_POST['product_id'] ?? '';

        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($productId) {
                return $item['id'] != $productId;
            });
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }

        $_SESSION['success'] = 'Produto removido do carrinho!';
        redirect('/carrinho');
    }

    private function calculateTotal($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    private function getMainImage($productId) {
        $product = new \Product();
        $images = $product->getImages($productId);
        
        if (!empty($images)) {
            return $images[0]->image_path;
        }
        
        return '/images/placeholder.png';
    }
}
