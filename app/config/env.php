<?php

class Env {
    private static $variables = [];
    private static $loaded = false;

    public static function load() {
        if (self::$loaded) {
            return;
        }

        $envFile = BASE_PATH . '/.env';
        
        if (!file_exists($envFile)) {
            die('Arquivo .env não encontrado. Copie .env.example para .env e configure.');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignorar comentários
            if (strpos($line, '#') === 0) {
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

        self::$loaded = true;
    }

    public static function get($key, $default = null) {
        if (!self::$loaded) {
            self::load();
        }

        return self::$variables[$key] ?? $default;
    }

    public static function all() {
        if (!self::$loaded) {
            self::load();
        }

        return self::$variables;
    }
}

// Carregar variáveis automaticamente
Env::load();
