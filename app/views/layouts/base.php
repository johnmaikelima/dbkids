<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'DbKids - Loja Virtual'; ?></title>
    <meta name="description" content="<?php echo $description ?? 'Loja virtual de produtos infantis'; ?>">
    <meta name="keywords" content="<?php echo $keywords ?? 'produtos infantis, brinquedos, roupas'; ?>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/public/css/theme.css" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de Promoção -->
    <div style="background: linear-gradient(90deg, #4A90E2 0%, #357ABD 100%); color: white; text-align: center; padding: 10px 0; font-size: 14px; font-weight: 600; letter-spacing: 2px;">
        <?php 
        $setting = new \Setting();
        $promoText = $setting->get('promo_text', 'ENVIO GRÁTIS ACIMA DE R$150,00');
        echo escape($promoText);
        ?>
    </div>

    <!-- Header Principal -->
    <header style="background: white; border-bottom: 1px solid #E8E8E8;">

        <!-- Logo, Pesquisa e Ícones -->
        <div style="padding: 15px 0;">
            <div class="container">
                <div style="display: flex; align-items: center; gap: 30px; justify-content: space-between;">
                    <!-- Menu Toggle Mobile (Esquerda) -->
                    <button class="navbar-toggler-mobile" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background: transparent; border: none; color: #2D3436; font-size: 24px; padding: 0; cursor: pointer; display: none; z-index: 10;">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Logo Esquerda -->
                    <div style="flex-shrink: 0;">
                        <a href="/" style="text-decoration: none; display: flex; align-items: center;">
                            <?php 
                            $logo = $setting->get('store_logo');
                            $storeName = $setting->get('store_name', 'DbKids');
                            if ($logo && file_exists(BASE_PATH . $logo)): 
                            ?>
                                <img src="<?php echo escape($logo); ?>" alt="Logo" style="max-height: 60px;">
                            <?php else: ?>
                                <h1 style="font-family: 'Fredoka', sans-serif; font-size: 28px; font-weight: 700; color: #2D3436; margin: 0;">
                                    <i class="fas fa-heart" style="color: #FF6B9D;"></i> <?php echo escape($storeName); ?>
                                </h1>
                            <?php endif; ?>
                        </a>
                    </div>

                    <!-- Campo de Pesquisa Centro -->
                    <div style="flex: 1; max-width: 400px;">
                        <form method="GET" action="/buscar" style="display: flex;">
                            <div style="display: flex; width: 100%; border: 2px solid #E8E8E8; border-radius: 25px; overflow: hidden; transition: all 0.3s;" onmouseover="this.style.borderColor='#FF6B9D'; this.style.boxShadow='0 4px 12px rgba(255, 107, 157, 0.2)'" onmouseout="this.style.borderColor='#E8E8E8'; this.style.boxShadow='none'">
                                <input type="text" name="q" placeholder="Buscar produtos..." style="flex: 1; border: none; padding: 12px 18px; font-family: 'Quicksand', sans-serif; font-size: 14px; outline: none;" value="<?php echo isset($_GET['q']) ? escape($_GET['q']) : ''; ?>">
                                <button type="submit" style="background: linear-gradient(135deg, #FF6B9D 0%, #FF5A8A 100%); border: none; color: white; padding: 0 18px; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Ícones Direita -->
                    <div style="display: flex; align-items: center; gap: 20px; flex-shrink: 0;">
                        <a href="/carrinho" style="text-decoration: none; color: #FF6B9D; font-size: 22px; position: relative; transition: all 0.3s; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(255, 107, 157, 0.1);" onmouseover="this.style.background='rgba(255, 107, 157, 0.2)'; this.style.transform='scale(1.1)'" onmouseout="this.style.background='rgba(255, 107, 157, 0.1)'; this.style.transform='scale(1)'">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if (!empty($_SESSION['cart'])): ?>
                                <span style="position: absolute; top: -5px; right: -5px; background: linear-gradient(135deg, #FF6B9D 0%, #FF5A8A 100%); color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; box-shadow: 0 2px 8px rgba(255, 107, 157, 0.3);">
                                    <?php echo count($_SESSION['cart']); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <a href="#" style="text-decoration: none; color: #845EC2; font-size: 22px; transition: all 0.3s; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(132, 94, 194, 0.1);" onmouseover="this.style.background='rgba(132, 94, 194, 0.2)'; this.style.transform='scale(1.1)'" onmouseout="this.style.background='rgba(132, 94, 194, 0.1)'; this.style.transform='scale(1)'">
                            <i class="fas fa-user"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

                <!-- Menu de Navegação -->
                <nav class="navbar navbar-expand-lg navbar-light" style="padding: 0; background: white; box-shadow: none; border: none;">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav mx-auto" style="gap: 30px;">
                            <li class="nav-item">
                                <a class="nav-link" href="/" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">HOME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">QUEM SOMOS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">DESTAQUES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">BODIES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">CONJUNTOS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">CALÇAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">VESTIDOS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/contato" style="color: #2D3436 !important; font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s;" onmouseover="this.style.color='#FF6B9D !important'; this.style.transform='scale(1.05)'" onmouseout="this.style.color='#2D3436 !important'; this.style.transform='scale(1)'">CONTATO</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Mensagens de Sucesso/Erro -->
    <div class="container" style="margin-top: 0; padding-top: 0;">
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

        <?php if (!empty($_SESSION['pending'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['pending']; unset($_SESSION['pending']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Conteúdo Principal -->
    <main class="py-4">
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer style="background: #2C3E50; color: #ECF0F1; margin-top: 80px;">
        <?php 
        $setting = new \Setting();
        $logo = $setting->get('store_logo');
        $storeName = $setting->get('store_name', 'DbKids');
        $email = $setting->get('store_email');
        $phone = $setting->get('store_phone');
        $address = $setting->get('store_address');
        ?>
        
        <!-- Seção Principal -->
        <div class="container" style="padding: 70px 0 40px;">
            <div class="row g-4">
                <!-- Sobre a Loja -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div style="margin-bottom: 25px;">
                        <?php if ($logo && file_exists(BASE_PATH . $logo)): ?>
                            <img src="<?php echo escape($logo); ?>" alt="Logo" style="max-height: 60px; margin-bottom: 20px; filter: brightness(0) invert(1);">
                        <?php else: ?>
                            <h4 style="font-family: 'Fredoka', sans-serif; font-weight: 700; margin-bottom: 20px; color: #fff; font-size: 28px;">
                                <?php echo escape($storeName); ?>
                            </h4>
                        <?php endif; ?>
                        <p style="color: #BDC3C7; line-height: 1.8; font-size: 15px; margin-bottom: 25px;">
                            <?php echo escape($setting->get('store_description', 'Loja virtual de produtos infantis de qualidade')); ?>
                        </p>
                        <div style="display: flex; gap: 12px;">
                            <a href="#" style="width: 40px; height: 40px; background: #3498DB; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none;" onmouseover="this.style.background='#2980B9'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='#3498DB'; this.style.transform='translateY(0)'">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #E74C3C; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none;" onmouseover="this.style.background='#C0392B'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='#E74C3C'; this.style.transform='translateY(0)'">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #27AE60; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none;" onmouseover="this.style.background='#229954'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='#27AE60'; this.style.transform='translateY(0)'">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#" style="width: 40px; height: 40px; background: #E67E22; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none;" onmouseover="this.style.background='#D35400'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='#E67E22'; this.style.transform='translateY(0)'">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Links Rápidos -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 style="font-family: 'Fredoka', sans-serif; font-weight: 600; color: #fff; margin-bottom: 20px; font-size: 18px;">
                        Links Rápidos
                    </h6>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;">
                            <a href="/" style="color: #BDC3C7; text-decoration: none; transition: all 0.3s; font-size: 15px; display: inline-block;" onmouseover="this.style.color='#3498DB'; this.style.paddingLeft='5px'" onmouseout="this.style.color='#BDC3C7'; this.style.paddingLeft='0'">
                                <i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px;"></i>Home
                            </a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="/carrinho" style="color: #BDC3C7; text-decoration: none; transition: all 0.3s; font-size: 15px; display: inline-block;" onmouseover="this.style.color='#3498DB'; this.style.paddingLeft='5px'" onmouseout="this.style.color='#BDC3C7'; this.style.paddingLeft='0'">
                                <i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px;"></i>Carrinho
                            </a>
                        </li>
                        <li style="margin-bottom: 12px;">
                            <a href="/meus-pedidos" style="color: #BDC3C7; text-decoration: none; transition: all 0.3s; font-size: 15px; display: inline-block;" onmouseover="this.style.color='#3498DB'; this.style.paddingLeft='5px'" onmouseout="this.style.color='#BDC3C7'; this.style.paddingLeft='0'">
                                <i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px;"></i>Meus Pedidos
                            </a>
                        </li>
                        <li>
                            <a href="/contato" style="color: #BDC3C7; text-decoration: none; transition: all 0.3s; font-size: 15px; display: inline-block;" onmouseover="this.style.color='#3498DB'; this.style.paddingLeft='5px'" onmouseout="this.style.color='#BDC3C7'; this.style.paddingLeft='0'">
                                <i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px;"></i>Contato
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contato -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 style="font-family: 'Fredoka', sans-serif; font-weight: 600; color: #fff; margin-bottom: 20px; font-size: 18px;">
                        Contato
                    </h6>
                    <div style="color: #BDC3C7; font-size: 15px;">
                        <?php if ($email): ?>
                            <p style="margin-bottom: 15px; display: flex; align-items: start; gap: 10px;">
                                <i class="fas fa-envelope" style="color: #3498DB; margin-top: 3px; flex-shrink: 0;"></i>
                                <a href="mailto:<?php echo escape($email); ?>" style="color: #BDC3C7; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#3498DB'" onmouseout="this.style.color='#BDC3C7'">
                                    <?php echo escape($email); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($phone): ?>
                            <p style="margin-bottom: 15px; display: flex; align-items: start; gap: 10px;">
                                <i class="fas fa-phone" style="color: #27AE60; margin-top: 3px; flex-shrink: 0;"></i>
                                <a href="tel:<?php echo escape($phone); ?>" style="color: #BDC3C7; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#27AE60'" onmouseout="this.style.color='#BDC3C7'">
                                    <?php echo escape($phone); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($address): ?>
                            <p style="margin-bottom: 0; display: flex; align-items: start; gap: 10px;">
                                <i class="fas fa-map-marker-alt" style="color: #E74C3C; margin-top: 3px; flex-shrink: 0;"></i>
                                <span><?php echo escape($address); ?></span>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Diferenciais -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 style="font-family: 'Fredoka', sans-serif; font-weight: 600; color: #fff; margin-bottom: 20px; font-size: 18px;">
                        Por que Escolher?
                    </h6>
                    <ul style="list-style: none; padding: 0; color: #BDC3C7; font-size: 15px;">
                        <li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-check-circle" style="color: #27AE60; flex-shrink: 0;"></i>
                            Produtos de Qualidade
                        </li>
                        <li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-shipping-fast" style="color: #3498DB; flex-shrink: 0;"></i>
                            Entrega Rápida
                        </li>
                        <li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-shield-alt" style="color: #E67E22; flex-shrink: 0;"></i>
                            Compra Segura
                        </li>
                        <li style="margin-bottom: 0; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-headset" style="color: #9B59B6; flex-shrink: 0;"></i>
                            Suporte Dedicado
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div style="background: #1A252F; padding: 25px 0; border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; color: #95A5A6; font-size: 14px;">
                    <p style="margin: 0;">
                        &copy; <?php echo date('Y'); ?> <strong style="color: #ECF0F1;"><?php echo escape($storeName); ?></strong>. Todos os direitos reservados.
                    </p>
                    <p style="margin: 0;">
                        Feito com <i class="fas fa-heart" style="color: #E74C3C;"></i> para crianças felizes
                        <?php if (isAdmin()): ?>
                            <span style="margin: 0 8px; color: #34495E;">|</span>
                            <a href="/admin/dashboard" style="color: #3498DB; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#5DADE2'" onmouseout="this.style.color='#3498DB'">Admin</a>
                            <a href="/admin/logout" style="color: #E74C3C; text-decoration: none; margin-left: 15px; transition: color 0.3s;" onmouseover="this.style.color='#EC7063'" onmouseout="this.style.color='#E74C3C'">Sair</a>
                        <?php else: ?>
                            <span style="margin: 0 8px; color: #34495E;">|</span>
                            <a href="/admin/login" style="color: #3498DB; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#5DADE2'" onmouseout="this.style.color='#3498DB'">Admin</a>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botão Flutuante WhatsApp -->
    <a href="https://api.whatsapp.com/send?phone=5511989283757" target="_blank" id="whatsapp-float" style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background: #25D366; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4); z-index: 9999; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 6px 25px rgba(37, 211, 102, 0.6)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 20px rgba(37, 211, 102, 0.4)'">
        <i class="fab fa-whatsapp"></i>
    </a>

    <style>
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        #whatsapp-float {
            animation: pulse 2s infinite;
        }

        #whatsapp-float:hover {
            animation: none;
        }

        @media (max-width: 768px) {
            #whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 26px;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/script.js"></script>
</body>
</html>
