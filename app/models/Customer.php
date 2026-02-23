<?php

class Customer {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter todos os clientes
     */
    public function all() {
        $stmt = $this->db->query("SELECT * FROM customers ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter cliente por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter cliente por email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Criar novo cliente
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO customers (name, email, phone, cpf, address, city, state, zip_code)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['cpf'] ?? null,
            $data['address'] ?? null,
            $data['city'] ?? null,
            $data['state'] ?? null,
            $data['zip_code'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Criar ou atualizar cliente
     */
    public function createOrUpdate($data) {
        $existing = $this->findByEmail($data['email']);

        if ($existing) {
            $this->update($existing->id, $data);
            return $existing->id;
        }

        return $this->create($data);
    }

    /**
     * Atualizar cliente
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE customers 
            SET name = ?, email = ?, phone = ?, cpf = ?, address = ?, city = ?, state = ?, zip_code = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['cpf'] ?? null,
            $data['address'] ?? null,
            $data['city'] ?? null,
            $data['state'] ?? null,
            $data['zip_code'] ?? null,
            $id
        ]);
    }

    /**
     * Deletar cliente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Contar total de clientes
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM customers");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    /**
     * Obter clientes com paginação
     */
    public function paginate($page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT * FROM customers 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
