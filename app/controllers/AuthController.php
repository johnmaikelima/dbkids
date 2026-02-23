<?php

namespace App\Controllers;

class AuthController {
    
    public function loginForm() {
        if (isAuthenticated()) {
            redirect('/admin/dashboard');
        }
        view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email e senha são obrigatórios';
            redirect('/admin/login');
        }

        $user = new \User();
        $authenticated = $user->authenticate($email, $password);

        if (!$authenticated) {
            $_SESSION['error'] = 'Email ou senha inválidos';
            redirect('/admin/login');
        }

        // Salvar na sessão
        $_SESSION['user_id'] = $authenticated->id;
        $_SESSION['user_name'] = $authenticated->name;
        $_SESSION['user_email'] = $authenticated->email;
        $_SESSION['role'] = $authenticated->role;

        $_SESSION['success'] = 'Login realizado com sucesso!';
        redirect('/admin/dashboard');
    }

    public function logout() {
        session_destroy();
        redirect('/');
    }
}
