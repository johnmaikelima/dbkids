<?php

class ProductAttribute {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obter todos os atributos
     */
    public function all() {
        $stmt = $this->db->query("SELECT * FROM attributes ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter atributo por ID com seus valores
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM attributes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter valores de um atributo
     */
    public function getValues($attributeId) {
        $stmt = $this->db->prepare("
            SELECT * FROM attribute_values 
            WHERE attribute_id = ? 
            ORDER BY value
        ");
        $stmt->execute([$attributeId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Criar atributo
     */
    public function create($name) {
        $slug = strtolower(str_replace(' ', '-', $name));
        $stmt = $this->db->prepare("INSERT INTO attributes (name, slug) VALUES (?, ?)");
        return $stmt->execute([$name, $slug]);
    }

    /**
     * Adicionar valor a um atributo
     */
    public function addValue($attributeId, $value) {
        $slug = strtolower(str_replace(' ', '-', $value));
        $stmt = $this->db->prepare("
            INSERT INTO attribute_values (attribute_id, value, slug)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$attributeId, $value, $slug]);
    }

    /**
     * Deletar atributo
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM attributes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Deletar valor de atributo
     */
    public function deleteValue($valueId) {
        $stmt = $this->db->prepare("DELETE FROM attribute_values WHERE id = ?");
        return $stmt->execute([$valueId]);
    }
}
?>
