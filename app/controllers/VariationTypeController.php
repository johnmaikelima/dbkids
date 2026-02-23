<?php

namespace App\Controllers;

class VariationTypeController {
    
    public function create() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $name = $_POST['name'] ?? '';

        if (empty($productId) || empty($name)) {
            $_SESSION['error'] = 'Produto e nome da variação são obrigatórios';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variationType = new \VariationType();
            $variationType->create($productId, $name);
            
            $_SESSION['success'] = 'Tipo de variação criado com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao criar variação: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function addOption() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $variationTypeId = $_POST['variation_type_id'] ?? '';
        $value = $_POST['value'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);

        if (empty($variationTypeId) || empty($value)) {
            $_SESSION['error'] = 'Variação e valor são obrigatórios';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            // Gerar SKU automaticamente: PROD-{ID_PRODUTO}-{VALOR_SANITIZADO}
            $valueSanitized = strtoupper(str_replace(' ', '', $value));
            $sku = 'PROD-' . $productId . '-' . $valueSanitized;

            $variationType = new \VariationType();
            $variationType->addOption($variationTypeId, $value, $sku, $price, $stock);
            
            $_SESSION['success'] = 'Opção adicionada com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao adicionar opção: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function deleteOption() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $optionId = $_POST['option_id'] ?? '';

        if (empty($optionId)) {
            $_SESSION['error'] = 'Opção não encontrada';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variationType = new \VariationType();
            $variationType->deleteOption($optionId);
            
            $_SESSION['success'] = 'Opção deletada com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar opção: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }

    public function delete() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/produtos');
        }

        $productId = $_POST['product_id'] ?? '';
        $variationTypeId = $_POST['variation_type_id'] ?? '';

        if (empty($variationTypeId)) {
            $_SESSION['error'] = 'Variação não encontrada';
            redirect('/admin/produtos/editar/' . $productId);
            return;
        }

        try {
            $variationType = new \VariationType();
            $variationType->delete($variationTypeId);
            
            $_SESSION['success'] = 'Variação deletada com sucesso!';
            redirect('/admin/produtos/editar/' . $productId);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar variação: ' . $e->getMessage();
            redirect('/admin/produtos/editar/' . $productId);
        }
    }
}
?>
