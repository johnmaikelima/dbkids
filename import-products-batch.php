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

// Lista de URLs dos produtos
$urls = [
    'https://www.outletdbkids.com.br/product-page/body-estampado-11',
    'https://www.outletdbkids.com.br/product-page/body-estampado-10',
    'https://www.outletdbkids.com.br/product-page/body-estampado-9',
    'https://www.outletdbkids.com.br/product-page/body-estampado-8',
    'https://www.outletdbkids.com.br/product-page/c%C3%B3pia-de-body-estampado-1',
    'https://www.outletdbkids.com.br/product-page/body-estampado-7',
    'https://www.outletdbkids.com.br/product-page/body-estampado-6',
    'https://www.outletdbkids.com.br/product-page/body-estampado-5',
    'https://www.outletdbkids.com.br/product-page/body-estampado-4',
    'https://www.outletdbkids.com.br/product-page/body-estampado-3',
    'https://www.outletdbkids.com.br/product-page/body-estampado-2',
    'https://www.outletdbkids.com.br/product-page/body-estampado-1',
    'https://www.outletdbkids.com.br/product-page/body-estampa-m%C3%A3e',
    'https://www.outletdbkids.com.br/product-page/body-estampado',
    'https://www.outletdbkids.com.br/product-page/body-bebe-menina-estampado-alakazoo',
];

echo "🚀 Iniciando importação de " . count($urls) . " produtos...\n";
echo str_repeat("=", 60) . "\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($urls as $index => $url) {
    echo "[$index/" . count($urls) . "] Processando: {$url}\n";
    
    try {
        // Buscar conteúdo da página
        $html = @file_get_contents($url);
        if (!$html) {
            echo "  ❌ Erro ao acessar URL\n\n";
            $errorCount++;
            continue;
        }
        
        // Extrair dados do JSON
        preg_match('/"name":"([^"]+)"/', $html, $nameMatch);
        preg_match('/"description":"([^"]+)"/', $html, $descMatch);
        preg_match('/"fullUrl":"([^"]+)"/', $html, $imageMatch);
        preg_match('"selections":\[\{"id":\d+,"value":"([^"]+)"', $html, $sizeMatch);
        
        $name = isset($nameMatch[1]) ? $nameMatch[1] : 'Produto Importado';
        $description = isset($descMatch[1]) ? html_entity_decode($descMatch[1]) : 'Produto importado do catálogo';
        $imageUrl = isset($imageMatch[1]) ? str_replace('\\/', '/', $imageMatch[1]) : null;
        
        // Criar produto
        $productData = [
            'name' => $name . ' ' . time(),
            'description' => $description,
            'category_id' => 1, // Bodies
            'price' => 0,
            'stock' => 0,
            'weight' => 0.3,
            'length' => null,
            'width' => null,
            'height' => null
        ];
        
        $product = new Product();
        $productId = $product->create($productData);
        
        if (!$productId) {
            echo "  ❌ Erro ao criar produto\n\n";
            $errorCount++;
            continue;
        }
        
        echo "  ✅ Produto criado (ID: {$productId})\n";
        
        // Baixar imagem se existir
        if ($imageUrl) {
            $uploadDir = PUBLIC_PATH . '/uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $imageContent = @file_get_contents($imageUrl);
            if ($imageContent) {
                $fileName = 'product_' . $productId . '_' . time() . '.png';
                $filePath = $uploadDir . $fileName;
                
                if (file_put_contents($filePath, $imageContent)) {
                    $imagePath = '/public/uploads/products/' . $fileName;
                    $product->addImage($productId, $imagePath, 1);
                    echo "  ✅ Imagem salva\n";
                }
            }
        }
        
        // Criar variação de tamanho
        $variationType = new VariationType();
        $typeId = $variationType->create($productId, 'Tamanho');
        
        // Adicionar tamanhos padrão
        $tamanhos = ['Tamanho 1', 'Tamanho 3'];
        foreach ($tamanhos as $tamanho) {
            $sku = 'PROD-' . $productId . '-' . strtoupper(str_replace(' ', '-', $tamanho));
            $variationType->addOption($typeId, $tamanho, $sku, 0, 0);
        }
        
        echo "  ✅ Variações criadas\n";
        echo "  ✅ Produto importado com sucesso!\n\n";
        
        $successCount++;
        
    } catch (Exception $e) {
        echo "  ❌ Erro: " . $e->getMessage() . "\n\n";
        $errorCount++;
    }
    
    // Pequeno delay para não sobrecarregar
    usleep(500000); // 0.5 segundo
}

echo str_repeat("=", 60) . "\n";
echo "📊 Resumo da Importação:\n";
echo "✅ Sucesso: {$successCount}\n";
echo "❌ Erros: {$errorCount}\n";
echo "📦 Total: " . count($urls) . "\n";
?>
