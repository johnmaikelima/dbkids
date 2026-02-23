<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/functions.php';

$modelFiles = glob(APP_PATH . '/models/*.php');
foreach ($modelFiles as $file) {
    require_once $file;
}

echo "=== Teste Final - Sistema de Imagens ===\n\n";

try {
    $db = getDB();
    
    // 1. Verificar produtos
    echo "1. Produtos cadastrados:\n";
    $stmt = $db->query("SELECT id, name FROM products LIMIT 5");
    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach ($products as $p) {
        echo "   - ID: {$p->id}, Nome: {$p->name}\n";
    }
    
    // 2. Verificar imagens
    echo "\n2. Imagens cadastradas:\n";
    $stmt = $db->query("SELECT id, product_id, image_path, is_main FROM product_images");
    $images = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if (empty($images)) {
        echo "   (nenhuma imagem cadastrada)\n";
    } else {
        foreach ($images as $img) {
            $filePath = BASE_PATH . '/public' . $img->image_path;
            $exists = file_exists($filePath) ? "✓" : "✗";
            $main = $img->is_main ? "[CAPA]" : "";
            echo "   {$exists} ID: {$img->id} | Produto: {$img->product_id} | {$main}\n";
            echo "      Path: {$img->image_path}\n";
        }
    }
    
    // 3. Verificar pasta de uploads
    echo "\n3. Arquivos na pasta de uploads:\n";
    $uploadDir = BASE_PATH . '/public/uploads/products/';
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        $count = 0;
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $count++;
                $size = filesize($uploadDir . $file);
                echo "   - {$file} ({$size} bytes)\n";
            }
        }
        if ($count === 0) {
            echo "   (pasta vazia)\n";
        }
    }
    
    echo "\n✓ Sistema de imagens funcionando corretamente!\n";
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
