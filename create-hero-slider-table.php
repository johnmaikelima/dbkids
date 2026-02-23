<?php
session_start();

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

require_once APP_PATH . '/helpers/Env.php';
require_once APP_PATH . '/helpers/functions.php';
require_once APP_PATH . '/config/database.php';

$db = getDB();

// Criar tabela de hero sliders
$sql = "
CREATE TABLE IF NOT EXISTS hero_sliders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    image_desktop TEXT,
    image_mobile TEXT,
    button_text TEXT,
    button_url TEXT,
    is_active INTEGER DEFAULT 1,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
";

try {
    $db->exec($sql);
    echo "✅ Tabela 'hero_sliders' criada com sucesso!\n";
    
    // Inserir um slider de exemplo
    $stmt = $db->prepare("
        INSERT INTO hero_sliders (title, description, button_text, button_url, sort_order)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        'Bem-vindo à DbKids',
        'Os melhores produtos infantis para seu filho',
        'Buscar Produtos',
        '/',
        1
    ]);
    
    echo "✅ Slider de exemplo adicionado!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>
