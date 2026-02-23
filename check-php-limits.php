<?php
echo "<h2>Configurações PHP de Upload</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Configuração</th><th>Valor</th></tr>";
echo "<tr><td>upload_max_filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td>post_max_size</td><td>" . ini_get('post_max_size') . "</td></tr>";
echo "<tr><td>max_file_uploads</td><td>" . ini_get('max_file_uploads') . "</td></tr>";
echo "<tr><td>memory_limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
echo "<tr><td>max_execution_time</td><td>" . ini_get('max_execution_time') . "</td></tr>";
echo "</table>";

echo "<h3>Teste de $_FILES</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
} else {
    echo "<form method='POST' enctype='multipart/form-data'>";
    echo "<input type='file' name='test[]' multiple><br><br>";
    echo "<button type='submit'>Testar</button>";
    echo "</form>";
}
?>
