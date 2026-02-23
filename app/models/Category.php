<?php

class Category {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter todas as categorias
     */
    public function all() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter categoria por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter categoria por slug
     */
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Criar nova categoria
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, slug, description)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $data['name'],
            generateSlug($data['name']),
            $data['description'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Atualizar categoria
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE categories 
            SET name = ?, slug = ?, description = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            generateSlug($data['name']),
            $data['description'] ?? null,
            $id
        ]);
    }

    /**
     * Deletar categoria
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Contar total de categorias
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM categories");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
