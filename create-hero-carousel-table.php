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

// Criar tabela de hero carousel
$sql = "
CREATE TABLE IF NOT EXISTS hero_carousel (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    is_active INTEGER DEFAULT 1,
    auto_play INTEGER DEFAULT 1,
    interval INTEGER DEFAULT 5000,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
";

$sql2 = "
CREATE TABLE IF NOT EXISTS hero_carousel_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    carousel_id INTEGER NOT NULL,
    image_desktop TEXT,
    image_mobile TEXT,
    button_text TEXT,
    button_url TEXT,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (carousel_id) REFERENCES hero_carousel(id) ON DELETE CASCADE
)
";

try {
    $db->exec($sql);
    echo "✅ Tabela 'hero_carousel' criada com sucesso!\n";
    
    $db->exec($sql2);
    echo "✅ Tabela 'hero_carousel_images' criada com sucesso!\n";
    
    // Inserir carousel de exemplo
    $stmt = $db->prepare("INSERT INTO hero_carousel (title, is_active) VALUES (?, ?)");
    $stmt->execute(['Carousel Principal', 1]);
    
    echo "✅ Carousel de exemplo adicionado!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>
