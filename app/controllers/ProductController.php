<?php

namespace App\Controllers;

class ProductController {
    
    public function index() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $product = new \Product();
        $products = $product->paginate($_GET['page'] ?? 1, 20);

        view('admin/products/index', ['products' => $products]);
    }

    public function create() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $category = new \Category();
        $categories = $category->all();

        view('admin/products/create', ['categories' => $categories]);
    }

    public function store() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $data = [
            'category_id' => $_POST['category_id'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'stock' => $_POST['stock'] ?? 0,
            'weight' => $_POST['weight'] ?? null,
            'length' => $_POST['length'] ?? null,
            'width' => $_POST['width'] ?? null,
            'height' => $_POST['height'] ?? null
        ];

        // Validar
        if (empty($data['name']) || empty($data['price'])) {
            $_SESSION['error'] = 'Nome e preço são obrigatórios';
            redirect('/admin/produtos/novo');
        }

        // Se nenhuma categoria foi selecionada via múltipla seleção, usar a categoria_id padrão
        $categoryIds = $_POST['category_ids'] ?? [];
        if (empty($categoryIds) && !empty($data['category_id'])) {
            $categoryIds = [$data['category_id']];
        }

        if (empty($categoryIds)) {
            $_SESSION['error'] = 'Selecione pelo menos uma categoria';
            redirect('/admin/produtos/novo');
        }

        try {
            $product = new \Product();
            $productId = $product->create($data);

            // Salvar múltiplas categorias
            $product->updateCategories($productId, $categoryIds);

            // Processar imagens
            if (!empty($_FILES['images'])) {
                $this->uploadImages($productId, $_FILES['images']);
            }

            // Processar características
            if (!empty($_POST['attributes'])) {
                foreach ($_POST['attributes'] as $attr) {
                    if (!empty($attr['name']) && !empty($attr['value'])) {
                        $product->addAttribute($productId, $attr['name'], $attr['value']);
                    }
                }
            }

            $_SESSION['success'] = 'Produto criado com sucesso!';
            redirect('/admin/produtos');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/produtos/novo');
        }
    }

    public function edit($id) {
        if (!isAdmin()) {
            redirect('/');
        }

        $product = new \Product();
        $productData = $product->find($id);

        if (!$productData) {
            $_SESSION['error'] = 'Produto não encontrado';
            redirect('/admin/produtos');
        }

        $category = new \Category();
        $categories = $category->all();

        $images = $product->getImages($id);

        $attributes = $product->getAttributes($id);

        // Obter tipos de variações e suas opções
        $variationType = new \VariationType();
        $variationTypes = $variationType->getByProduct($id);

        view('admin/products/edit', [
            'product' => $productData,
            'categories' => $categories,
            'attributes' => $attributes,
            'images' => $images,
            'variationTypes' => $variationTypes
        ]);
    }

    public function update() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $id = $_POST['id'] ?? '';

        // Se apenas imagens foram enviadas (sem name), fazer upload e voltar para edição
        if (empty($_POST['name']) && !empty($id)) {
            if (!empty($_FILES['images']['name'][0])) {
                $this->uploadImages($id, $_FILES['images']);
                $_SESSION['success'] = 'Imagens enviadas com sucesso!';
            }
            redirect('/admin/produtos/editar/' . $id);
            return;
        }

        $data = [
            'category_id' => $_POST['category_id'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => floatval($_POST['price'] ?? 0),
            'stock' => intval($_POST['stock'] ?? 0),
            'weight' => !empty($_POST['weight']) ? floatval($_POST['weight']) : null,
            'length' => !empty($_POST['length']) ? floatval($_POST['length']) : null,
            'width' => !empty($_POST['width']) ? floatval($_POST['width']) : null,
            'height' => !empty($_POST['height']) ? floatval($_POST['height']) : null
        ];

        if (empty($id) || empty($data['name'])) {
            $_SESSION['error'] = 'ID ou nome do produto inválido';
            redirect('/admin/produtos/editar/' . $id);
            return;
        }

        // Se nenhuma categoria foi selecionada via múltipla seleção, usar a categoria_id padrão
        $categoryIds = $_POST['category_ids'] ?? [];
        
        // Garantir que categoryIds é um array
        if (!is_array($categoryIds)) {
            $categoryIds = !empty($categoryIds) ? [$categoryIds] : [];
        }
        
        // Converter para inteiros
        $categoryIds = array_map('intval', array_filter($categoryIds));
        
        if (empty($categoryIds)) {
            $_SESSION['error'] = 'Selecione pelo menos uma categoria';
            redirect('/admin/produtos/editar/' . $id);
            return;
        }

        try {
            $product = new \Product();
            $result = $product->update($id, $data);
            
            if (!$result) {
                $_SESSION['error'] = 'Falha ao atualizar produto no banco de dados';
                redirect('/admin/produtos/editar/' . $id);
                return;
            }

            // Debug: registrar categorias recebidas
            $logFile = BASE_PATH . '/logs/categories-debug.log';
            $logDir = dirname($logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Produto ID: {$id}, Categorias: " . json_encode($categoryIds) . "\n", FILE_APPEND);

            // Atualizar múltiplas categorias
            $product->updateCategories($id, $categoryIds);
            
            // Processar novas imagens se houver
            $uploadedCount = 0;
            
            // Debug: salvar informações sobre o upload
            $logFile = BASE_PATH . '/logs/upload-debug.log';
            $logDir = dirname($logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            $debugInfo = date('Y-m-d H:i:s') . " - Update Produto ID: {$id}\n";
            $debugInfo .= "FILES isset: " . (isset($_FILES['images']) ? 'SIM' : 'NÃO') . "\n";
            if (isset($_FILES['images'])) {
                $debugInfo .= "FILES é array: " . (is_array($_FILES['images']['tmp_name']) ? 'SIM' : 'NÃO') . "\n";
                $debugInfo .= "Quantidade de arquivos: " . count($_FILES['images']['tmp_name']) . "\n";
                foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                    $debugInfo .= "  Arquivo {$key}: tmp={$tmpName}, erro={$_FILES['images']['error'][$key]}\n";
                }
            }
            file_put_contents($logFile, $debugInfo . "\n", FILE_APPEND);
            
            if (isset($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
                $hasImages = false;
                foreach ($_FILES['images']['tmp_name'] as $tmpName) {
                    if (!empty($tmpName)) {
                        $hasImages = true;
                        break;
                    }
                }
                
                if ($hasImages) {
                    try {
                        $uploadedCount = $this->uploadImages($id, $_FILES['images']);
                        file_put_contents($logFile, "Upload realizado: {$uploadedCount} imagens\n", FILE_APPEND);
                    } catch (Exception $imageError) {
                        file_put_contents($logFile, "ERRO: " . $imageError->getMessage() . "\n", FILE_APPEND);
                        $_SESSION['warning'] = 'Produto atualizado, mas houve erro ao salvar imagens: ' . $imageError->getMessage();
                        redirect('/admin/produtos');
                        return;
                    }
                }
            }
            
            // Mensagem de sucesso
            if ($uploadedCount > 0) {
                $_SESSION['success'] = "Produto atualizado! {$uploadedCount} imagem(ns) adicionada(s).";
            } else {
                $_SESSION['success'] = 'Produto atualizado com sucesso!';
            }
            
            redirect('/admin/produtos');
            return;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $id);
            return;
        }
    }

    public function delete($id) {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        try {
            $product = new \Product();
            $product->delete($id);
            $_SESSION['success'] = 'Produto deletado com sucesso!';
            redirect('/admin/produtos');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/produtos');
        }
    }

    public function show($slug) {
        $product = new \Product();
        $productData = $product->findBySlug($slug);

        if (!$productData) {
            http_response_code(404);
            view('404');
            return;
        }

        $attributes = $product->getAttributes($productData->id);
        $images = $product->getImages($productData->id);

        // Obter tipos de variações e suas opções
        $variationType = new \VariationType();
        $variationTypes = $variationType->getByProduct($productData->id);

        // Buscar primeira categoria do produto para o breadcrumb
        $db = getDB();
        $stmt = $db->prepare("
            SELECT c.id, c.name, c.slug 
            FROM product_categories pc
            LEFT JOIN categories c ON pc.category_id = c.id
            WHERE pc.product_id = ?
            LIMIT 1
        ");
        $stmt->execute([$productData->id]);
        $firstCategory = $stmt->fetch(\PDO::FETCH_OBJ);

        view('product/show', [
            'product' => $productData,
            'attributes' => $attributes,
            'images' => $images,
            'variationTypes' => $variationTypes,
            'firstCategory' => $firstCategory
        ]);
    }

    public function setMainImage() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $imageId = $_POST['image_id'] ?? '';
        $productId = $_POST['product_id'] ?? '';

        if (empty($imageId) || empty($productId)) {
            $_SESSION['error'] = 'Dados inválidos';
            redirect('/admin/produtos/editar/' . $productId);
        }

        try {
            $product = new \Product();
            $product->setMainImage($productId, $imageId);
            $_SESSION['success'] = 'Imagem de capa atualizada com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function deleteImage() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $imageId = $_POST['image_id'] ?? '';
        $productId = $_POST['product_id'] ?? '';

        if (empty($imageId) || empty($productId)) {
            $_SESSION['error'] = 'Dados inválidos: imageId=' . $imageId . ', productId=' . $productId;
            redirect('/admin/produtos/editar/' . $productId);
        }

        try {
            $product = new \Product();
            $result = $product->deleteImage($imageId, $productId);
            
            if ($result) {
                $_SESSION['success'] = 'Imagem deletada com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao deletar imagem do banco de dados';
            }
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    private function uploadImages($productId, $files) {
        $uploadDir = BASE_PATH . '/public/uploads/products/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $product = new \Product();
        $uploadedCount = 0;
        
        // Verificar se há arquivos
        if (!isset($files['tmp_name']) || !is_array($files['tmp_name'])) {
            return 0;
        }

        // Verificar se já existe uma imagem de capa
        $existingImages = $product->getImages($productId);
        $hasCover = false;
        foreach ($existingImages as $img) {
            if ($img->is_main) {
                $hasCover = true;
                break;
            }
        }

        foreach ($files['tmp_name'] as $key => $tmpName) {
            // Pular se não há arquivo
            if (empty($tmpName)) {
                continue;
            }
            
            // Verificar erro de upload
            if ($files['error'][$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            // Validar se é imagem
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $tmpName);
            finfo_close($finfo);
            
            if (strpos($fileType, 'image') === false) {
                continue;
            }

            // Gerar nome único com delay para evitar nomes duplicados
            usleep(100000); // 0.1 segundo
            $extension = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
            $fileName = 'product_' . $productId . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $filePath = $uploadDir . $fileName;

            // Tentar mover arquivo
            if (move_uploaded_file($tmpName, $filePath)) {
                // Se não há capa, a primeira imagem será a capa
                $isMain = (!$hasCover && $uploadedCount === 0) ? 1 : 0;
                
                // Salvar no banco
                $product->addImage($productId, '/public/uploads/products/' . $fileName, $isMain);
                $uploadedCount++;
            }
        }
        
        return $uploadedCount;
    }

    public function bulkCategories() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $product = new \Product();
        $category = new \Category();
        
        // Buscar produtos com suas categorias
        $products = $product->all();
        
        // Para cada produto, buscar suas categorias
        $db = getDB();
        foreach ($products as $prod) {
            $stmt = $db->prepare("
                SELECT GROUP_CONCAT(c.name, ', ') as category_names
                FROM product_categories pc
                LEFT JOIN categories c ON pc.category_id = c.id
                WHERE pc.product_id = ?
                GROUP BY pc.product_id
            ");
            $stmt->execute([$prod->id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $prod->category_names = $result['category_names'] ?? null;
        }
        
        $categories = $category->all();

        view('admin/products/bulk-categories', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function updateBulkCategories() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos/categorias-massa');
        }

        $productIds = $_POST['product_ids'] ?? [];
        $action = $_POST['action'] ?? '';
        $categoryIds = $_POST['category_ids'] ?? [];

        if (empty($productIds) || empty($categoryIds)) {
            $_SESSION['error'] = 'Selecione pelo menos um produto e uma categoria';
            redirect('/admin/produtos/categorias-massa');
        }

        $product = new \Product();
        $db = getDB();

        try {
            $db->beginTransaction();

            foreach ($productIds as $productId) {
                if ($action === 'add') {
                    // Adicionar categorias (sem remover as existentes)
                    foreach ($categoryIds as $categoryId) {
                        // Verificar se já existe
                        $stmt = $db->prepare("SELECT id FROM product_categories WHERE product_id = ? AND category_id = ?");
                        $stmt->execute([$productId, $categoryId]);
                        
                        if (!$stmt->fetch()) {
                            // Adicionar apenas se não existir
                            $stmt = $db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
                            $stmt->execute([$productId, $categoryId]);
                        }
                    }
                } elseif ($action === 'remove') {
                    // Remover categorias específicas
                    $placeholders = str_repeat('?,', count($categoryIds) - 1) . '?';
                    $stmt = $db->prepare("DELETE FROM product_categories WHERE product_id = ? AND category_id IN ($placeholders)");
                    $stmt->execute(array_merge([$productId], $categoryIds));
                } elseif ($action === 'replace') {
                    // Substituir todas as categorias
                    $stmt = $db->prepare("DELETE FROM product_categories WHERE product_id = ?");
                    $stmt->execute([$productId]);
                    
                    foreach ($categoryIds as $categoryId) {
                        $stmt = $db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
                        $stmt->execute([$productId, $categoryId]);
                    }
                }
            }

            $db->commit();
            
            $productCount = count($productIds);
            $categoryCount = count($categoryIds);
            $_SESSION['success'] = "Categorias atualizadas com sucesso! {$productCount} produto(s) afetado(s).";
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Erro ao atualizar categorias: ' . $e->getMessage();
        }

        redirect('/admin/produtos/categorias-massa');
    }
}
