<?php

namespace App\Controllers;

class SettingsController {
    
    public function index() {
        $setting = new \Setting();
        $settings = $setting->getAll();
        
        view('admin/settings/index', [
            'settings' => $settings
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/configuracoes');
        }

        try {
            $setting = new \Setting();
            
            // Atualizar informações da loja
            $setting->set('store_name', $_POST['store_name'] ?? 'DbKids');
            $setting->set('store_description', $_POST['store_description'] ?? '');
            $setting->set('store_email', $_POST['store_email'] ?? '');
            $setting->set('store_phone', $_POST['store_phone'] ?? '');
            $setting->set('store_address', $_POST['store_address'] ?? '');
            
            // Atualizar tema
            $setting->set('theme', $_POST['theme'] ?? 'colorful');
            $setting->set('primary_color', $_POST['primary_color'] ?? '#FF6B9D');
            $setting->set('secondary_color', $_POST['secondary_color'] ?? '#FFC75F');
            $setting->set('accent_color', $_POST['accent_color'] ?? '#845EC2');
            
            // Processar upload de logo
            if (!empty($_FILES['logo']['tmp_name'])) {
                $uploadDir = BASE_PATH . '/public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = 'logo_' . time() . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $filePath)) {
                    $setting->set('store_logo', '/public/uploads/' . $fileName);
                }
            }
            
            // Atualizar Mercado Pago
            if (isset($_POST['mercado_pago_token'])) {
                $setting->set('MERCADO_PAGO_TOKEN', $_POST['mercado_pago_token']);
            }
            if (isset($_POST['mercado_pago_public_key'])) {
                $setting->set('MERCADO_PAGO_PUBLIC_KEY', $_POST['mercado_pago_public_key']);
            }
            if (isset($_POST['mercado_pago_webhook_secret'])) {
                $setting->set('MERCADO_PAGO_WEBHOOK_SECRET', $_POST['mercado_pago_webhook_secret']);
            }
            
            $_SESSION['success'] = 'Configurações atualizadas com sucesso!';
            redirect('/admin/configuracoes');
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar configurações: ' . $e->getMessage();
            redirect('/admin/configuracoes');
        }
    }
}
?>
