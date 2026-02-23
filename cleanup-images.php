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

echo "=== Limpeza de Imagens Órfãs ===\n\n";

try {
    $db = getDB();
    
    // Buscar todas as imagens
    $stmt = $db->query("SELECT id, image_path, product_id FROM product_images");
    $images = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $deleted = 0;
    
    foreach ($images as $image) {
        $filePath = BASE_PATH . '/public' . $image->image_path;
        
        // Se arquivo não existe, deletar do banco
        if (!file_exists($filePath)) {
            $deleteStmt = $db->prepare("DELETE FROM product_images WHERE id = ?");
            $deleteStmt->execute([$image->id]);
            $deleted++;
            
            echo "✓ Deletada imagem órfã: ID {$image->id} - {$image->image_path}\n";
        }
    }
    
    echo "\n========================================\n";
    echo "✓ Total de imagens órfãs deletadas: {$deleted}\n";
    echo "========================================\n";
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
