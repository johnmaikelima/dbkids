<?php
session_start();

// Configurações
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

// Autoload
require_once APP_PATH . '/helpers/Env.php';
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/functions.php';

// Carregar Models
$modelFiles = glob(APP_PATH . '/models/*.php');
foreach ($modelFiles as $file) {
    require_once $file;
}

// Dados do produto
$productData = [
    'name' => 'Body Estampado ' . time(),
    'description' => 'O Body em Malha de Manga Curta é prático e super fofo, possui abertura na Gola e fechamento entrepernas com botão de pressão, que facilita na hora de vestir. Perfeito para vários momentos, o modelinho possui várias opções de estampas divertidas, e é uma ótima opção para presentear.',
    'category_id' => 1, // Bodies
    'price' => 0,
    'stock' => 0,
    'weight' => 0.3,
    'length' => null,
    'width' => null,
    'height' => null
];

// Criar produto
$product = new Product();
$productId = $product->create($productData);

if ($productId) {
    echo "✅ Produto criado com ID: {$productId}\n";
    
    // Baixar e salvar imagem
    $imageUrl = 'https://static.wixstatic.com/media/2a909a_be1abdce23de46149afd941c2c5f2368~mv2.png/v1/fit/w_500,h_500,q_90/file.png';
    $uploadDir = PUBLIC_PATH . '/uploads/products/';
    
    // Criar diretório se não existir
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Baixar imagem
    $imageContent = @file_get_contents($imageUrl);
    if ($imageContent) {
        $fileName = 'product_' . $productId . '_' . time() . '.png';
        $filePath = $uploadDir . $fileName;
        
        if (file_put_contents($filePath, $imageContent)) {
            $imagePath = '/public/uploads/products/' . $fileName;
            $product->addImage($productId, $imagePath, 1); // 1 = imagem principal
            echo "✅ Imagem baixada e salva: {$imagePath}\n";
        } else {
            echo "⚠️ Erro ao salvar imagem\n";
        }
    } else {
        echo "⚠️ Erro ao baixar imagem\n";
    }
    
    // Criar tipo de variação (Tamanho)
    $variationType = new VariationType();
    $typeId = $variationType->create($productId, 'Tamanho');
    echo "✅ Tipo de variação criado: Tamanho\n";
    
    // Adicionar opções de tamanho
    $tamanhos = ['Tamanho 1', 'Tamanho 3'];
    foreach ($tamanhos as $tamanho) {
        // Gerar SKU automaticamente
        $sku = 'PROD-' . $productId . '-' . strtoupper(str_replace(' ', '-', $tamanho));
        
        $optionId = $variationType->addOption(
            $typeId,
            $tamanho,
            $sku,
            0,
            0
        );
        echo "✅ Opção adicionada: {$tamanho} (SKU: {$sku})\n";
    }
    
    echo "\n✅ Produto 'Body Estampado' cadastrado com sucesso!\n";
    echo "ID do Produto: {$productId}\n";
    echo "URL: http://localhost:8000/produto/body-estampado\n";
} else {
    echo "❌ Erro ao criar produto\n";
}
?>
