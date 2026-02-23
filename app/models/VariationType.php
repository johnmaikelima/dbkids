<?php

class VariationType {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obter tipos de variações de um produto
     */
    public function getByProduct($productId) {
        $stmt = $this->db->prepare("
            SELECT * FROM product_variation_types
            WHERE product_id = ?
            ORDER BY name
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter opções de uma variação
     */
    public function getOptions($variationTypeId) {
        $stmt = $this->db->prepare("
            SELECT * FROM product_variation_options
            WHERE variation_type_id = ?
            ORDER BY value
        ");
        $stmt->execute([$variationTypeId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Criar tipo de variação
     */
    public function create($productId, $name) {
        $stmt = $this->db->prepare("
            INSERT INTO product_variation_types (product_id, name)
            VALUES (?, ?)
        ");
        return $stmt->execute([$productId, $name]);
    }

    /**
     * Adicionar opção a uma variação
     */
    public function addOption($variationTypeId, $value, $sku, $price, $stock) {
        $stmt = $this->db->prepare("
            INSERT INTO product_variation_options (variation_type_id, value, sku, price, stock)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$variationTypeId, $value, $sku, $price, $stock]);
    }

    /**
     * Atualizar opção
     */
    public function updateOption($optionId, $value, $sku, $price, $stock) {
        $stmt = $this->db->prepare("
            UPDATE product_variation_options
            SET value = ?, sku = ?, price = ?, stock = ?
            WHERE id = ?
        ");
        return $stmt->execute([$value, $sku, $price, $stock, $optionId]);
    }

    /**
     * Deletar opção
     */
    public function deleteOption($optionId) {
        $stmt = $this->db->prepare("DELETE FROM product_variation_options WHERE id = ?");
        return $stmt->execute([$optionId]);
    }

    /**
     * Deletar tipo de variação
     */
    public function delete($variationTypeId) {
        $stmt = $this->db->prepare("DELETE FROM product_variation_types WHERE id = ?");
        return $stmt->execute([$variationTypeId]);
    }

    /**
     * Obter tipo por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM product_variation_types WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>
