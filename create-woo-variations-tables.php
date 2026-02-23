<?php
define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Criando tabelas de atributos e variações tipo WooCommerce...\n\n";

    // Tabela de atributos globais (Tamanho, Cor, Material, etc.)
    $db->exec("
        CREATE TABLE IF NOT EXISTS attributes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL UNIQUE,
            slug VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Tabela 'attributes' criada\n";

    // Tabela de valores dos atributos (P, M, G para Tamanho; Preto, Vermelho para Cor)
    $db->exec("
        CREATE TABLE IF NOT EXISTS attribute_values (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            attribute_id INTEGER NOT NULL,
            value VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE,
            UNIQUE(attribute_id, slug)
        )
    ");
    echo "✓ Tabela 'attribute_values' criada\n";

    // Tabela de atributos do produto (quais atributos cada produto usa)
    $db->exec("
        CREATE TABLE IF NOT EXISTS product_attributes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            attribute_id INTEGER NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE,
            UNIQUE(product_id, attribute_id)
        )
    ");
    echo "✓ Tabela 'product_attributes' criada\n";

    // Tabela de variações (combinações de atributos)
    $db->exec("
        CREATE TABLE IF NOT EXISTS product_variations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            sku VARCHAR(100),
            price DECIMAL(10, 2),
            stock INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Tabela 'product_variations' criada\n";

    // Tabela de relacionamento entre variações e valores de atributos
    $db->exec("
        CREATE TABLE IF NOT EXISTS variation_attributes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            variation_id INTEGER NOT NULL,
            attribute_id INTEGER NOT NULL,
            attribute_value_id INTEGER NOT NULL,
            FOREIGN KEY (variation_id) REFERENCES product_variations(id) ON DELETE CASCADE,
            FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE,
            FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE,
            UNIQUE(variation_id, attribute_id)
        )
    ");
    echo "✓ Tabela 'variation_attributes' criada\n";

    echo "\n✓ Todas as tabelas foram criadas com sucesso!\n";

} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
?>
