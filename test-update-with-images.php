<?php
session_start();

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/functions.php';

$modelFiles = glob(APP_PATH . '/models/*.php');
foreach ($modelFiles as $file) {
    require_once $file;
}

echo "=== Teste de Update com Imagens ===\n\n";

try {
    // 1. Atualizar produto
    $product = new Product();
    $data = [
        'category_id' => 1,
        'name' => 'Produto com Imagem - ' . date('H:i:s'),
        'description' => 'Teste',
        'price' => 199.99,
        'stock' => 30,
        'weight' => null,
        'length' => null,
        'width' => null,
        'height' => null
    ];
    
    echo "1. Atualizando produto...\n";
    $result = $product->update(1, $data);
    
    if (!$result) {
        echo "   ✗ Falha ao atualizar\n";
        exit;
    }
    echo "   ✓ Produto atualizado\n\n";
    
    // 2. Criar imagem de teste
    echo "2. Criando imagem de teste...\n";
    $uploadDir = BASE_PATH . '/public/uploads/products/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $image = imagecreatetruecolor(200, 200);
    $blue = imagecolorallocate($image, 0, 102, 204);
    imagefill($image, 0, 0, $blue);
    
    $fileName = 'test_' . time() . '.png';
    $filePath = $uploadDir . $fileName;
    imagepng($image, $filePath);
    imagedestroy($image);
    
    echo "   ✓ Imagem criada: {$fileName}\n\n";
    
    // 3. Adicionar imagem ao banco
    echo "3. Adicionando imagem ao banco...\n";
    $product->addImage(1, '/public/uploads/products/' . $fileName, 0);
    echo "   ✓ Imagem adicionada\n\n";
    
    // 4. Verificar resultado
    echo "4. Verificando resultado...\n";
    $updated = $product->find(1);
    $images = $product->getImages(1);
    
    echo "   Produto:\n";
    echo "     Nome: {$updated->name}\n";
    echo "     Preço: {$updated->price}\n";
    echo "   Imagens: " . count($images) . "\n";
    foreach ($images as $img) {
        echo "     - {$img->image_path}\n";
    }
    
    echo "\n✓ Teste completo com sucesso!\n";
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
    echo "   Stack: " . $e->getTraceAsString() . "\n";
}

echo "\n";
?>
