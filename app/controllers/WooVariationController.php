<?php

namespace App\Controllers;

class WooVariationController {
    
    public function addAttribute() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $attributeId = $_POST['attribute_id'] ?? '';

        if (empty($productId) || empty($attributeId)) {
            $_SESSION['error'] = 'Produto e atributo são obrigatórios';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variation = new \WooVariation();
            $variation->addProductAttribute($productId, $attributeId);
            
            $_SESSION['success'] = 'Atributo adicionado ao produto!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao adicionar atributo: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function removeAttribute() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $attributeId = $_POST['attribute_id'] ?? '';

        if (empty($productId) || empty($attributeId)) {
            $_SESSION['error'] = 'Produto e atributo são obrigatórios';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variation = new \WooVariation();
            $variation->removeProductAttribute($productId, $attributeId);
            
            $_SESSION['success'] = 'Atributo removido do produto!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao remover atributo: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function create() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $sku = $_POST['sku'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $attributes = [];

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'attr_') === 0 && !empty($value)) {
                $attributeId = str_replace('attr_', '', $key);
                $attributes[$attributeId] = $value;
            }
        }

        if (empty($productId) || empty($attributes)) {
            $_SESSION['error'] = 'Produto e atributos são obrigatórios';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variation = new \WooVariation();
            $variation->create($productId, $sku, $price, $stock, $attributes);
            
            $_SESSION['success'] = 'Variação criada com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao criar variação: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function update() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $variationId = $_POST['variation_id'] ?? '';
        $productId = $_POST['product_id'] ?? '';
        $sku = $_POST['sku'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);

        if (empty($variationId)) {
            $_SESSION['error'] = 'Variação não encontrada';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variation = new \WooVariation();
            $variation->update($variationId, $sku, $price, $stock);
            
            $_SESSION['success'] = 'Variação atualizada com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar variação: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function delete() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $variationId = $_POST['variation_id'] ?? '';
        $productId = $_POST['product_id'] ?? '';

        if (empty($variationId)) {
            $_SESSION['error'] = 'Variação não encontrada';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variation = new \WooVariation();
            $variation->delete($variationId);
            
            $_SESSION['success'] = 'Variação deletada com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar variação: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }
}
?>
