<?php

namespace App\Controllers;

class VariationController {
    
    public function create() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $sku = $_POST['sku'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $variables = [];

        // Coletar variáveis selecionadas
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'variable_') === 0 && !empty($value)) {
                $typeId = str_replace('variable_', '', $key);
                $variables[$typeId] = $value;
            }
        }

        if (empty($productId) || empty($variables)) {
            $_SESSION['error'] = 'Produto e variáveis são obrigatórios';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variation = new \ProductVariation();
            $variation->create($productId, $sku, $price, $stock, $variables);
            
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
            $variation = new \ProductVariation();
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
            $variation = new \ProductVariation();
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
