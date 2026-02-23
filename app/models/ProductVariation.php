<?php

class ProductVariation {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obter todas as variações de um produto
     */
    public function getByProduct($productId) {
        $stmt = $this->db->prepare("
            SELECT pv.*, GROUP_CONCAT(pvv.variable_type_id || ':' || pvv.variable_value_id) as variables
            FROM product_variations pv
            LEFT JOIN product_variation_values pvv ON pv.id = pvv.variation_id
            WHERE pv.product_id = ?
            GROUP BY pv.id
            ORDER BY pv.created_at
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Criar variação
     */
    public function create($productId, $sku, $price, $stock, $variables) {
        try {
            $this->db->beginTransaction();

            // Inserir variação
            $stmt = $this->db->prepare("
                INSERT INTO product_variations (product_id, sku, price, stock)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$productId, $sku, $price, $stock]);
            $variationId = $this->db->lastInsertId();

            // Inserir valores das variáveis
            foreach ($variables as $typeId => $valueId) {
                $stmt = $this->db->prepare("
                    INSERT INTO product_variation_values (variation_id, variable_type_id, variable_value_id)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$variationId, $typeId, $valueId]);
            }

            $this->db->commit();
            return $variationId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Atualizar variação
     */
    public function update($variationId, $sku, $price, $stock) {
        $stmt = $this->db->prepare("
            UPDATE product_variations
            SET sku = ?, price = ?, stock = ?
            WHERE id = ?
        ");
        return $stmt->execute([$sku, $price, $stock, $variationId]);
    }

    /**
     * Deletar variação
     */
    public function delete($variationId) {
        $stmt = $this->db->prepare("DELETE FROM product_variations WHERE id = ?");
        return $stmt->execute([$variationId]);
    }

    /**
     * Obter variação por ID
     */
    public function find($variationId) {
        $stmt = $this->db->prepare("SELECT * FROM product_variations WHERE id = ?");
        $stmt->execute([$variationId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
?>
