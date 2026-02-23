<?php

class Router {
    private $routes = [];

    public function __construct() {
        $this->registerRoutes();
    }

    private function registerRoutes() {
        // Rota para adicionar coluna payment_link
        $this->routes['GET']['/add-payment-link-column'] = function() {
            try {
                $db = getDB();
                $db->exec("ALTER TABLE orders ADD COLUMN payment_link TEXT");
                echo "✓ Coluna payment_link adicionada!<br>";
                echo "<a href='/'>Voltar</a>";
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'duplicate column name') !== false) {
                    echo "✓ Coluna payment_link já existe<br>";
                    echo "<a href='/'>Voltar</a>";
                } else {
                    echo "Erro: " . $e->getMessage();
                }
            }
        };

        // Rota temporária para criar tabela settings
        $this->routes['GET']['/setup-settings'] = function() {
            try {
                $db = getDB();
                $db->exec("CREATE TABLE IF NOT EXISTS settings (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    setting_key TEXT UNIQUE NOT NULL,
                    setting_value TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");
                echo "✓ Tabela settings criada com sucesso!<br>";
                echo "<a href='/admin/configuracoes'>Ir para Configurações</a>";
            } catch (Exception $e) {
                echo "Erro: " . $e->getMessage();
            }
        };

        // Rota para debug direto do banco
        $this->routes['GET']['/debug-db'] = function() {
            $db = getDB();
            
            echo "<h2>Debug Direto do Banco</h2>";
            
            // Buscar TOKEN
            $stmt = $db->prepare("SELECT value FROM settings WHERE key = ?");
            $stmt->execute(['MERCADO_PAGO_TOKEN']);
            $token = $stmt->fetch(PDO::FETCH_COLUMN);
            
            echo "<p><strong>Query direta TOKEN:</strong> " . ($token ?: '(vazio)') . "</p>";
            echo "<p><strong>Tipo:</strong> " . gettype($token) . "</p>";
            echo "<p><strong>Comprimento:</strong> " . strlen($token) . "</p>";
            
            // Testar getEnv
            echo "<hr>";
            echo "<p><strong>getEnv('MERCADO_PAGO_TOKEN'):</strong> " . (getEnv('MERCADO_PAGO_TOKEN') ?: '(vazio)') . "</p>";
            
            // Verificar se função getDB está disponível quando getEnv é chamado
            echo "<p><strong>function_exists('getDB'):</strong> " . (function_exists('getDB') ? 'SIM' : 'NÃO') . "</p>";
        };

        // Rota de teste para verificar credenciais do Mercado Pago
        $this->routes['GET']['/test-mp-settings'] = function() {
            try {
                $db = getDB();
                
                echo "<h2>Teste de Configurações do Mercado Pago</h2>";
                
                // Verificar se a tabela existe
                $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'");
                $tableExists = $stmt->fetch();
                
                if (!$tableExists) {
                    echo "<p style='color: red;'>❌ Tabela 'settings' não existe!</p>";
                    echo "<a href='/setup-settings'>Criar tabela settings</a>";
                    return;
                }
                
                echo "<p style='color: green;'>✓ Tabela 'settings' existe</p>";
                
                // Verificar estrutura da tabela
                $stmt = $db->query("PRAGMA table_info(settings)");
                $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                echo "<h3>Estrutura da tabela:</h3>";
                echo "<pre>";
                print_r($columns);
                echo "</pre>";
                
                // Buscar todas as configurações
                $stmt = $db->query("SELECT * FROM settings");
                $settings = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                echo "<h3>Configurações no banco de dados:</h3>";
                echo "<table border='1' cellpadding='10'>";
                echo "<tr><th>Chave</th><th>Valor</th><th>Criado em</th></tr>";
                
                if (empty($settings)) {
                    echo "<tr><td colspan='3'>Nenhuma configuração encontrada</td></tr>";
                } else {
                    foreach ($settings as $setting) {
                        $value = $setting['value'] ?? '';
                        $key = $setting['key'] ?? '';
                        // Mascarar valores sensíveis
                        if (strpos($key, 'MERCADO_PAGO') !== false && !empty($value)) {
                            $value = substr($value, 0, 20) . '...';
                        }
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($key) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($value ?: '(vazio)') . "</td>";
                        echo "<td>" . htmlspecialchars($setting['created_at'] ?? '') . "</td>";
                        echo "</tr>";
                    }
                }
                
                echo "</table>";
                
                // Testar função getEnv()
                echo "<h3>Teste da função getEnv():</h3>";
                $token = getEnv('MERCADO_PAGO_TOKEN');
                $publicKey = getEnv('MERCADO_PAGO_PUBLIC_KEY');
                $secret = getEnv('MERCADO_PAGO_WEBHOOK_SECRET');
                
                echo "<p><strong>MERCADO_PAGO_TOKEN:</strong> " . ($token ? substr($token, 0, 20) . '... (' . strlen($token) . ' caracteres)' : '(vazio)') . "</p>";
                echo "<p><strong>MERCADO_PAGO_PUBLIC_KEY:</strong> " . ($publicKey ? substr($publicKey, 0, 20) . '... (' . strlen($publicKey) . ' caracteres)' : '(vazio)') . "</p>";
                echo "<p><strong>MERCADO_PAGO_WEBHOOK_SECRET:</strong> " . ($secret ? substr($secret, 0, 20) . '... (' . strlen($secret) . ' caracteres)' : '(vazio)') . "</p>";
                
                echo "<br><a href='/admin/configuracoes'>Ir para Configurações</a>";
                
            } catch (Exception $e) {
                echo "Erro: " . $e->getMessage();
            }
        };

        // Rotas públicas
        $this->routes['GET']['/'] = 'HomeController@index';
        $this->routes['GET']['/buscar'] = 'SearchController@index';
        $this->routes['GET']['/categoria/(:slug)'] = 'CategoryController@show';
        $this->routes['GET']['/produto/(:slug)'] = 'ProductController@show';
        $this->routes['GET']['/carrinho'] = 'CartController@index';
        $this->routes['POST']['/carrinho/adicionar'] = 'CartController@add';
        $this->routes['POST']['/carrinho/remover'] = 'CartController@remove';
        $this->routes['GET']['/contato'] = 'ContactController@index';
        $this->routes['POST']['/contato/enviar'] = 'ContactController@send';

        // Rotas de checkout
        $this->routes['GET']['/checkout'] = 'CheckoutController@index';
        $this->routes['POST']['/checkout/processar'] = 'CheckoutController@process';
        $this->routes['POST']['/checkout/gerar-link-pagamento'] = 'CheckoutController@generatePaymentLink';
        $this->routes['GET']['/checkout/sucesso'] = 'CheckoutController@success';
        $this->routes['GET']['/checkout/falha'] = 'CheckoutController@failure';
        $this->routes['GET']['/checkout/pendente'] = 'CheckoutController@pending';
        $this->routes['POST']['/checkout/webhook'] = 'CheckoutController@webhook';

        // Rotas de pedidos do cliente
        $this->routes['GET']['/meus-pedidos'] = 'CustomerOrdersController@index';
        $this->routes['GET']['/meus-pedidos/detalhe'] = 'CustomerOrdersController@detail';

        // Rotas de autenticação
        $this->routes['GET']['/admin/login'] = 'AuthController@loginForm';
        $this->routes['POST']['/admin/login'] = 'AuthController@login';
        $this->routes['GET']['/admin/logout'] = 'AuthController@logout';

        // Rotas admin (protegidas)
        $this->routes['GET']['/admin/dashboard'] = 'DashboardController@index';
        $this->routes['GET']['/admin/usuarios'] = 'UserController@index';
        $this->routes['GET']['/admin/usuarios/novo'] = 'UserController@create';
        $this->routes['POST']['/admin/usuarios/salvar'] = 'UserController@store';
        $this->routes['GET']['/admin/usuarios/editar/(:id)'] = 'UserController@edit';
        $this->routes['POST']['/admin/usuarios/atualizar'] = 'UserController@update';
        $this->routes['POST']['/admin/usuarios/deletar/(:id)'] = 'UserController@delete';

        // Rotas de produtos
        $this->routes['GET']['/admin/produtos'] = 'ProductController@index';
        $this->routes['GET']['/admin/produtos/novo'] = 'ProductController@create';
        $this->routes['POST']['/admin/produtos/salvar'] = 'ProductController@store';
        $this->routes['GET']['/admin/produtos/editar/(:id)'] = 'ProductController@edit';
        $this->routes['POST']['/admin/produtos/atualizar'] = 'ProductController@update';
        $this->routes['POST']['/admin/produtos/enviar-imagens'] = 'ProductController@uploadImages';
        $this->routes['POST']['/admin/produtos/deletar/(:id)'] = 'ProductController@delete';
        $this->routes['POST']['/admin/produtos/definir-capa'] = 'ProductController@setMainImage';
        $this->routes['POST']['/admin/produtos/deletar-imagem'] = 'ProductController@deleteImage';
        $this->routes['GET']['/admin/produtos/categorias-massa'] = 'ProductController@bulkCategories';
        $this->routes['POST']['/admin/produtos/atualizar-categorias-massa'] = 'ProductController@updateBulkCategories';

        // Rotas de categorias
        $this->routes['GET']['/admin/categorias'] = 'CategoryController@index';
        $this->routes['GET']['/admin/categorias/nova'] = 'CategoryController@create';
        $this->routes['POST']['/admin/categorias/salvar'] = 'CategoryController@store';
        $this->routes['GET']['/admin/categorias/editar/(:id)'] = 'CategoryController@edit';
        $this->routes['POST']['/admin/categorias/atualizar'] = 'CategoryController@update';
        $this->routes['POST']['/admin/categorias/deletar/(:id)'] = 'CategoryController@delete';

        // Rotas de pedidos
        $this->routes['GET']['/admin/pedidos'] = 'OrderController@index';
        $this->routes['GET']['/admin/pedidos/(:id)'] = 'OrderController@show';
        $this->routes['POST']['/admin/pedidos/atualizar-status'] = 'OrderController@updateStatus';

        // Rotas de clientes
        $this->routes['GET']['/admin/clientes'] = 'CustomerController@index';
        $this->routes['GET']['/admin/clientes/(:id)'] = 'CustomerController@show';

        // Rotas de variações de produtos
        $this->routes['POST']['/admin/variações/criar'] = 'VariationController@create';
        $this->routes['POST']['/admin/variações/atualizar'] = 'VariationController@update';
        $this->routes['POST']['/admin/variações/deletar'] = 'VariationController@delete';

        // Rotas de tipos de variáveis
        $this->routes['GET']['/admin/tipos-variaveis'] = 'VariableTypeController@index';

        // Rotas de configurações
        $this->routes['GET']['/admin/configuracoes'] = 'SettingsController@index';
        $this->routes['POST']['/admin/configuracoes/atualizar'] = 'SettingsController@update';

        // Rotas de hero carousel
        $this->routes['GET']['/admin/hero-carousel'] = 'HeroCarouselController@index';
        $this->routes['GET']['/admin/hero-carousel/editar'] = 'HeroCarouselController@edit';
        $this->routes['POST']['/admin/hero-carousel/atualizar'] = 'HeroCarouselController@update';
        $this->routes['POST']['/admin/hero-carousel/adicionar-imagem'] = 'HeroCarouselController@addImage';
        $this->routes['GET']['/admin/hero-carousel/editar-slide'] = 'HeroCarouselController@editImage';
        $this->routes['POST']['/admin/hero-carousel/atualizar-slide'] = 'HeroCarouselController@updateImage';
        $this->routes['GET']['/admin/hero-carousel/deletar-imagem'] = 'HeroCarouselController@deleteImage';
        $this->routes['POST']['/admin/tipos-variaveis/criar'] = 'VariableTypeController@create';
        $this->routes['POST']['/admin/tipos-variaveis/adicionar-valor'] = 'VariableTypeController@addValue';
        $this->routes['POST']['/admin/tipos-variaveis/deletar-valor'] = 'VariableTypeController@deleteValue';
        $this->routes['POST']['/admin/tipos-variaveis/deletar'] = 'VariableTypeController@delete';

        // Rotas de variações simples
        $this->routes['POST']['/admin/variacoes-simples/criar'] = 'SimpleVariationController@create';
        $this->routes['POST']['/admin/variacoes-simples/deletar'] = 'SimpleVariationController@delete';

        // Rotas de tipos de variações simples
        $this->routes['POST']['/admin/variacoes-tipos/criar'] = 'VariationTypeController@create';
        $this->routes['POST']['/admin/variacoes-tipos/adicionar-opcao'] = 'VariationTypeController@addOption';
        $this->routes['POST']['/admin/variacoes-tipos/deletar-opcao'] = 'VariationTypeController@deleteOption';
        $this->routes['POST']['/admin/variacoes-tipos/deletar'] = 'VariationTypeController@delete';
    }

    public function dispatch($uri) {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }

        foreach ($this->routes[$method] as $route => $handler) {
            if ($this->matchRoute($route, $uri, $params)) {
                $this->callHandler($handler, $params);
                return;
            }
        }

        $this->notFound();
    }

    private function matchRoute($route, $uri, &$params) {
        $pattern = preg_replace('/\(:([a-z]+)\)/', '(?P<$1>[^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }

    private function callHandler($handler, $params) {
        // Se for uma closure (função anônima), executa diretamente
        if ($handler instanceof \Closure) {
            return call_user_func_array($handler, $params);
        }

        list($controller, $action) = explode('@', $handler);
        $controllerFile = APP_PATH . '/controllers/' . $controller . '.php';

        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }

        require_once $controllerFile;
        $controllerClass = 'App\\Controllers\\' . $controller;

        if (!class_exists($controllerClass)) {
            $this->notFound();
            return;
        }

        $controllerInstance = new $controllerClass();
        call_user_func_array([$controllerInstance, $action], $params);
    }

    private function notFound() {
        http_response_code(404);
        echo '404 - Página não encontrada';
    }
}
