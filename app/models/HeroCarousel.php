<?php

class HeroCarousel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Obter carousel ativo
     */
    public function getActive() {
        $stmt = $this->db->query("
            SELECT * FROM hero_carousel 
            WHERE is_active = 1 
            LIMIT 1
        ");
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Obter todas as imagens de um carousel
     */
    public function getImages($carouselId) {
        $stmt = $this->db->prepare("
            SELECT * FROM hero_carousel_images 
            WHERE carousel_id = ? 
            ORDER BY sort_order ASC
        ");
        $stmt->execute([$carouselId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter todos os carousels
     */
    public function all() {
        $stmt = $this->db->query("
            SELECT * FROM hero_carousel 
            ORDER BY id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obter carousel por ID
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM hero_carousel WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Criar novo carousel
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO hero_carousel (title, is_active, auto_play, interval)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['title'] ?? 'Carousel',
            $data['is_active'] ?? 1,
            $data['auto_play'] ?? 1,
            $data['interval'] ?? 5000
        ]);
    }

    /**
     * Atualizar carousel
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE hero_carousel 
            SET title = ?, is_active = ?, auto_play = ?, interval = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['title'] ?? '',
            $data['is_active'] ?? 1,
            $data['auto_play'] ?? 1,
            $data['interval'] ?? 5000,
            $id
        ]);
    }

    /**
     * Adicionar imagem ao carousel
     */
    public function addImage($carouselId, $data) {
        $type = $data['type'] ?? 'image';
        
        if ($type === 'image') {
            $stmt = $this->db->prepare("
                INSERT INTO hero_carousel_images (carousel_id, type, image_desktop, image_mobile, button_text, button_url, sort_order)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $carouselId,
                $type,
                $data['image_desktop'] ?? '',
                $data['image_mobile'] ?? '',
                $data['button_text'] ?? '',
                $data['button_url'] ?? '',
                $data['sort_order'] ?? 0
            ]);
        } else {
            // Tipo 'product'
            $stmt = $this->db->prepare("
                INSERT INTO hero_carousel_images (carousel_id, type, image_desktop, image_mobile, product_id, category_id, promotion_text, sort_order)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $carouselId,
                $type,
                $data['image_desktop'] ?? '',
                $data['image_mobile'] ?? '',
                $data['product_id'] ?? null,
                $data['category_id'] ?? null,
                $data['promotion_text'] ?? '',
                $data['sort_order'] ?? 0
            ]);
        }
    }

    /**
     * Atualizar imagem
     */
    public function updateImage($imageId, $data) {
        $type = $data['type'] ?? 'image';
        
        if ($type === 'image') {
            $stmt = $this->db->prepare("
                UPDATE hero_carousel_images 
                SET type = ?, image_desktop = ?, image_mobile = ?, button_text = ?, button_url = ?, product_id = NULL, category_id = NULL, promotion_text = NULL, sort_order = ?
                WHERE id = ?
            ");

            return $stmt->execute([
                $type,
                $data['image_desktop'] ?? '',
                $data['image_mobile'] ?? '',
                $data['button_text'] ?? '',
                $data['button_url'] ?? '',
                $data['sort_order'] ?? 0,
                $imageId
            ]);
        } else {
            // Tipo 'product'
            $stmt = $this->db->prepare("
                UPDATE hero_carousel_images 
                SET type = ?, image_desktop = ?, image_mobile = ?, product_id = ?, category_id = ?, promotion_text = ?, button_text = NULL, button_url = NULL, sort_order = ?
                WHERE id = ?
            ");

            return $stmt->execute([
                $type,
                $data['image_desktop'] ?? '',
                $data['image_mobile'] ?? '',
                $data['product_id'] ?? null,
                $data['category_id'] ?? null,
                $data['promotion_text'] ?? '',
                $data['sort_order'] ?? 0,
                $imageId
            ]);
        }
    }

    /**
     * Deletar imagem
     */
    public function deleteImage($imageId) {
        $stmt = $this->db->prepare("DELETE FROM hero_carousel_images WHERE id = ?");
        return $stmt->execute([$imageId]);
    }

    /**
     * Deletar carousel
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM hero_carousel WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
