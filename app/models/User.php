<?php

class User {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter todos os usuários
     */
    public function all() {
        $stmt = $this->db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter usuário por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter usuário por email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Criar novo usuário
     */
    public function create($data) {
        // Verificar se email já existe
        if ($this->findByEmail($data['email'])) {
            throw new Exception('Email já cadastrado');
        }

        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['name'],
            $data['email'],
            hashPassword($data['password']),
            $data['role'] ?? 'admin'
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Atualizar usuário
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET name = ?, email = ?, role = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['role'] ?? 'admin',
            $id
        ]);
    }

    /**
     * Atualizar senha
     */
    public function updatePassword($id, $password) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            hashPassword($password),
            $id
        ]);
    }

    /**
     * Deletar usuário
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Verificar credenciais
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);

        if (!$user) {
            return false;
        }

        if (!verifyPassword($password, $user->password)) {
            return false;
        }

        return $user;
    }

    /**
     * Contar total de usuários
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
