<?php
require_once 'app/config/database.php';
require_once 'app/helpers/functions.php';

try {
    $db = getDB();
    
    // Criar tabela settings
    $sql = "CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        setting_key TEXT UNIQUE NOT NULL,
        setting_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $db->exec($sql);
    echo "✓ Tabela settings criada com sucesso!\n";
    
    // Inserir configurações padrão do Mercado Pago
    $stmt = $db->prepare("INSERT OR IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)");
    
    $settings = [
        ['MERCADO_PAGO_TOKEN', ''],
        ['MERCADO_PAGO_PUBLIC_KEY', ''],
        ['MERCADO_PAGO_WEBHOOK_SECRET', ''],
    ];
    
    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }
    
    echo "✓ Configurações padrão inseridas!\n";
    echo "\nAcesse /admin/configuracoes para configurar o Mercado Pago\n";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
