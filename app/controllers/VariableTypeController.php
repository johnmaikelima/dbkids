<?php

namespace App\Controllers;

class VariableTypeController {
    
    public function index() {
        if (!isAdmin()) {
            redirect('/');
        }

        $variableType = new \VariableType();
        $types = $variableType->all();

        view('admin/variable-types/index', [
            'types' => $types
        ]);
    }

    public function create() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/tipos-variaveis');
        }

        $name = $_POST['name'] ?? '';
        $productId = $_POST['product_id'] ?? '';

        if (empty($name)) {
            $_SESSION['error'] = 'Nome do tipo de variável é obrigatório';
            if (!empty($productId)) {
                redirect('/admin/produtos/editar/' . $productId);
            } else {
                redirect('/admin/tipos-variaveis');
            }
            return;
        }

        try {
            $variableType = new \VariableType();
            $variableType->create($name);
            
            $_SESSION['success'] = 'Tipo de variável criado com sucesso!';
            if (!empty($productId)) {
                redirect('/admin/produtos/editar/' . $productId);
            } else {
                redirect('/admin/tipos-variaveis');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao criar tipo de variável: ' . $e->getMessage();
            if (!empty($productId)) {
                redirect('/admin/produtos/editar/' . $productId);
            } else {
                redirect('/admin/tipos-variaveis');
            }
        }
    }

    public function addValue() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/tipos-variaveis');
        }

        $typeId = $_POST['type_id'] ?? '';
        $value = $_POST['value'] ?? '';
        $productId = $_POST['product_id'] ?? '';

        if (empty($typeId) || empty($value)) {
            $_SESSION['error'] = 'Tipo e valor são obrigatórios';
            if (!empty($productId)) {
                redirect('/admin/produtos/editar/' . $productId);
            } else {
                redirect('/admin/tipos-variaveis');
            }
            return;
        }

        try {
            $variableType = new \VariableType();
            $variableType->addValue($typeId, $value);
            
            $_SESSION['success'] = 'Valor adicionado com sucesso!';
            if (!empty($productId)) {
                redirect('/admin/produtos/editar/' . $productId);
            } else {
                redirect('/admin/tipos-variaveis');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao adicionar valor: ' . $e->getMessage();
            if (!empty($productId)) {
                redirect('/admin/produtos/editar/' . $productId);
            } else {
                redirect('/admin/tipos-variaveis');
            }
        }
    }

    public function deleteValue() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/tipos-variaveis');
        }

        $valueId = $_POST['value_id'] ?? '';

        if (empty($valueId)) {
            $_SESSION['error'] = 'Valor não encontrado';
            redirect('/admin/tipos-variaveis');
            return;
        }

        try {
            $variableType = new \VariableType();
            $variableType->deleteValue($valueId);
            
            $_SESSION['success'] = 'Valor deletado com sucesso!';
            redirect('/admin/tipos-variaveis');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar valor: ' . $e->getMessage();
            redirect('/admin/tipos-variaveis');
        }
    }

    public function delete() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/tipos-variaveis');
        }

        $typeId = $_POST['type_id'] ?? '';

        if (empty($typeId)) {
            $_SESSION['error'] = 'Tipo não encontrado';
            redirect('/admin/tipos-variaveis');
            return;
        }

        try {
            $variableType = new \VariableType();
            $variableType->delete($typeId);
            
            $_SESSION['success'] = 'Tipo de variável deletado com sucesso!';
            redirect('/admin/tipos-variaveis');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar tipo: ' . $e->getMessage();
            redirect('/admin/tipos-variaveis');
        }
    }
}
?>
