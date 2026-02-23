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
                $result = $stmt->fetch(PDO::FETCH_COLUMN);
                
                // Se encontrou no banco, retorna o valor
                if ($result !== false && $result !== null) {
                    return $result;
                }
            }
        } catch (Exception $e) {
            // Se falhar, continua para buscar do .env
        }
        
        // Se não encontrou no banco, busca do .env
        $envValue = Env::get($key);
        if ($envValue !== false && $envValue !== null) {
            return $envValue;
        }
        
        // Se não encontrou em nenhum lugar, retorna o padrão
        return $default;
    }
}

// Função alternativa para obter variáveis do banco (sem cache)
function getSettingValue($key, $default = null) {
    try {
        if (function_exists('getDB')) {
            $db = getDB();
            $stmt = $db->prepare("SELECT value FROM settings WHERE key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch(PDO::FETCH_COLUMN);
            
            if ($result !== false && $result !== null) {
                return $result;
            }
        }
    } catch (Exception $e) {
        // Falha silenciosa
    }
    
    return $default;
}

// Carregar .env automaticamente
Env::load();
error_log("ENV loaded. MERCADO_PAGO_TOKEN: " . (Env::get('MERCADO_PAGO_TOKEN') ? 'SET' : 'NOT SET'));
?>
