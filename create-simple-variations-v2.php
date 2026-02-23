<?php
define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Criando tabelas de variações simples v2...\n\n";

    // Tabela de variações (Tamanho, Cor, Material, etc.)
    $db->exec("
        CREATE TABLE IF NOT EXISTS product_variation_types (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            name VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Tabela 'product_variation_types' criada\n";

    // Tabela de valores das variações (P, M, G para Tamanho)
    $db->exec("
        CREATE TABLE IF NOT EXISTS product_variation_options (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            variation_type_id INTEGER NOT NULL,
            value VARCHAR(100) NOT NULL,
            sku VARCHAR(100),
            price DECIMAL(10, 2),
            stock INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (variation_type_id) REFERENCES product_variation_types(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Tabela 'product_variation_options' criada\n";

    echo "\n✓ Tabelas criadas com sucesso!\n";

} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
?>
