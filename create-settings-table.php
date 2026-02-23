<?php
session_start();

// Configurações
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

// Autoload
require_once APP_PATH . '/helpers/Env.php';
require_once APP_PATH . '/helpers/functions.php';
require_once APP_PATH . '/config/database.php';

$db = getDB();

// Criar tabela de configurações
$sql = "
CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key TEXT UNIQUE NOT NULL,
    value TEXT,
    type TEXT DEFAULT 'string',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
";

try {
    $db->exec($sql);
    echo "✅ Tabela 'settings' criada com sucesso!\n";
    
    // Inserir configurações padrão
    $defaults = [
        ['store_name', 'DbKids', 'string'],
        ['store_logo', '/public/images/logo.png', 'string'],
        ['store_description', 'Loja de roupas infantis', 'string'],
        ['store_email', 'contato@dbkids.com', 'string'],
        ['store_phone', '(11) 98928-3757', 'string'],
        ['store_address', 'São Paulo, SP', 'string'],
        ['theme', 'colorful', 'string'],
        ['primary_color', '#FF6B9D', 'string'],
        ['secondary_color', '#FFC75F', 'string'],
        ['accent_color', '#845EC2', 'string'],
    ];
    
    $stmt = $db->prepare("INSERT OR IGNORE INTO settings (key, value, type) VALUES (?, ?, ?)");
    
    foreach ($defaults as $config) {
        $stmt->execute($config);
        echo "✅ Configuração '{$config[0]}' adicionada\n";
    }
    
    echo "\n✅ Tabela de configurações criada e populada com sucesso!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>
