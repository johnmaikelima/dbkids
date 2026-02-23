<?php
/**
 * Router para o PHP Built-in Server
 * Este arquivo é usado pelo PHP Built-in Server para servir arquivos estáticos
 * 
 * Uso: php -S localhost:8000 router.php
 */

// Aumentar limites de upload
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_file_uploads', '20');
ini_set('memory_limit', '256M');

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Lista de diretórios e extensões estáticas
$staticDirs = ['/public/', '/uploads/'];
$staticExtensions = ['jpg', 'jpeg', 'png', 'gif', 'css', 'js', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot', 'avif', 'webp'];

// Verificar se é um diretório estático
foreach ($staticDirs as $dir) {
    if (strpos($requestUri, $dir) === 0) {
        $filePath = __DIR__ . $requestUri;
        if (file_exists($filePath) && is_file($filePath)) {
            return false; // Deixar o servidor servir o arquivo
        }
    }
}

// Verificar se é um arquivo com extensão estática
$extension = pathinfo($requestUri, PATHINFO_EXTENSION);
if (in_array(strtolower($extension), $staticExtensions)) {
    $filePath = __DIR__ . $requestUri;
    if (file_exists($filePath) && is_file($filePath)) {
        return false; // Deixar o servidor servir o arquivo
    }
}

// Caso contrário, rotear para o index.php
require_once __DIR__ . '/index.php';
