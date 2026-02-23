<?php
define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

echo "=== Teste de Permissões do Banco ===\n\n";

// 1. Verificar arquivo
echo "1. Arquivo do banco:\n";
if (file_exists(DB_PATH)) {
    echo "   ✓ Arquivo existe\n";
    echo "   Tamanho: " . filesize(DB_PATH) . " bytes\n";
    echo "   Legível: " . (is_readable(DB_PATH) ? "SIM" : "NÃO") . "\n";
    echo "   Gravável: " . (is_writable(DB_PATH) ? "SIM" : "NÃO") . "\n";
} else {
    echo "   ✗ Arquivo não existe\n";
}

echo "\n2. Conectar ao banco:\n";
try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✓ Conexão bem-sucedida\n";
    
    // Verificar modo
    $result = $db->query("PRAGMA query_only")->fetch();
    echo "   Modo read-only: " . ($result[0] ? "SIM" : "NÃO") . "\n";
    
    // Tentar fazer um UPDATE simples
    echo "\n3. Teste de UPDATE:\n";
    $stmt = $db->prepare("UPDATE products SET name = ? WHERE id = 1");
    $success = $stmt->execute(['TESTE DE PERMISSÃO - ' . date('H:i:s')]);
    
    if ($success) {
        echo "   ✓ UPDATE executado\n";
        
        // Verificar se foi atualizado
        $result = $db->query("SELECT name FROM products WHERE id = 1")->fetch();
        echo "   Nome atual: " . $result['name'] . "\n";
    } else {
        echo "   ✗ UPDATE falhou\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Erro: " . $e->getMessage() . "\n";
}

echo "\n";
?>
