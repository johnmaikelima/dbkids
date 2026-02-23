<?php

// Função para verificar se usuário está autenticado
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

// Função para verificar se usuário é admin
function isAdmin() {
    return isAuthenticated() && $_SESSION['role'] === 'admin';
}

// Função para redirecionar
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Função para escapar HTML
function escape($string) {
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Função para formatar moeda
function formatCurrency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

// Função para gerar slug
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

// Função para validar email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para hash de senha
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Função para verificar senha
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Função para gerar número de pedido
function generateOrderNumber() {
    return 'PED-' . date('YmdHis') . '-' . rand(1000, 9999);
}

// Função para obter banco de dados
function getDB() {
    return Database::getInstance()->getConnection();
}

// Função para view
function view($name, $data = []) {
    extract($data);
    include APP_PATH . '/views/' . $name . '.php';
}

// Função para obter configuração
function config($key) {
    $config = [
        'app_name' => 'DbKids',
        'app_url' => 'http://localhost/DbKids',
        'mercado_pago_token' => 'YOUR_TOKEN_HERE',
    ];
    return $config[$key] ?? null;
}
