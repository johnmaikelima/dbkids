<?php

namespace App\Controllers;

class HomeController {
    
    public function index() {
        $product = new \Product();
        $category = new \Category();

        $products = $product->paginate(1, 12);
        $categories = $category->all();

        view('home/index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
