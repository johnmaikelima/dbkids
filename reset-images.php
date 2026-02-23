<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    
    // Deletar todas as imagens do banco
    $db->exec("DELETE FROM product_images");
    
    echo "✓ Todas as imagens foram removidas do banco de dados\n";
    
    // Deletar arquivos da pasta
    $uploadDir = BASE_PATH . '/public/uploads/products/';
    if (is_dir($uploadDir)) {
        $files = scandir($uploadDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                unlink($uploadDir . $file);
                echo "✓ Arquivo deletado: " . $file . "\n";
            }
        }
    }
    
    echo "\n✓ Limpeza completa!\n";
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
?>
