<?php
require_once 'app/config/database.php';
require_once 'app/helpers/functions.php';

try {
    $db = getDB();
    
    // Adicionar coluna payment_link à tabela orders
    $db->exec("ALTER TABLE orders ADD COLUMN payment_link TEXT");
    
    echo "✓ Coluna payment_link adicionada à tabela orders com sucesso!\n";
    
} catch (PDOException $e) {
    // Se a coluna já existir, ignora o erro
    if (strpos($e->getMessage(), 'duplicate column name') !== false) {
        echo "✓ Coluna payment_link já existe na tabela orders\n";
    } else {
        echo "Erro: " . $e->getMessage() . "\n";
    }
}
?>
