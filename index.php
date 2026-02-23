<?php
session_start();

// Configurações
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

// Autoload do Composer
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

// Autoload
require_once APP_PATH . '/helpers/Env.php';
require_once APP_PATH . '/helpers/Mail.php';
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/config/router.php';
require_once APP_PATH . '/helpers/functions.php';

// Carregar Models
$modelFiles = glob(APP_PATH . '/models/*.php');
foreach ($modelFiles as $file) {
    require_once $file;
}

// Processar requisição
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Detectar ambiente automaticamente
$basePath = '';
if (strpos($_SERVER['REQUEST_URI'], '/DbKids') === 0) {
    // Ambiente local (Laragon)
    $basePath = '/DbKids';
    $requestUri = substr($requestUri, strlen($basePath));
}

// Se for raiz, redirecionar para /
if (empty($requestUri) || $requestUri === '/') {
    $requestUri = '/';
}

// Inicializar aplicação
$router = new Router();
$router->dispatch($requestUri);
