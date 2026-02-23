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

echo "=== Debug Delete Image ===\n\n";

// Simular exclusão de imagem
$imageId = 1;
$productId = 1;

try {
    $product = new Product();
    
    // Obter informações da imagem
    $db = getDB();
    $stmt = $db->prepare("SELECT id, image_path, product_id FROM product_images WHERE id = ?");
    $stmt->execute([$imageId]);
    $image = $stmt->fetch(PDO::FETCH_OBJ);
    
    if (!$image) {
        echo "✗ Imagem não encontrada no banco\n";
        exit;
    }
    
    echo "Imagem encontrada:\n";
    echo "  ID: " . $image->id . "\n";
    echo "  Path: " . $image->image_path . "\n";
    echo "  Product ID: " . $image->product_id . "\n";
    
    // Verificar arquivo físico
    $filePath = BASE_PATH . '/public' . $image->image_path;
    echo "\nArquivo físico:\n";
    echo "  Caminho: " . $filePath . "\n";
    echo "  Existe: " . (file_exists($filePath) ? "SIM" : "NÃO") . "\n";
    
    // Tentar deletar
    echo "\nTentando deletar...\n";
    $result = $product->deleteImage($imageId, $productId);
    
    if ($result) {
        echo "✓ Imagem deletada com sucesso\n";
        
        // Verificar se foi removida do banco
        $stmt = $db->prepare("SELECT id FROM product_images WHERE id = ?");
        $stmt->execute([$imageId]);
        $check = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (!$check) {
            echo "✓ Removida do banco de dados\n";
        } else {
            echo "✗ Ainda existe no banco de dados\n";
        }
        
        // Verificar se arquivo foi deletado
        if (!file_exists($filePath)) {
            echo "✓ Arquivo físico removido\n";
        } else {
            echo "✗ Arquivo físico ainda existe\n";
        }
    } else {
        echo "✗ Erro ao deletar\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
    echo "   Stack: " . $e->getTraceAsString() . "\n";
}

echo "\n";
?>
