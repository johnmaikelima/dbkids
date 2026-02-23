<?php

namespace App\Controllers;

class AttributeController {
    
    public function index() {
        if (!isAdmin()) {
            redirect('/');
        }

        $attribute = new \ProductAttribute();
        $attributes = $attribute->all();

        view('admin/attributes/index', [
            'attributes' => $attributes
        ]);
    }

    public function create() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/atributos');
        }

        $name = $_POST['name'] ?? '';

        if (empty($name)) {
            $_SESSION['error'] = 'Nome do atributo é obrigatório';
            redirect('/admin/atributos');
            return;
        }

        try {
            $attribute = new \ProductAttribute();
            $attribute->create($name);
            
            $_SESSION['success'] = 'Atributo criado com sucesso!';
            redirect('/admin/atributos');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao criar atributo: ' . $e->getMessage();
            redirect('/admin/atributos');
        }
    }

    public function addValue() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/atributos');
        }

        $attributeId = $_POST['attribute_id'] ?? '';
        $value = $_POST['value'] ?? '';

        if (empty($attributeId) || empty($value)) {
            $_SESSION['error'] = 'Atributo e valor são obrigatórios';
            redirect('/admin/atributos');
            return;
        }

        try {
            $attribute = new \ProductAttribute();
            $attribute->addValue($attributeId, $value);
            
            $_SESSION['success'] = 'Valor adicionado com sucesso!';
            redirect('/admin/atributos');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao adicionar valor: ' . $e->getMessage();
            redirect('/admin/atributos');
        }
    }

    public function deleteValue() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/atributos');
        }

        $valueId = $_POST['value_id'] ?? '';

        if (empty($valueId)) {
            $_SESSION['error'] = 'Valor não encontrado';
            redirect('/admin/atributos');
            return;
        }

        try {
            $attribute = new \ProductAttribute();
            $attribute->deleteValue($valueId);
            
            $_SESSION['success'] = 'Valor deletado com sucesso!';
            redirect('/admin/atributos');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar valor: ' . $e->getMessage();
            redirect('/admin/atributos');
        }
    }

    public function delete() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/atributos');
        }

        $attributeId = $_POST['attribute_id'] ?? '';

        if (empty($attributeId)) {
            $_SESSION['error'] = 'Atributo não encontrado';
            redirect('/admin/atributos');
            return;
        }

        try {
            $attribute = new \ProductAttribute();
            $attribute->delete($attributeId);
            
            $_SESSION['success'] = 'Atributo deletado com sucesso!';
            redirect('/admin/atributos');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar atributo: ' . $e->getMessage();
            redirect('/admin/atributos');
        }
    }
}
?>
