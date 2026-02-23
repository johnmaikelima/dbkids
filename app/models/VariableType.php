<?php

class VariableType {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obter todos os tipos de variáveis
     */
    public function all() {
        $stmt = $this->db->query("SELECT * FROM product_variable_types ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter tipo de variável por ID com seus valores
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM product_variable_types WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter valores de um tipo de variável
     */
    public function getValues($typeId) {
        $stmt = $this->db->prepare("
            SELECT * FROM product_variable_values 
            WHERE variable_type_id = ? 
            ORDER BY value
        ");
        $stmt->execute([$typeId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Criar tipo de variável
     */
    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO product_variable_types (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    /**
     * Adicionar valor a um tipo de variável
     */
    public function addValue($typeId, $value) {
        $stmt = $this->db->prepare("
            INSERT INTO product_variable_values (variable_type_id, value)
            VALUES (?, ?)
        ");
        return $stmt->execute([$typeId, $value]);
    }

    /**
     * Deletar tipo de variável
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM product_variable_types WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Deletar valor de variável
     */
    public function deleteValue($valueId) {
        $stmt = $this->db->prepare("DELETE FROM product_variable_values WHERE id = ?");
        return $stmt->execute([$valueId]);
    }
}
?>
