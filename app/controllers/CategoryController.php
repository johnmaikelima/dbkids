<?php

namespace App\Controllers;

class CategoryController {
    
    public function index() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $category = new \Category();
        $categories = $category->all();

        view('admin/categories/index', ['categories' => $categories]);
    }

    public function create() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        view('admin/categories/create');
    }

    public function store() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/categorias');
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        if (empty($data['name'])) {
            // Se for requisição AJAX, retornar JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Nome da categoria é obrigatório']);
                exit;
            }
            $_SESSION['error'] = 'Nome da categoria é obrigatório';
            redirect('/admin/categorias/nova');
        }

        try {
            $category = new \Category();
            $categoryId = $category->create($data);
            
            // Se for requisição AJAX, retornar JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'id' => $categoryId,
                    'name' => $data['name'],
                    'message' => 'Categoria criada com sucesso!'
                ]);
                exit;
            }
            
            $_SESSION['success'] = 'Categoria criada com sucesso!';
            redirect('/admin/categorias');
        } catch (Exception $e) {
            // Se for requisição AJAX, retornar JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/categorias/nova');
        }
    }

    public function edit($id) {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $category = new \Category();
        $categoryData = $category->find($id);

        if (!$categoryData) {
            $_SESSION['error'] = 'Categoria não encontrada';
            redirect('/admin/categorias');
        }

        view('admin/categories/edit', ['category' => $categoryData]);
    }

    public function update() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/categorias');
        }

        $id = $_POST['id'] ?? '';
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        if (empty($data['name'])) {
            $_SESSION['error'] = 'Nome da categoria é obrigatório';
            redirect('/admin/categorias/editar/' . $id);
        }

        try {
            $category = new \Category();
            $category->update($id, $data);
            $_SESSION['success'] = 'Categoria atualizada com sucesso!';
            redirect('/admin/categorias');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/categorias/editar/' . $id);
        }
    }

    public function delete($id) {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/categorias');
        }

        try {
            $category = new \Category();
            $category->delete($id);
            $_SESSION['success'] = 'Categoria deletada com sucesso!';
            redirect('/admin/categorias');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/categorias');
        }
    }

    public function show($slug) {
        $category = new \Category();
        $categoryData = $category->findBySlug($slug);

        if (!$categoryData) {
            redirect('/');
        }

        $product = new \Product();
        $sort = $_GET['sort'] ?? 'relevancia';
        
        $products = $product->getByCategory($categoryData->id);

        // Carregar imagens para cada produto
        foreach ($products as $prod) {
            $prod->images = $product->getImages($prod->id);
        }

        // Ordenação
        switch ($sort) {
            case 'preco_asc':
                usort($products, function($a, $b) {
                    return $a->price <=> $b->price;
                });
                break;
            case 'preco_desc':
                usort($products, function($a, $b) {
                    return $b->price <=> $a->price;
                });
                break;
            case 'nome':
                usort($products, function($a, $b) {
                    return strcmp($a->name, $b->name);
                });
                break;
            case 'relevancia':
            default:
                // Manter ordem padrão
                break;
        }

        view('category/show', [
            'category' => $categoryData,
            'products' => $products,
            'currentSort' => $sort
        ]);
    }
}
