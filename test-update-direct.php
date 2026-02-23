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

echo "=== Teste de Update Direto ===\n\n";

try {
    // Obter primeiro produto
    $product = new Product();
    $p = $product->find(1);
    
    if (!$p) {
        echo "✗ Produto 1 não encontrado\n";
        exit;
    }
    
    echo "Produto encontrado:\n";
    echo "  ID: {$p->id}\n";
    echo "  Nome: {$p->name}\n";
    echo "  Preço: {$p->price}\n";
    echo "  Estoque: {$p->stock}\n\n";
    
    // Tentar atualizar
    echo "Tentando atualizar...\n";
    $data = [
        'category_id' => $p->category_id,
        'name' => $p->name . ' - ATUALIZADO',
        'description' => $p->description,
        'price' => $p->price + 10,
        'stock' => $p->stock + 5,
        'weight' => $p->weight,
        'length' => $p->length,
        'width' => $p->width,
        'height' => $p->height
    ];
    
    $result = $product->update(1, $data);
    
    if ($result) {
        echo "✓ Update retornou true\n\n";
        
        // Verificar se foi atualizado
        $updated = $product->find(1);
        echo "Dados após update:\n";
        echo "  Nome: {$updated->name}\n";
        echo "  Preço: {$updated->price}\n";
        echo "  Estoque: {$updated->stock}\n";
    } else {
        echo "✗ Update retornou false\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
    echo "   Stack: " . $e->getTraceAsString() . "\n";
}

echo "\n";
?>
