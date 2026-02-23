<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/functions.php';

// Carregar Models
$modelFiles = glob(APP_PATH . '/models/*.php');
foreach ($modelFiles as $file) {
    require_once $file;
}

try {
    $db = getDB();
    
    // Buscar todas as imagens com caminho incorreto
    $stmt = $db->query("SELECT id, image_path FROM product_images");
    $images = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    $fixed = 0;
    $deleted = 0;
    
    foreach ($images as $image) {
        // Se o caminho contém /public/public, corrigir
        if (strpos($image->image_path, '/public/public') !== false) {
            $newPath = str_replace('/public/public', '/uploads', $image->image_path);
            
            // Atualizar no banco
            $updateStmt = $db->prepare("UPDATE product_images SET image_path = ? WHERE id = ?");
            $updateStmt->execute([$newPath, $image->id]);
            $fixed++;
            
            echo "✓ Corrigido: {$image->image_path} → {$newPath}\n";
        }
        // Se o caminho contém /public/uploads, deixar como está
        elseif (strpos($image->image_path, '/public/uploads') !== false) {
            echo "✓ Já correto: {$image->image_path}\n";
        }
        // Se o caminho é /uploads, deixar como está
        elseif (strpos($image->image_path, '/uploads/products') !== false) {
            echo "✓ Já correto: {$image->image_path}\n";
        }
        // Caso contrário, tentar corrigir
        else {
            if (strpos($image->image_path, '/uploads') === false) {
                // Extrair apenas o nome do arquivo
                $fileName = basename($image->image_path);
                $newPath = '/uploads/products/' . $fileName;
                
                $updateStmt = $db->prepare("UPDATE product_images SET image_path = ? WHERE id = ?");
                $updateStmt->execute([$newPath, $image->id]);
                $fixed++;
                
                echo "✓ Corrigido: {$image->image_path} → {$newPath}\n";
            }
        }
    }
    
    echo "\n========================================\n";
    echo "✓ Imagens corrigidas: {$fixed}\n";
    echo "✓ Total processado: " . count($images) . "\n";
    echo "========================================\n";
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
?>
