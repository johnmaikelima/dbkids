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

echo "=== Debug Update Issue ===\n\n";

try {
    $db = getDB();
    
    // 1. Verificar produto antes
    echo "1. Produto ANTES da atualização:\n";
    $stmt = $db->prepare("SELECT id, name, price, stock FROM products WHERE id = 1");
    $stmt->execute();
    $before = $stmt->fetch(PDO::FETCH_OBJ);
    echo "   Nome: {$before->name}\n";
    echo "   Preço: {$before->price}\n";
    echo "   Estoque: {$before->stock}\n\n";
    
    // 2. Tentar atualizar
    echo "2. Tentando atualizar...\n";
    $product = new Product();
    $data = [
        'category_id' => 1,
        'name' => 'PRODUTO TESTE ATUALIZADO - ' . date('H:i:s'),
        'description' => 'Descrição teste',
        'price' => 999.99,
        'stock' => 100,
        'weight' => null,
        'length' => null,
        'width' => null,
        'height' => null
    ];
    
    $result = $product->update(1, $data);
    echo "   Resultado: " . ($result ? "TRUE" : "FALSE") . "\n\n";
    
    // 3. Verificar produto depois
    echo "3. Produto DEPOIS da atualização:\n";
    $stmt = $db->prepare("SELECT id, name, price, stock FROM products WHERE id = 1");
    $stmt->execute();
    $after = $stmt->fetch(PDO::FETCH_OBJ);
    echo "   Nome: {$after->name}\n";
    echo "   Preço: {$after->price}\n";
    echo "   Estoque: {$after->stock}\n\n";
    
    // 4. Verificar se mudou
    if ($before->name !== $after->name) {
        echo "✓ UPDATE FUNCIONANDO!\n";
    } else {
        echo "✗ UPDATE NÃO ESTÁ FUNCIONANDO\n";
        echo "   Nome não mudou\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
