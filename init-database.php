<?php
/**
 * Script de inicialização do banco de dados
 * Execute este arquivo UMA VEZ após o deploy para criar o banco de dados
 */

define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/database/dbkids.db');

echo "=== Inicialização do Banco de Dados ===\n\n";

// 1. Criar pasta database se não existir
if (!file_exists(BASE_PATH . '/database')) {
    mkdir(BASE_PATH . '/database', 0777, true);
    echo "✓ Pasta database/ criada\n";
} else {
    echo "✓ Pasta database/ já existe\n";
}

// 2. Verificar se o banco já existe
if (file_exists(DB_PATH)) {
    echo "⚠ Banco de dados já existe em: " . DB_PATH . "\n";
    echo "Se quiser recriar, delete o arquivo primeiro.\n";
    exit;
}

// 3. Criar banco de dados
try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Banco de dados criado\n";
    
    // 4. Ler e executar o schema SQL
    $schemaFile = BASE_PATH . '/app/database/schema.sql';
    
    if (!file_exists($schemaFile)) {
        echo "✗ Arquivo schema.sql não encontrado em: " . $schemaFile . "\n";
        exit;
    }
    
    $sql = file_get_contents($schemaFile);
    $db->exec($sql);
    echo "✓ Tabelas criadas a partir do schema.sql\n";
    
    // 5. Criar tabela settings
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        setting_key TEXT UNIQUE NOT NULL,
        setting_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ Tabela settings criada\n";
    
    // 6. Criar usuário admin padrão
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Admin', 'admin@dbkids.com', $password, 1]);
    echo "✓ Usuário admin criado\n";
    echo "   Email: admin@dbkids.com\n";
    echo "   Senha: admin123\n";
    
    // 6. Definir permissões
    chmod(DB_PATH, 0666);
    echo "✓ Permissões configuradas\n";
    
    echo "\n=== Banco de dados inicializado com sucesso! ===\n";
    echo "Você pode acessar o painel admin em: /admin/login\n";
    
} catch (PDOException $e) {
    echo "✗ Erro ao criar banco de dados: " . $e->getMessage() . "\n";
    exit;
}
?>
