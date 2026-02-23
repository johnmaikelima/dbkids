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

echo "=== Teste Exato do Formulário ===\n\n";

// Simular exatamente o que o formulário envia
$_POST = [
    'id' => '1',
    'category_id' => '1',
    'name' => 'PRODUTO ATUALIZADO VIA FORMULÁRIO - ' . date('H:i:s'),
    'description' => 'Teste',
    'price' => '500.00',
    'stock' => '50',
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
        'error' => [UPLOAD_ERR_NO_FILE],
        'size' => []
    ]
];

try {
    // Simular o controller
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

    echo "Dados a atualizar:\n";
    echo "  ID: {$id}\n";
    echo "  Nome: {$data['name']}\n";
    echo "  Preço: {$data['price']}\n";
    echo "  Estoque: {$data['stock']}\n\n";

    // Antes
    $db = getDB();
    $stmt = $db->prepare("SELECT name, price, stock FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $before = $stmt->fetch(PDO::FETCH_OBJ);
    echo "ANTES:\n";
    echo "  Nome: {$before->name}\n";
    echo "  Preço: {$before->price}\n";
    echo "  Estoque: {$before->stock}\n\n";

    // Atualizar
    $product = new Product();
    $result = $product->update($id, $data);
    
    echo "Resultado do update: " . ($result ? "TRUE" : "FALSE") . "\n\n";

    // Depois
    $stmt = $db->prepare("SELECT name, price, stock FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $after = $stmt->fetch(PDO::FETCH_OBJ);
    echo "DEPOIS:\n";
    echo "  Nome: {$after->name}\n";
    echo "  Preço: {$after->price}\n";
    echo "  Estoque: {$after->stock}\n\n";

    if ($before->name !== $after->name) {
        echo "✓ FUNCIONANDO!\n";
    } else {
        echo "✗ NÃO FUNCIONANDO - Dados não foram atualizados\n";
    }

} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
