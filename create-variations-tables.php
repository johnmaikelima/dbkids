<?php
define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Criando tabelas de variáveis...\n\n";

    // Tabela de tipos de variáveis (Tamanho, Cor, etc.)
    $db->exec("
        CREATE TABLE IF NOT EXISTS product_variable_types (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Tabela 'product_variable_types' criada\n";

    // Tabela de valores de variáveis (P, M, G para tamanho; Vermelho, Azul para cor)
    $db->exec("
        CREATE TABLE IF NOT EXISTS product_variable_values (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            variable_type_id INTEGER NOT NULL,
            value VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (variable_type_id) REFERENCES product_variable_types(id) ON DELETE CASCADE,
            UNIQUE(variable_type_id, value)
        )
    ");
    echo "✓ Tabela 'product_variable_values' criada\n";

    // Tabela de variações do produto (combinações de variáveis + estoque)
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

    // Tabela de relacionamento entre variações e valores de variáveis
    $db->exec("
        CREATE TABLE IF NOT EXISTS product_variation_values (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            variation_id INTEGER NOT NULL,
            variable_type_id INTEGER NOT NULL,
            variable_value_id INTEGER NOT NULL,
            FOREIGN KEY (variation_id) REFERENCES product_variations(id) ON DELETE CASCADE,
            FOREIGN KEY (variable_type_id) REFERENCES product_variable_types(id) ON DELETE CASCADE,
            FOREIGN KEY (variable_value_id) REFERENCES product_variable_values(id) ON DELETE CASCADE,
            UNIQUE(variation_id, variable_type_id)
        )
    ");
    echo "✓ Tabela 'product_variation_values' criada\n";

    echo "\n✓ Todas as tabelas foram criadas com sucesso!\n";

} catch (Exception $e) {
    echo "✗ Erro: " . $e->getMessage() . "\n";
}
?>
