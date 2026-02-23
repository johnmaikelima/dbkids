<?php

namespace App\Controllers;

class HeroSliderController {
    
    public function index() {
        $heroSlider = new \HeroSlider();
        $sliders = $heroSlider->all();
        
        view('admin/hero-slider/index', [
            'sliders' => $sliders
        ]);
    }

    public function create() {
        view('admin/hero-slider/form');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/hero-slider');
        }

        try {
            $heroSlider = new \HeroSlider();
            
            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'button_text' => $_POST['button_text'] ?? '',
                'button_url' => $_POST['button_url'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'sort_order' => $_POST['sort_order'] ?? 0
            ];

            // Upload imagem desktop
            if (!empty($_FILES['image_desktop']['tmp_name'])) {
                $uploadDir = BASE_PATH . '/public/uploads/hero/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = 'hero_desktop_' . time() . '.' . pathinfo($_FILES['image_desktop']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_desktop']['tmp_name'], $filePath)) {
                    $data['image_desktop'] = '/public/uploads/hero/' . $fileName;
                }
            }

            // Upload imagem mobile
            if (!empty($_FILES['image_mobile']['tmp_name'])) {
                $uploadDir = BASE_PATH . '/public/uploads/hero/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = 'hero_mobile_' . time() . '.' . pathinfo($_FILES['image_mobile']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_mobile']['tmp_name'], $filePath)) {
                    $data['image_mobile'] = '/public/uploads/hero/' . $fileName;
                }
            }

            $heroSlider->create($data);
            
            $_SESSION['success'] = 'Hero slider criado com sucesso!';
            redirect('/admin/hero-slider');
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao criar hero slider: ' . $e->getMessage();
            redirect('/admin/hero-slider/novo');
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            redirect('/admin/hero-slider');
        }

        $heroSlider = new \HeroSlider();
        $slider = $heroSlider->find($id);

        if (!$slider) {
            $_SESSION['error'] = 'Hero slider não encontrado';
            redirect('/admin/hero-slider');
        }

        view('admin/hero-slider/form', [
            'slider' => $slider
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/hero-slider');
        }

        try {
            $id = $_POST['id'] ?? '';
            
            if (empty($id)) {
                redirect('/admin/hero-slider');
            }

            $heroSlider = new \HeroSlider();
            $slider = $heroSlider->find($id);

            if (!$slider) {
                $_SESSION['error'] = 'Hero slider não encontrado';
                redirect('/admin/hero-slider');
            }

            $data = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'button_text' => $_POST['button_text'] ?? '',
                'button_url' => $_POST['button_url'] ?? '',
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'sort_order' => $_POST['sort_order'] ?? 0,
                'image_desktop' => $slider->image_desktop,
                'image_mobile' => $slider->image_mobile
            ];

            // Upload imagem desktop
            if (!empty($_FILES['image_desktop']['tmp_name'])) {
                $uploadDir = BASE_PATH . '/public/uploads/hero/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = 'hero_desktop_' . time() . '.' . pathinfo($_FILES['image_desktop']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_desktop']['tmp_name'], $filePath)) {
                    $data['image_desktop'] = '/public/uploads/hero/' . $fileName;
                }
            }

            // Upload imagem mobile
            if (!empty($_FILES['image_mobile']['tmp_name'])) {
                $uploadDir = BASE_PATH . '/public/uploads/hero/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = 'hero_mobile_' . time() . '.' . pathinfo($_FILES['image_mobile']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image_mobile']['tmp_name'], $filePath)) {
                    $data['image_mobile'] = '/public/uploads/hero/' . $fileName;
                }
            }

            $heroSlider->update($id, $data);
            
            $_SESSION['success'] = 'Hero slider atualizado com sucesso!';
            redirect('/admin/hero-slider');
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar hero slider: ' . $e->getMessage();
            redirect('/admin/hero-slider');
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            redirect('/admin/hero-slider');
        }

        try {
            $heroSlider = new \HeroSlider();
            $heroSlider->delete($id);
            
            $_SESSION['success'] = 'Hero slider deletado com sucesso!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao deletar hero slider: ' . $e->getMessage();
        }

        redirect('/admin/hero-slider');
    }
}
?>
