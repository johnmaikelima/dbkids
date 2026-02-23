<?php

namespace App\Controllers;

class UserController {
    
    public function index() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $user = new \User();
        $users = $user->all();

        view('admin/users/index', ['users' => $users]);
    }

    public function create() {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        view('admin/users/create');
    }

    public function store() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/usuarios');
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? 'admin'
        ];

        // Validar
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            $_SESSION['error'] = 'Todos os campos são obrigatórios';
            redirect('/admin/usuarios/novo');
        }

        if (!isValidEmail($data['email'])) {
            $_SESSION['error'] = 'Email inválido';
            redirect('/admin/usuarios/novo');
        }

        try {
            $user = new \User();
            $user->create($data);
            $_SESSION['success'] = 'Usuário criado com sucesso!';
            redirect('/admin/usuarios');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/usuarios/novo');
        }
    }

    public function edit($id) {
        if (!isAdmin()) {
            redirect('/admin/login');
        }

        $user = new \User();
        $userData = $user->find($id);

        if (!$userData) {
            $_SESSION['error'] = 'Usuário não encontrado';
            redirect('/admin/usuarios');
        }

        view('admin/users/edit', ['user' => $userData]);
    }

    public function update() {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/usuarios');
        }

        $id = $_POST['id'] ?? '';
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'role' => $_POST['role'] ?? 'admin'
        ];

        if (empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = 'Nome e email são obrigatórios';
            redirect('/admin/usuarios/editar/' . $id);
        }

        try {
            $user = new \User();
            $user->update($id, $data);
            $_SESSION['success'] = 'Usuário atualizado com sucesso!';
            redirect('/admin/usuarios');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/usuarios/editar/' . $id);
        }
    }

    public function delete($id) {
        if (!isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/usuarios');
        }

        try {
            $user = new \User();
            $user->delete($id);
            $_SESSION['success'] = 'Usuário deletado com sucesso!';
            redirect('/admin/usuarios');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect('/admin/usuarios');
        }
    }
}
