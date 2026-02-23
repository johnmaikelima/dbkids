<?php

class Env {
    private static $variables = [];

    public static function load($filePath = null) {
        if ($filePath === null) {
            $filePath = BASE_PATH . '/.env';
        }

        if (!file_exists($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignorar comentários
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parsear variável
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remover aspas se existirem
                if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                    (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
                    $value = substr($value, 1, -1);
                }
                
                self::$variables[$key] = $value;
            }
        }
    }

    public static function get($key, $default = null) {
        // Primeiro tenta variáveis de ambiente do sistema
        $value = getenv($key);
        
        if ($value !== false) {
            return $value;
        }

        // Depois tenta variáveis carregadas do .env
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }

        // Retorna valor padrão
        return $default;
    }

    public static function set($key, $value) {
        self::$variables[$key] = $value;
    }
}

// Função global para obter variável de ambiente (prioriza banco de dados)
if (!function_exists('getEnv')) {
    function getEnv($key, $default = null) {
        // Tentar buscar do banco de dados primeiro
        try {
            if (function_exists('getDB')) {
                $db = getDB();
                $stmt = $db->prepare("SELECT value FROM settings WHERE key = ?");
                $stmt->execute([$key]);
                $result = $stmt->fetch(\PDO::FETCH_COLUMN);
                
                error_log("getEnv({$key}): DB result = " . ($result ?: 'NULL/FALSE'));
                
                if ($result !== false && $result !== null && $result !== '') {
                    error_log("getEnv({$key}): Returning from DB: " . substr($result, 0, 20) . '...');
                    return $result;
                }
            }
        } catch (\Exception $e) {
            error_log("getEnv({$key}): Exception: " . $e->getMessage());
        }
        
        // Se não encontrou no banco, busca do .env
        $envResult = Env::get($key, $default);
        error_log("getEnv({$key}): Returning from .env: " . ($envResult ?: 'NULL'));
        return $envResult;
    }
}

// Carregar .env automaticamente
Env::load();
error_log("ENV loaded. MERCADO_PAGO_TOKEN: " . (Env::get('MERCADO_PAGO_TOKEN') ? 'SET' : 'NOT SET'));
?>
