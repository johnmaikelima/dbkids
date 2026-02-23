<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/functions.php';

// Carregar Models
$modelFiles = glob(APP_PATH . '/models/*.php');
foreach ($modelFiles as $file) {
    require_once $file;
}

try {
    $user = new User();
    $user->create([
        'name' => 'Admin',
        'email' => 'admin@dbkids.com',
        'password' => 'senha123',
        'role' => 'admin'
    ]);
    
    echo "✓ Usuário admin criado com sucesso!\n\n";
    echo "Acesse: http://localhost:8000/admin/login\n";
    echo "Email: admin@dbkids.com\n";
    echo "Senha: senha123\n";
} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
?>
