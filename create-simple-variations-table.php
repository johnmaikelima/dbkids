<?php
define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Criando tabela de variações simples...\n\n";

    // Tabela de variações simples
    $db->exec("
        CREATE TABLE IF NOT EXISTS simple_variations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            name VARCHAR(255) NOT NULL,
            sku VARCHAR(100),
            price DECIMAL(10, 2),
            stock INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Tabela 'simple_variations' criada\n";

    echo "\n✓ Tabela foi criada com sucesso!\n";
    echo "\nAgora você pode deletar as antigas tabelas de variações se quiser:\n";
    echo "- product_variable_types\n";
    echo "- product_variable_values\n";
    echo "- product_variations\n";
    echo "- product_variation_values\n";

} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
?>
