<?php

namespace App\Controllers;

class ContactController {
    
    public function index() {
        view('contact/index');
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/contato');
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'] ?? ''
        ];

        // Validar
        if (empty($data['name']) || empty($data['email']) || empty($data['subject']) || empty($data['message'])) {
            $_SESSION['error'] = 'Todos os campos são obrigatórios';
            redirect('/contato');
        }

        if (!isValidEmail($data['email'])) {
            $_SESSION['error'] = 'Email inválido';
            redirect('/contato');
        }

        try {
            $db = getDB();
            $stmt = $db->prepare("
                INSERT INTO contacts (name, email, subject, message)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['subject'],
                $data['message']
            ]);

            $_SESSION['success'] = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
            redirect('/contato');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao enviar mensagem. Tente novamente.';
            redirect('/contato');
        }
    }
}
