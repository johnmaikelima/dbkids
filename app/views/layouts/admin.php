<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin - DbKids'; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/public/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-dark text-white p-3">
            <div class="mb-4">
                <h5 class="fw-bold"><i class="fas fa-child"></i> DbKids Admin</h5>
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/admin/dashboard">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#produtosMenu" role="button" aria-expanded="true" aria-controls="produtosMenu">
                        <i class="fas fa-box"></i> Produtos
                    </a>
                    <div class="collapse show" id="produtosMenu">
                        <ul class="nav flex-column ms-3 mt-2">
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white-50" href="/admin/produtos">
                                    <i class="fas fa-list"></i> Listar Produtos
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white-50" href="/admin/produtos/novo">
                                    <i class="fas fa-plus"></i> Novo Produto
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white-50" href="/admin/categorias">
                                    <i class="fas fa-tags"></i> Categorias
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white-50" href="/admin/produtos/categorias-massa">
                                    <i class="fas fa-edit"></i> Editar Categorias em Massa
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link text-white-50" href="/admin/tipos-variaveis">
                                    <i class="fas fa-sliders-h"></i> Tipos de Variáveis
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/admin/pedidos">
                        <i class="fas fa-shopping-bag"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/admin/clientes">
                        <i class="fas fa-users"></i> Clientes
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/admin/usuarios">
                        <i class="fas fa-user-shield"></i> Usuários
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/admin/hero-carousel">
                        <i class="fas fa-images"></i> Hero Carousel
                    </a>
                </li>
                <li class="nav-item mt-4 pt-4 border-top">
                    <a class="nav-link text-white" href="/admin/configuracoes">
                        <i class="fas fa-cog"></i> Configurações
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/" target="_blank">
                        <i class="fas fa-globe"></i> Ir para o Site
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/admin/logout">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Conteúdo Principal -->
        <div class="flex-grow-1">
            <!-- Top Bar -->
            <div class="bg-light border-bottom p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?php echo $title ?? 'Dashboard'; ?></h4>
                    <div>
                        <span class="me-3">Bem-vindo, <?php echo $_SESSION['user_name'] ?? 'Admin'; ?></span>
                        <a href="/admin/logout" class="btn btn-sm btn-danger">Sair</a>
                    </div>
                </div>
            </div>

            <!-- Mensagens -->
            <div class="container-fluid p-3">
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Conteúdo -->
            <div class="container-fluid p-3">
                <?php echo $content; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/admin.js"></script>
</body>
</html>
