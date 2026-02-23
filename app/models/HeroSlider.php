<?php

class HeroSlider {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter todos os sliders ativos
     */
    public function getActive() {
        $stmt = $this->db->query("
            SELECT * FROM hero_sliders 
            WHERE is_active = 1 
            ORDER BY sort_order ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter todos os sliders
     */
    public function all() {
        $stmt = $this->db->query("
            SELECT * FROM hero_sliders 
            ORDER BY sort_order ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter slider por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM hero_sliders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Criar novo slider
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO hero_sliders (title, description, image_desktop, image_mobile, button_text, button_url, is_active, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['image_desktop'] ?? '',
            $data['image_mobile'] ?? '',
            $data['button_text'] ?? '',
            $data['button_url'] ?? '',
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0
        ]);
    }

    /**
     * Atualizar slider
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE hero_sliders 
            SET title = ?, description = ?, image_desktop = ?, image_mobile = ?, button_text = ?, button_url = ?, is_active = ?, sort_order = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['image_desktop'] ?? $data['image_desktop'] ?? '',
            $data['image_mobile'] ?? $data['image_mobile'] ?? '',
            $data['button_text'] ?? '',
            $data['button_url'] ?? '',
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0,
            $id
        ]);
    }

    /**
     * Deletar slider
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM hero_sliders WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
