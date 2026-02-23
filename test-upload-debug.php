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

echo "=== Debug Upload de Imagens ===\n\n";

// Simular $_FILES como se viesse do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    echo "Arquivo recebido:\n";
    echo "  Nome: " . $_FILES['test_image']['name'] . "\n";
    echo "  Tipo: " . $_FILES['test_image']['type'] . "\n";
    echo "  Tamanho: " . $_FILES['test_image']['size'] . " bytes\n";
    echo "  Erro: " . $_FILES['test_image']['error'] . "\n";
    echo "  Tmp: " . $_FILES['test_image']['tmp_name'] . "\n\n";
    
    if ($_FILES['test_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = BASE_PATH . '/public/uploads/products/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
            echo "✓ Pasta criada\n";
        }
        
        $fileName = 'test_upload_' . time() . '.png';
        $filePath = $uploadDir . $fileName;
        
        echo "Tentando mover para: {$filePath}\n";
        
        if (move_uploaded_file($_FILES['test_image']['tmp_name'], $filePath)) {
            echo "✓ Arquivo movido com sucesso!\n";
            echo "  Tamanho final: " . filesize($filePath) . " bytes\n";
        } else {
            echo "✗ Falha ao mover arquivo\n";
            echo "  Permissões da pasta: " . (is_writable($uploadDir) ? "OK" : "SEM PERMISSÃO") . "\n";
        }
    } else {
        echo "✗ Erro no upload: " . $_FILES['test_image']['error'] . "\n";
    }
} else {
    // Mostrar formulário
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Teste de Upload</title>
    </head>
    <body>
        <h1>Teste de Upload de Imagem</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="test_image" accept="image/*" required><br><br>
            <button type="submit">TESTAR UPLOAD</button>
        </form>
    </body>
    </html>
    <?php
}
?>
