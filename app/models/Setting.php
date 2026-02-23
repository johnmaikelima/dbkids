<?php

class Setting {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter configuração por chave
     */
    public function get($key, $default = null) {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->value : $default;
    }

    /**
     * Obter todas as configurações
     */
    public function all() {
        $stmt = $this->db->query("SELECT * FROM settings");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Atualizar configuração
     */
    public function set($key, $value) {
        $stmt = $this->db->prepare("
            INSERT INTO settings (key, value, updated_at)
            VALUES (?, ?, CURRENT_TIMESTAMP)
            ON CONFLICT(key) DO UPDATE SET value = ?, updated_at = CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$key, $value, $value]);
    }

    /**
     * Obter todas as configurações como array associativo
     */
    public function getAll() {
        $settings = [];
        $all = $this->all();
        foreach ($all as $setting) {
            $settings[$setting->key] = $setting->value;
        }
        return $settings;
    }
}
?>
