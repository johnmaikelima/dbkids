<?php

namespace App\Controllers;

class HeroCarouselController {
    
    public function index() {
        $carousel = new \HeroCarousel();
        $carousels = $carousel->all();
        
        view('admin/hero-carousel/index', [
            'carousels' => $carousels
        ]);
    }

    public function edit() {
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            redirect('/admin/hero-carousel');
        }

        $carousel = new \HeroCarousel();
        $carouselData = $carousel->find($id);

        if (!$carouselData) {
            $_SESSION['error'] = 'Carousel não encontrado';
            redirect('/admin/hero-carousel');
        }

        $images = $carousel->getImages($id);

        view('admin/hero-carousel/edit', [
            'carousel' => $carouselData,
            'images' => $images
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/hero-carousel');
        }

        try {
            $id = $_POST['id'] ?? '';
            
            if (empty($id)) {
                redirect('/admin/hero-carousel');
            }

            $carousel = new \HeroCarousel();
            
            $data = [
                'title' => $_POST['title'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'auto_play' => isset($_POST['auto_play']) ? 1 : 0,
                'interval' => $_POST['interval'] ?? 5000
            ];

            $carousel->update($id, $data);
            
            $_SESSION['success'] = 'Carousel atualizado com sucesso!';
            redirect('/admin/hero-carousel/editar?id=' . $id);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar carousel: ' . $e->getMessage();
            redirect('/admin/hero-carousel');
        }
    }

    public function addImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/hero-carousel');
        }

        try {
            $carouselId = $_POST['carousel_id'] ?? '';
            
            if (empty($carouselId)) {
                redirect('/admin/hero-carousel');
            }

            $carousel = new \HeroCarousel();
            $uploadDir = BASE_PATH . '/public/uploads/hero/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $data = [
                'button_text' => $_POST['button_text'] ?? '',
                'button_url' => $_POST['button_url'] ?? '',
                'sort_order' => $_POST['sort_order'] ?? 0
            ];

            // Upload imagem desktop
            if (!empty($_FILES['image_desktop']['tmp_name'])) {
                $fileName = 'carousel_desktop_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['image_desktop']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_desktop']['tmp_name'], $filePath)) {
                    $data['image_desktop'] = '/public/uploads/hero/' . $fileName;
                }
            }

            // Upload imagem mobile
            if (!empty($_FILES['image_mobile']['tmp_name'])) {
                $fileName = 'carousel_mobile_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['image_mobile']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_mobile']['tmp_name'], $filePath)) {
                    $data['image_mobile'] = '/public/uploads/hero/' . $fileName;
                }
            }

            $carousel->addImage($carouselId, $data);
            
            $_SESSION['success'] = 'Imagem adicionada com sucesso!';
            redirect('/admin/hero-carousel/editar?id=' . $carouselId);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao adicionar imagem: ' . $e->getMessage();
            redirect('/admin/hero-carousel');
        }
    }

    public function editImage() {
        $imageId = $_GET['image_id'] ?? '';
        $carouselId = $_GET['carousel_id'] ?? '';
        
        if (empty($imageId) || empty($carouselId)) {
            redirect('/admin/hero-carousel');
        }

        try {
            $carousel = new \HeroCarousel();
            $carouselData = $carousel->find($carouselId);
            $images = $carousel->getImages($carouselId);
            
            $imageData = null;
            foreach ($images as $img) {
                if ($img->id == $imageId) {
                    $imageData = $img;
                    break;
                }
            }

            if (!$imageData) {
                $_SESSION['error'] = 'Slide não encontrado';
                redirect('/admin/hero-carousel/editar?id=' . $carouselId);
            }

            view('admin/hero-carousel/edit-image', [
                'carousel' => $carouselData,
                'image' => $imageData,
                'products' => (new \Product())->all(),
                'categories' => (new \Category())->all()
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao editar slide: ' . $e->getMessage();
            redirect('/admin/hero-carousel/editar?id=' . $carouselId);
        }
    }

    public function updateImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/hero-carousel');
        }

        try {
            $imageId = $_POST['image_id'] ?? '';
            $carouselId = $_POST['carousel_id'] ?? '';
            
            if (empty($imageId) || empty($carouselId)) {
                redirect('/admin/hero-carousel');
            }

            $carousel = new \HeroCarousel();
            $uploadDir = BASE_PATH . '/public/uploads/hero/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $data = [
                'type' => $_POST['type'] ?? 'image',
                'button_text' => $_POST['button_text'] ?? '',
                'button_url' => $_POST['button_url'] ?? '',
                'product_id' => $_POST['product_id'] ?? null,
                'category_id' => $_POST['category_id'] ?? null,
                'promotion_text' => $_POST['promotion_text'] ?? '',
                'sort_order' => $_POST['sort_order'] ?? 0
            ];

            // Verificar se foi selecionada uma imagem da galeria ou upload
            if (!empty($_POST['selected_desktop_path'])) {
                // Usar imagem selecionada da galeria
                $data['image_desktop'] = $_POST['selected_desktop_path'];
            } elseif (!empty($_FILES['image_desktop']['tmp_name'])) {
                // Upload de nova imagem desktop
                $fileName = 'carousel_desktop_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['image_desktop']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_desktop']['tmp_name'], $filePath)) {
                    $data['image_desktop'] = '/public/uploads/hero/' . $fileName;
                }
            }

            // Verificar se foi selecionada uma imagem da galeria ou upload
            if (!empty($_POST['selected_mobile_path'])) {
                // Usar imagem selecionada da galeria
                $data['image_mobile'] = $_POST['selected_mobile_path'];
            } elseif (!empty($_FILES['image_mobile']['tmp_name'])) {
                // Upload de nova imagem mobile
                $fileName = 'carousel_mobile_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['image_mobile']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_mobile']['tmp_name'], $filePath)) {
                    $data['image_mobile'] = '/public/uploads/hero/' . $fileName;
                }
            }

            $carousel->updateImage($imageId, $data);
            
            $_SESSION['success'] = 'Slide atualizado com sucesso!';
            redirect('/admin/hero-carousel/editar?id=' . $carouselId);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar slide: ' . $e->getMessage();
            redirect('/admin/hero-carousel');
        }
    }

    public function deleteImage() {
        $imageId = $_GET['image_id'] ?? '';
        $carouselId = $_GET['carousel_id'] ?? '';
        
        if (empty($imageId) || empty($carouselId)) {
            redirect('/admin/hero-carousel');
        }

        try {
            $carousel = new \HeroCarousel();
            $carousel->deleteImage($imageId);
            
            $_SESSION['success'] = 'Slide deletado com sucesso!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar slide: ' . $e->getMessage();
        }

        redirect('/admin/hero-carousel/editar?id=' . $carouselId);
    }
}
?>
