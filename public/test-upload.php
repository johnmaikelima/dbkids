<?php
echo "=== Teste de Upload ===<br><br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    echo "<h3>Resultado:</h3>";
    echo "Nome: " . $_FILES['test_image']['name'] . "<br>";
    echo "Tipo: " . $_FILES['test_image']['type'] . "<br>";
    echo "Tamanho: " . $_FILES['test_image']['size'] . " bytes<br>";
    echo "Erro: " . $_FILES['test_image']['error'] . "<br>";
    echo "Tmp: " . $_FILES['test_image']['tmp_name'] . "<br><br>";
    
    if ($_FILES['test_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/products/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = 'test_' . time() . '.png';
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['test_image']['tmp_name'], $filePath)) {
            echo "<strong style='color:green'>✓ SUCESSO! Arquivo salvo em: {$filePath}</strong><br>";
            echo "Tamanho: " . filesize($filePath) . " bytes<br>";
        } else {
            echo "<strong style='color:red'>✗ ERRO ao mover arquivo</strong><br>";
            echo "Pasta gravável: " . (is_writable($uploadDir) ? "SIM" : "NÃO") . "<br>";
        }
    } else {
        echo "<strong style='color:red'>✗ Erro no upload: " . $_FILES['test_image']['error'] . "</strong><br>";
    }
    echo "<br><a href='test-upload.php'>Testar novamente</a>";
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste de Upload</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        input, button { padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Teste de Upload de Imagem</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="test_image" accept="image/*" required><br>
        <button type="submit">TESTAR UPLOAD</button>
    </form>
</body>
</html>
<?php } ?>
