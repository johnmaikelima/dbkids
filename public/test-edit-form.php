<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Dados Recebidos:</h2>";
    echo "<h3>POST:</h3><pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h3>FILES:</h3><pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if (isset($_FILES['images'])) {
        echo "<h3>Processamento:</h3>";
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp) {
            if (!empty($tmp)) {
                echo "Arquivo {$key}: {$_FILES['images']['name'][$key]} - Tamanho: {$_FILES['images']['size'][$key]} bytes<br>";
            }
        }
    } else {
        echo "<h3 style='color:red'>ERRO: \$_FILES['images'] NÃO EXISTE!</h3>";
    }
    
    echo "<br><a href='test-edit-form.php'>Testar novamente</a>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste Formulário Edição</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Teste de Formulário de Edição</h1>
        
        <div class="card mt-4">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" value="Produto Teste">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Categoria</label>
                        <select class="form-control" name="category_id">
                            <option value="1">Categoria 1</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Preço</label>
                        <input type="number" class="form-control" name="price" value="100" step="0.01">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Adicionar Imagens</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg">TESTAR ENVIO</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
