<?php

class SimpleVariation {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obter todas as variações simples de um produto
     */
    public function getByProduct($productId) {
        $stmt = $this->db->prepare("
            SELECT * FROM simple_variations
            WHERE product_id = ?
            ORDER BY created_at
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Criar variação simples
     */
    public function create($productId, $name, $sku, $price, $stock) {
        $stmt = $this->db->prepare("
            INSERT INTO simple_variations (product_id, name, sku, price, stock)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$productId, $name, $sku, $price, $stock]);
    }

    /**
     * Atualizar variação simples
     */
    public function update($variationId, $name, $sku, $price, $stock) {
        $stmt = $this->db->prepare("
            UPDATE simple_variations
            SET name = ?, sku = ?, price = ?, stock = ?
            WHERE id = ?
        ");
        return $stmt->execute([$name, $sku, $price, $stock, $variationId]);
    }

    /**
     * Deletar variação simples
     */
    public function delete($variationId) {
        $stmt = $this->db->prepare("DELETE FROM simple_variations WHERE id = ?");
        return $stmt->execute([$variationId]);
    }

    /**
     * Obter variação por ID
     */
    public function find($variationId) {
        $stmt = $this->db->prepare("SELECT * FROM simple_variations WHERE id = ?");
        $stmt->execute([$variationId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>
