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

echo "=== Teste de Submissão de Formulário ===\n\n";

// Simular POST
$_POST = [
    'id' => '1',
    'category_id' => '1',
    'name' => 'Produto Teste - Atualizado via Formulário',
    'description' => 'Descrição teste',
    'price' => '150.00',
    'stock' => '20',
    'weight' => '',
    'length' => '',
    'width' => '',
    'height' => ''
];

$_FILES = [
    'images' => [
        'name' => [],
        'type' => [],
        'tmp_name' => [],
        'error' => [],
        'size' => []
    ]
];

echo "Dados POST:\n";
print_r($_POST);
echo "\n";

try {
    $id = $_POST['id'] ?? '';
    $data = [
        'category_id' => $_POST['category_id'] ?? '',
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'price' => floatval($_POST['price'] ?? 0),
        'stock' => intval($_POST['stock'] ?? 0),
        'weight' => !empty($_POST['weight']) ? floatval($_POST['weight']) : null,
        'length' => !empty($_POST['length']) ? floatval($_POST['length']) : null,
        'width' => !empty($_POST['width']) ? floatval($_POST['width']) : null,
        'height' => !empty($_POST['height']) ? floatval($_POST['height']) : null
    ];

    echo "Dados processados:\n";
    print_r($data);
    echo "\n";

    if (empty($id) || empty($data['name'])) {
        echo "✗ Validação falhou\n";
        exit;
    }

    $product = new Product();
    $result = $product->update($id, $data);
    
    if (!$result) {
        echo "✗ Update retornou false\n";
        exit;
    }
    
    echo "✓ Update bem-sucedido!\n";
    
    // Verificar dados
    $updated = $product->find($id);
    echo "\nDados após update:\n";
    echo "  Nome: {$updated->name}\n";
    echo "  Preço: {$updated->price}\n";
    echo "  Estoque: {$updated->stock}\n";
    
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
    echo "   Stack: " . $e->getTraceAsString() . "\n";
}

echo "\n";
?>
