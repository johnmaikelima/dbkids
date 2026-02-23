<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/functions.php';

$uploadDir = BASE_PATH . '/public/uploads/products/';

echo "=== Teste de Upload ===\n\n";

echo "1. Verificar pasta de uploads:\n";
echo "   Caminho: " . $uploadDir . "\n";
echo "   Existe: " . (is_dir($uploadDir) ? "SIM" : "NÃO") . "\n";
echo "   Permissão escrita: " . (is_writable($uploadDir) ? "SIM" : "NÃO") . "\n";

echo "\n2. Listar arquivos na pasta:\n";
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    if (count($files) > 2) {
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "   - " . $file . "\n";
            }
        }
    } else {
        echo "   (pasta vazia)\n";
    }
}

echo "\n3. Verificar banco de dados:\n";
try {
    $db = getDB();
    $stmt = $db->query("SELECT id, image_path FROM product_images");
    $images = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "   Total de imagens no banco: " . count($images) . "\n";
    foreach ($images as $image) {
        $filePath = BASE_PATH . '/public' . $image->image_path;
        $exists = file_exists($filePath) ? "✓" : "✗";
        echo "   {$exists} ID: {$image->id} - {$image->image_path}\n";
        if (!file_exists($filePath)) {
            echo "      Arquivo não encontrado em: {$filePath}\n";
        }
    }
} catch (Exception $e) {
    echo "   Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
