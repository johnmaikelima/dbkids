<?php

namespace App\Controllers;

class SearchController {
    
    public function index() {
        $query = $_GET['q'] ?? '';
        $products = [];
        
        if (!empty($query)) {
            $product = new \Product();
            $products = $product->search($query);
            
            // Carregar imagens para cada produto
            foreach ($products as $prod) {
                $prod->images = $product->getImages($prod->id);
            }
        }
        
        view('search/results', [
            'query' => $query,
            'products' => $products
        ]);
    }
}
