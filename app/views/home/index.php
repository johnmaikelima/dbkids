<?php
$title = 'DbKids - Loja Virtual de Produtos Infantis';
$description = 'Encontre os melhores produtos infantis com qualidade e segurança';
$keywords = 'produtos infantis, brinquedos, roupas, acessórios';
ob_start();
?>

<div class="container">
    <!-- Hero Carousel Fullwidth -->
    <?php 
    $carousel = new \HeroCarousel();
    $carouselData = $carousel->getActive();
    
    if ($carouselData):
        $images = $carousel->getImages($carouselData->id);
        if (!empty($images)):
            $isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(mobile|android|iphone|ipad)/i', $_SERVER['HTTP_USER_AGENT']);
    ?>
    <div style="position: relative; width: 100vw; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; margin-bottom: 40px; margin-top: 0; overflow: hidden;" class="carousel-wrapper">
        <div id="heroCarousel" style="position: relative; width: 100%; height: 500px; overflow: hidden;">
            <?php foreach ($images as $index => $image): 
                $displayImage = $isMobile && $image->image_mobile ? $image->image_mobile : $image->image_desktop;
            ?>
                <div class="carousel-slide" style="position: absolute; width: 100%; height: 100%; opacity: <?php echo $index === 0 ? '1' : '0'; ?>; transition: opacity 0.8s ease-in-out; top: 0; left: 0;">
                    <img src="<?php echo escape($displayImage); ?>" alt="Slide <?php echo $index + 1; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    
                    <?php if ($image->type === 'image' && $image->button_text && $image->button_url): ?>
                        <div style="position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); z-index: 10;">
                            <a href="<?php echo escape($image->button_url); ?>" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #FF6B9D 0%, #FF5A8A 100%); border: none; box-shadow: 0 4px 15px rgba(255, 107, 157, 0.4); padding: 12px 40px; font-size: 16px;">
                                <?php echo escape($image->button_text); ?>
                            </a>
                        </div>
                    <?php elseif ($image->type === 'product'): ?>
                        <?php 
                        $productToShow = null;
                        $categoryProducts = [];
                        $isCategory = false;
                        $productsJson = '[]';
                        
                        if ($image->product_id) {
                            $p = new \Product();
                            $productToShow = $p->find($image->product_id);
                            $productImages = $p->getImages($image->product_id);
                            $productToShow->images = $productImages;
                        } elseif ($image->category_id) {
                            $p = new \Product();
                            $categoryProducts = $p->getByCategory($image->category_id);
                            $isCategory = true;
                            if (!empty($categoryProducts)) {
                                // Carregar imagens para cada produto
                                foreach ($categoryProducts as $prod) {
                                    $prod->images = $p->getImages($prod->id);
                                }
                                $productToShow = $categoryProducts[0];
                                $productsJson = json_encode(array_map(function($prod) {
                                    return [
                                        'name' => $prod->name,
                                        'description' => substr($prod->description, 0, 100),
                                        'slug' => $prod->slug,
                                        'image' => !empty($prod->images) ? $prod->images[0]->image_path : ''
                                    ];
                                }, $categoryProducts));
                            }
                        }
                        
                        if ($productToShow):
                        ?>
                            <div class="product-info-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; width: 100%; background: linear-gradient(135deg, rgba(200, 180, 220, 0.45) 0%, rgba(180, 200, 220, 0.45) 100%); padding: 60px 0; z-index: 10; color: #2D3436; display: flex; justify-content: center; align-items: center;" data-slide-index="<?php echo $index; ?>" data-products="<?php echo htmlspecialchars($productsJson); ?>" data-is-category="<?php echo $isCategory ? '1' : '0'; ?>">
                                <!-- Setas de navegação (laterais do banner) -->
                                <?php if ($isCategory): ?>
                                    <button class="product-carousel-prev" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: rgba(123, 104, 238, 0.8); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(123, 104, 238, 0.4); z-index: 15;">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="product-carousel-next" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(123, 104, 238, 0.8); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(123, 104, 238, 0.4); z-index: 15;">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <div style="width: 100%; max-width: 1200px; padding: 0 80px; display: flex; justify-content: space-between; align-items: center; gap: 60px;">
                                    <div style="flex: 1; max-width: 500px;">
                                        <?php if ($image->promotion_text): ?>
                                            <p class="product-carousel-promo" style="font-family: 'Fredoka', sans-serif; font-size: 22px; margin-bottom: 15px; font-weight: 800; color: #7B68EE; text-transform: uppercase; letter-spacing: 3px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
                                                ✨ <?php echo escape($image->promotion_text); ?> ✨
                                            </p>
                                        <?php endif; ?>
                                        <h2 class="product-carousel-title" style="font-family: 'Fredoka', sans-serif; font-weight: 900; margin-bottom: 20px; font-size: 56px; color: #5A4A8A; text-shadow: 2px 2px 0px rgba(255,255,255,0.8), 4px 4px 0px rgba(90, 74, 138, 0.2); line-height: 1.1; letter-spacing: -1px;">
                                            <?php echo escape($productToShow->name); ?>
                                        </h2>
                                        <p class="product-carousel-desc" style="font-family: 'Quicksand', sans-serif; font-size: 20px; margin-bottom: 35px; line-height: 1.8; color: #2D3436; font-weight: 600;">
                                            <?php echo substr(escape($productToShow->description), 0, 120); ?>...
                                        </p>
                                        <a href="/produto/<?php echo $productToShow->slug; ?>" class="product-carousel-link" style="display: inline-block; background: linear-gradient(135deg, #7B68EE 0%, #6A5ACD 100%); color: white; font-family: 'Fredoka', sans-serif; font-weight: 800; padding: 20px 60px; font-size: 22px; border-radius: 50px; text-decoration: none; box-shadow: 0 12px 30px rgba(123, 104, 238, 0.4); transition: all 0.3s ease; border: none; cursor: pointer; letter-spacing: 1px;">
                                            <i class="fas fa-heart"></i> Ver Produto
                                        </a>
                                    </div>
                                    <div style="flex: 1; display: flex; justify-content: center; align-items: center; min-height: 380px;">
                                        <img class="product-carousel-image" src="<?php echo !empty($productToShow->images) ? escape($productToShow->images[0]->image_path) : ''; ?>" alt="<?php echo escape($productToShow->name); ?>" style="max-height: 380px; max-width: 100%; width: auto; height: auto; object-fit: contain; filter: drop-shadow(0 12px 24px rgba(0,0,0,0.3)); border-radius: 50%; background: rgba(255,255,255,0.2); padding: 25px; box-sizing: border-box;">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <!-- Indicadores -->
            <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 20; display: flex; gap: 10px;">
                <?php foreach ($images as $index => $image): ?>
                    <div class="carousel-indicator" style="width: 12px; height: 12px; border-radius: 50%; background: <?php echo $index === 0 ? '#FF6B9D' : 'rgba(255, 255, 255, 0.5)'; ?>; cursor: pointer; transition: all 0.3s;" data-index="<?php echo $index; ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const autoPlay = <?php echo $carouselData->auto_play ? 'true' : 'false'; ?>;
        const interval = <?php echo $carouselData->interval; ?>;

        function showSlide(n) {
            slides.forEach(slide => slide.style.opacity = '0');
            indicators.forEach(ind => ind.style.background = 'rgba(255, 255, 255, 0.5)');
            
            slides[n].style.opacity = '1';
            indicators[n].style.background = '#FF6B9D';
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        if (autoPlay && slides.length > 1) {
            setInterval(nextSlide, interval);
        }

        showSlide(0);

        // Processar slides de categoria com mini-carousel
        document.querySelectorAll('.product-info-overlay[data-is-category="1"]').forEach(overlay => {
            const productsJson = overlay.getAttribute('data-products');
            const slideIndex = parseInt(overlay.getAttribute('data-slide-index'));
            
            if (!productsJson || productsJson === '[]') return;
            
            try {
                const products = JSON.parse(productsJson);
                if (products.length === 0) return;
                
                const slide = slides[slideIndex];
                if (!slide) return;
                
                const titleEl = overlay.querySelector('.product-carousel-title');
                const descEl = overlay.querySelector('.product-carousel-desc');
                const linkEl = overlay.querySelector('.product-carousel-link');
                const productImgEl = overlay.querySelector('.product-carousel-image');
                const prevBtn = overlay.parentElement.querySelector('.product-carousel-prev');
                const nextBtn = overlay.parentElement.querySelector('.product-carousel-next');
                const counterEl = overlay.querySelector('.product-carousel-counter');
                
                if (!titleEl || !descEl || !linkEl || !productImgEl) return;
                
                let currentProductIndex = 0;
                let autoPlayInterval;
                
                function updateProduct() {
                    const product = products[currentProductIndex];
                    titleEl.textContent = product.name;
                    descEl.textContent = product.description + '...';
                    linkEl.href = '/produto/' + product.slug;
                    
                    if (product.image) {
                        productImgEl.src = product.image;
                        productImgEl.style.opacity = '1';
                    }
                    
                    if (counterEl) {
                        counterEl.textContent = (currentProductIndex + 1) + ' / ' + products.length;
                    }
                }
                
                function nextProduct() {
                    currentProductIndex = (currentProductIndex + 1) % products.length;
                    updateProduct();
                    resetAutoPlay();
                }
                
                function prevProduct() {
                    currentProductIndex = (currentProductIndex - 1 + products.length) % products.length;
                    updateProduct();
                    resetAutoPlay();
                }
                
                function resetAutoPlay() {
                    clearInterval(autoPlayInterval);
                    autoPlayInterval = setInterval(nextProduct, 6000);
                }
                
                // Adicionar event listeners aos botões
                if (prevBtn) prevBtn.addEventListener('click', prevProduct);
                if (nextBtn) nextBtn.addEventListener('click', nextProduct);
                
                // Carregar primeiro produto imediatamente
                updateProduct();
                
                // Mudar produto a cada 6 segundos (aumentado de 4)
                autoPlayInterval = setInterval(nextProduct, 6000);
            } catch (e) {
                console.error('Erro ao processar slide de categoria:', e);
            }
        });
    </script>
    <?php endif; endif; ?>

    <!-- Cards de Categorias Principais -->
    <div class="mb-5" style="margin-top: 60px;">
        <div class="row g-4">
            <!-- Baby -->
            <div class="col-md-4">
                <a href="/categoria/baby" style="text-decoration: none; color: inherit;">
                    <div class="category-card" style="position: relative; overflow: hidden; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); transition: all 0.4s ease; cursor: pointer; height: 500px;">
                        <img src="/public/uploads/img/Bebe.jpeg" alt="Baby" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;">
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(255, 182, 193, 0.95) 0%, rgba(255, 182, 193, 0.7) 100%); padding: 30px; text-align: center;">
                            <h3 style="font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 32px; color: #fff; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Baby</h3>
                            <p style="font-family: 'Quicksand', sans-serif; font-size: 16px; color: #fff; margin: 10px 0 0 0; font-weight: 600;">Produtos para bebês</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Menina -->
            <div class="col-md-4">
                <a href="/categoria/menina" style="text-decoration: none; color: inherit;">
                    <div class="category-card" style="position: relative; overflow: hidden; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); transition: all 0.4s ease; cursor: pointer; height: 500px;">
                        <img src="/public/uploads/img/meninas.jpeg" alt="Menina" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;">
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(255, 105, 180, 0.95) 0%, rgba(255, 105, 180, 0.7) 100%); padding: 30px; text-align: center;">
                            <h3 style="font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 32px; color: #fff; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Menina</h3>
                            <p style="font-family: 'Quicksand', sans-serif; font-size: 16px; color: #fff; margin: 10px 0 0 0; font-weight: 600;">Produtos para meninas</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Menino -->
            <div class="col-md-4">
                <a href="/categoria/menino" style="text-decoration: none; color: inherit;">
                    <div class="category-card" style="position: relative; overflow: hidden; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); transition: all 0.4s ease; cursor: pointer; height: 500px;">
                        <img src="/public/uploads/img/meninos.jpeg" alt="Menino" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;">
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(100, 149, 237, 0.95) 0%, rgba(100, 149, 237, 0.7) 100%); padding: 30px; text-align: center;">
                            <h3 style="font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 32px; color: #fff; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Menino</h3>
                            <p style="font-family: 'Quicksand', sans-serif; font-size: 16px; color: #fff; margin: 10px 0 0 0; font-weight: 600;">Produtos para meninos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.25) !important;
        }
        .category-card:hover img {
            transform: scale(1.1);
        }
    </style>
