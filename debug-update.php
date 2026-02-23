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

echo "=== Debug Update Produto ===\n\n";

// Simular atualização
$productId = 1;
$data = [
    'category_id' => 1,
    'name' => 'Produto Teste Atualizado',
    'description' => 'Descrição teste',
    'price' => 99.99,
    'stock' => 10,
    'weight' => null,
    'length' => null,
    'width' => null,
    'height' => null
];

try {
    $product = new Product();
    $result = $product->update($productId, $data);
    
    if ($result) {
        echo "✓ Produto atualizado com sucesso!\n\n";
        
        // Verificar no banco
        $updated = $product->find($productId);
        echo "Dados atualizados:\n";
        echo "  Nome: " . $updated->name . "\n";
        echo "  Preço: " . $updated->price . "\n";
        echo "  Estoque: " . $updated->stock . "\n";
    } else {
        echo "✗ Erro ao atualizar produto\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
