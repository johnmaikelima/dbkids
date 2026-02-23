<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO('sqlite:' . DB_PATH);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    private function createTables() {
        $sql = file_get_contents(APP_PATH . '/database/schema.sql');
        $this->pdo->exec($sql);
        
        // Migração: Adicionar colunas faltantes à tabela hero_carousel_images
        try {
            $this->pdo->exec("ALTER TABLE hero_carousel_images ADD COLUMN type TEXT DEFAULT 'image'");
        } catch (PDOException $e) {
            // Coluna já existe, ignorar erro
        }
        
        try {
            $this->pdo->exec("ALTER TABLE hero_carousel_images ADD COLUMN product_id INTEGER");
        } catch (PDOException $e) {
            // Coluna já existe, ignorar erro
        }
        
        try {
            $this->pdo->exec("ALTER TABLE hero_carousel_images ADD COLUMN category_id INTEGER");
        } catch (PDOException $e) {
            // Coluna já existe, ignorar erro
        }
        
        try {
            $this->pdo->exec("ALTER TABLE hero_carousel_images ADD COLUMN promotion_text TEXT");
        } catch (PDOException $e) {
            // Coluna já existe, ignorar erro
        }
        
        // Migração: Criar tabela de junção product_categories
        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS product_categories (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER NOT NULL,
                    category_id INTEGER NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                    UNIQUE(product_id, category_id)
                )
            ");
        } catch (PDOException $e) {
            // Tabela já existe, ignorar erro
        }
    }
}

// Instanciar banco de dados
$db = Database::getInstance();