</div>

<!-- Seção Quem Somos - Fullwidth -->
<div style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; margin-top: 80px; margin-bottom: 0; background: linear-gradient(135deg, #F8F9FF 0%, #F0E6FF 100%);">
    <div class="container" style="max-width: 100%; padding: 0;">
        <div style="display: flex; align-items: stretch; min-height: 600px;">
            <!-- Imagem - 50% esquerda -->
            <div style="flex: 1; min-height: 600px; overflow: hidden;">
                <img src="/public/uploads/hero/Propaganda.jpeg" alt="Propaganda DbKids" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <!-- Conteúdo - 50% direita -->
            <div style="flex: 1; padding: 80px 60px; display: flex; flex-direction: column; justify-content: center; background: white;">
                <h2 style="font-family: 'Fredoka', sans-serif; font-size: 48px; font-weight: 900; color: #5A4A8A; margin-bottom: 30px; letter-spacing: -1px;">
                    Quem Somos
                </h2>
                
                <p style="font-family: 'Quicksand', sans-serif; font-size: 18px; line-height: 1.8; color: #2D3436; margin-bottom: 20px; font-weight: 600;">
                    Bem-vindos à <strong style="color: #FF6B9D;">DBKIDS</strong> - O Mundo da Moda Infantil! Somos mais do que uma loja de roupas para crianças, somos o espaço onde a imaginação ganha vida através da moda. Nossa missão é vestir os pequenos com conforto, qualidade e estilo, transformando cada look em uma aventura única.
                </p>
                
                <p style="font-family: 'Quicksand', sans-serif; font-size: 16px; line-height: 1.8; color: #555; margin-bottom: 20px;">
                    Desde roupas fofas para bebês até tendências modernas para os mais crescidinhos, nossa seleção cuidadosamente escolhida reflete a essência da infância em cada detalhe. Acreditamos que cada criança é uma estrela com seu próprio brilho, e é por isso que buscamos oferecer peças que as ajudem a expressar sua personalidade única.
                </p>
                
                <p style="font-family: 'Quicksand', sans-serif; font-size: 16px; line-height: 1.8; color: #555; margin-bottom: 40px;">
                    Aqui na <strong style="color: #FF6B9D;">DBKIDS</strong>, valorizamos a alegria, a criatividade e a autenticidade. Nosso compromisso é proporcionar às famílias uma experiência de compra incrível, onde a diversão e o estilo andam lado a lado. Junte-se a nós nessa jornada de cores, estampas e sorrisos contagiantes. Seja parte da nossa família e vista a moda com alegria e originalidade!
                </p>
                
                <div>
                    <a href="/categoria" class="btn btn-lg" style="background: linear-gradient(135deg, #FF6B9D 0%, #FF5A8A 100%); border: none; color: white; font-family: 'Fredoka', sans-serif; font-weight: 800; padding: 18px 50px; font-size: 18px; box-shadow: 0 8px 20px rgba(255, 107, 157, 0.3); transition: all 0.3s ease; letter-spacing: 1px;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(255, 107, 157, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 20px rgba(255, 107, 157, 0.3)'">
                        <i class="fas fa-shopping-bag"></i> Explorar Produtos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Produtos -->
    <div class="mb-5" style="margin-top: 80px;">
        <h2 style="font-family: 'Fredoka', sans-serif; font-size: 32px; font-weight: 700; margin-bottom: 30px; text-align: center;">
            ⭐ Produtos Destaque ⭐
        </h2>
        <div class="row">
            <?php foreach ($products ?? [] as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="product-card">
                        <div style="height: 280px; overflow: hidden; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); position: relative;">
                            <?php 
                            // Buscar imagem do produto
                            $productModel = new \Product();
                            $images = $productModel->getImages($product->id);
                            $mainImage = null;
                            foreach ($images as $img) {
                                if ($img->is_main) {
                                    $mainImage = $img->image_path;
                                    break;
                                }
                            }
                            if (!$mainImage && !empty($images)) {
                                $mainImage = $images[0]->image_path;
                            }
                            ?>
                            <?php if ($mainImage): ?>
                                <img src="<?php echo escape($mainImage); ?>" alt="<?php echo escape($product->name); ?>" class="w-100 h-100" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-4x text-muted mb-2"></i>
                                        <p class="text-muted">Sem imagem</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h5 class="product-name"><?php echo escape($product->name); ?></h5>
                            <p class="text-muted small" style="min-height: 40px;"><?php echo substr(escape($product->description), 0, 60); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="product-price">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></span>
                                <?php if ($product->stock > 0): ?>
                                    <span class="badge bg-success">Em Estoque</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Fora de Estoque</span>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="/produto/<?php echo $product->slug; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Ver Detalhes
                                </a>
                                <form method="POST" action="/carrinho/adicionar">
                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-success btn-sm w-100" <?php echo $product->stock <= 0 ? 'disabled' : ''; ?>>
                                        <i class="fas fa-shopping-cart"></i> Adicionar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
