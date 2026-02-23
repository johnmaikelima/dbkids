<?php

class Product {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter todos os produtos
     */
    public function all() {
        $stmt = $this->db->query("
            SELECT p.*, GROUP_CONCAT(c.name, ', ') as category_name 
            FROM products p 
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id 
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter produto por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, GROUP_CONCAT(c.name, ', ') as category_name 
            FROM products p 
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id 
            WHERE p.id = ?
            GROUP BY p.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter produto por slug
     */
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("
            SELECT p.*, GROUP_CONCAT(c.name, ', ') as category_name 
            FROM products p 
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id 
            WHERE p.slug = ?
            GROUP BY p.id
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Criar novo produto
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO products (category_id, name, slug, description, price, stock, weight, length, width, height)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['category_id'],
            $data['name'],
            generateSlug($data['name']),
            $data['description'] ?? null,
            $data['price'],
            $data['stock'] ?? 0,
            $data['weight'] ?? null,
            $data['length'] ?? null,
            $data['width'] ?? null,
            $data['height'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Atualizar produto
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE products 
            SET name = ?, slug = ?, description = ?, price = ?, stock = ?, weight = ?, length = ?, width = ?, height = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            generateSlug($data['name']),
            $data['description'] ?? null,
            $data['price'],
            $data['stock'] ?? 0,
            $data['weight'] ?? null,
            $data['length'] ?? null,
            $data['width'] ?? null,
            $data['height'] ?? null,
            $id
        ]);
    }

    /**
     * Deletar produto
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Obter características do produto
     */
    public function getAttributes($productId) {
        $stmt = $this->db->prepare("
            SELECT * FROM product_attributes 
            WHERE product_id = ?
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Adicionar característica
     */
    public function addAttribute($productId, $name, $value) {
        $stmt = $this->db->prepare("
            INSERT INTO product_attributes (product_id, attribute_name, attribute_value)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$productId, $name, $value]);
    }

    /**
     * Obter imagens do produto
     */
    public function getImages($productId) {
        $stmt = $this->db->prepare("
            SELECT * FROM product_images 
            WHERE product_id = ? 
            ORDER BY is_main DESC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Adicionar imagem
     */
    public function addImage($productId, $imagePath, $isMain = false) {
        $stmt = $this->db->prepare("
            INSERT INTO product_images (product_id, image_path, is_main)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$productId, $imagePath, $isMain ? 1 : 0]);
    }

    /**
     * Obter produtos por categoria
     */
    public function getByCategory($categoryId) {
        $stmt = $this->db->prepare("
            SELECT p.*, GROUP_CONCAT(c.name, ', ') as category_name 
            FROM products p 
            INNER JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id 
            WHERE pc.category_id = ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Buscar produtos
     */
    public function search($query) {
        $query = '%' . $query . '%';
        $stmt = $this->db->prepare("
            SELECT p.*, GROUP_CONCAT(c.name, ', ') as category_name 
            FROM products p 
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id 
            WHERE p.name LIKE ? OR p.description LIKE ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$query, $query]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter produtos com paginação
     */
    public function paginate($page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("
            SELECT p.*, GROUP_CONCAT(c.name, ', ') as category_name 
            FROM products p 
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id 
            GROUP BY p.id
            ORDER BY p.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Contar total de produtos
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM products");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }

    /**
     * Definir imagem como capa
     */
    public function setMainImage($productId, $imageId) {
        // Remover is_main de todas as imagens do produto
        $stmt = $this->db->prepare("
            UPDATE product_images 
            SET is_main = 0 
            WHERE product_id = ?
        ");
        $stmt->execute([$productId]);

        // Definir a imagem selecionada como capa
        $stmt = $this->db->prepare("
            UPDATE product_images 
            SET is_main = 1 
            WHERE id = ? AND product_id = ?
        ");
        return $stmt->execute([$imageId, $productId]);
    }

    /**
     * Deletar imagem
     */
    public function deleteImage($imageId, $productId) {
        // Obter caminho da imagem
        $stmt = $this->db->prepare("SELECT image_path FROM product_images WHERE id = ? AND product_id = ?");
        $stmt->execute([$imageId, $productId]);
        $image = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$image) {
            throw new Exception('Imagem não encontrada');
        }

        // Deletar arquivo físico
        $filePath = BASE_PATH . '/public' . $image->image_path;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Deletar registro do banco
        $stmt = $this->db->prepare("DELETE FROM product_images WHERE id = ? AND product_id = ?");
        return $stmt->execute([$imageId, $productId]);
    }

    /**
     * Obter categorias do produto
     */
    public function getCategories($productId) {
        $stmt = $this->db->prepare("
            SELECT c.* FROM categories c
            INNER JOIN product_categories pc ON c.id = pc.category_id
            WHERE pc.product_id = ?
            ORDER BY c.name ASC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Adicionar categoria ao produto
     */
    public function addCategory($productId, $categoryId) {
        $stmt = $this->db->prepare("
            INSERT OR IGNORE INTO product_categories (product_id, category_id)
            VALUES (?, ?)
        ");
        return $stmt->execute([$productId, $categoryId]);
    }

    /**
     * Remover categoria do produto
     */
    public function removeCategory($productId, $categoryId) {
        $stmt = $this->db->prepare("
            DELETE FROM product_categories 
            WHERE product_id = ? AND category_id = ?
        ");
        return $stmt->execute([$productId, $categoryId]);
    }

    /**
     * Atualizar categorias do produto
     */
    public function updateCategories($productId, $categoryIds) {
        // Remover todas as categorias atuais
        $stmt = $this->db->prepare("DELETE FROM product_categories WHERE product_id = ?");
        $stmt->execute([$productId]);

        // Adicionar novas categorias
        if (!empty($categoryIds)) {
            foreach ($categoryIds as $categoryId) {
                $this->addCategory($productId, $categoryId);
            }
        }
        return true;
    }
}
