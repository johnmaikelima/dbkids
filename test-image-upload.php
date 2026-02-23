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

echo "=== Teste de Upload de Imagem ===\n\n";

// Simular upload
$uploadDir = BASE_PATH . '/public/uploads/products/';
$testFile = $uploadDir . 'test_image.png';

// Criar uma imagem PNG simples para teste
$image = imagecreatetruecolor(100, 100);
$white = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $white);
imagepng($image, $testFile);
imagedestroy($image);

echo "1. Arquivo de teste criado: " . $testFile . "\n";
echo "   Existe: " . (file_exists($testFile) ? "SIM" : "NÃO") . "\n";
echo "   Tamanho: " . filesize($testFile) . " bytes\n\n";

// Tentar salvar no banco
try {
    $product = new Product();
    $product->addImage(1, '/public/uploads/products/test_image.png', 1);
    
    echo "2. Imagem salva no banco com sucesso!\n\n";
    
    // Verificar no banco
    $db = getDB();
    $stmt = $db->query("SELECT id, image_path FROM product_images WHERE product_id = 1");
    $images = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "3. Imagens do produto 1 no banco:\n";
    foreach ($images as $img) {
        echo "   - ID: {$img->id}, Path: {$img->image_path}\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
