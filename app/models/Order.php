<?php

class Order {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter todos os pedidos
     */
    public function all() {
        $stmt = $this->db->query("
            SELECT o.*, c.name as customer_name, c.email as customer_email 
            FROM orders o 
            LEFT JOIN customers c ON o.customer_id = c.id 
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter pedido por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT o.*, c.name as customer_name, c.email as customer_email 
            FROM orders o 
            LEFT JOIN customers c ON o.customer_id = c.id 
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter pedido por número
     */
    public function findByNumber($orderNumber) {
        $stmt = $this->db->prepare("
            SELECT o.*, c.name as customer_name, c.email as customer_email 
            FROM orders o 
            LEFT JOIN customers c ON o.customer_id = c.id 
            WHERE o.order_number = ?
        ");
        $stmt->execute([$orderNumber]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Criar novo pedido
     */
    public function create($data) {
        $orderNumber = generateOrderNumber();
        
        $stmt = $this->db->prepare("
            INSERT INTO orders (customer_id, order_number, total_price, shipping_cost, shipping_address, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $totalPrice = 0;
        foreach ($data['items'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $stmt->execute([
            $data['customer_id'],
            $orderNumber,
            $totalPrice,
            $data['shipping_cost'] ?? 0,
            $data['shipping_address'] ?? null,
            'pending'
        ]);

        $orderId = $this->db->lastInsertId();

        // Adicionar itens do pedido
        foreach ($data['items'] as $item) {
            $this->addItem($orderId, $item['id'], $item['quantity'], $item['price']);
        }

        return $orderId;
    }

    /**
     * Adicionar item ao pedido
     */
    public function addItem($orderId, $productId, $quantity, $price) {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$orderId, $productId, $quantity, $price]);
    }

    /**
     * Obter itens do pedido
     */
    public function getItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name as product_name, p.slug 
            FROM order_items oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Atualizar status do pedido
     */
    public function updateStatus($orderId, $status) {
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET status = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$status, $orderId]);
    }

    /**
     * Atualizar ID de pagamento
     */
    public function updatePaymentId($orderId, $paymentId) {
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET payment_id = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$paymentId, $orderId]);
    }

    /**
     * Atualizar código de rastreamento
     */
    public function updateTrackingCode($orderId, $trackingCode) {
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET tracking_code = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$trackingCode, $orderId]);
    }

    /**
     * Obter pedidos do cliente
     */
    public function getByCustomer($customerId) {
        $stmt = $this->db->prepare("
            SELECT * FROM orders 
            WHERE customer_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter pedidos com paginação
     */
    public function paginate($page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT o.*, c.name as customer_name 
            FROM orders o 
            LEFT JOIN customers c ON o.customer_id = c.id 
            ORDER BY o.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Contar total de pedidos
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM orders");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    /**
     * Obter total de vendas
     */
    public function getTotalSales() {
        $stmt = $this->db->query("SELECT SUM(total_price) as total FROM orders WHERE status = 'paid'");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total ?? 0;
    }

    /**
     * Obter pedidos por status
     */
    public function getByStatus($status) {
        $stmt = $this->db->prepare("
            SELECT o.*, c.name as customer_name 
            FROM orders o 
            LEFT JOIN customers c ON o.customer_id = c.id 
            WHERE o.status = ? 
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
