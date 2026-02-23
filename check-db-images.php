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

echo "=== Verificar Imagens no Banco ===\n\n";

try {
    $db = getDB();
    $stmt = $db->query("SELECT id, product_id, image_path, is_main FROM product_images ORDER BY id DESC");
    $images = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "Total de imagens: " . count($images) . "\n\n";
    
    foreach ($images as $image) {
        $filePath = BASE_PATH . '/public' . $image->image_path;
        $exists = file_exists($filePath) ? "✓" : "✗";
        $main = $image->is_main ? "[CAPA]" : "";
        
        echo "{$exists} ID: {$image->id} | Produto: {$image->product_id} | {$main}\n";
        echo "   Path no DB: {$image->image_path}\n";
        echo "   Arquivo: {$filePath}\n";
        echo "   Existe: " . (file_exists($filePath) ? "SIM" : "NÃO") . "\n\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

echo "\n=== Arquivos na Pasta ===\n\n";
$uploadDir = BASE_PATH . '/public/uploads/products/';
$files = scandir($uploadDir);
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "- " . $file . "\n";
    }
}
?>
