<?php
$title = 'Resultados de Busca - ' . escape($query);
$description = 'Resultados de busca para: ' . escape($query);
ob_start();
?>

<div class="container py-5">
    <div class="mb-5">
        <h2 style="font-family: 'Fredoka', sans-serif; font-size: 32px; font-weight: 700; margin-bottom: 10px;">
            Resultados de Busca
        </h2>
        <p style="font-size: 16px; color: #666;">
            <?php if (!empty($query)): ?>
                Mostrando resultados para: <strong><?php echo escape($query); ?></strong>
            <?php else: ?>
                Digite algo para buscar
            <?php endif; ?>
        </p>
    </div>

    <?php if (!empty($query) && empty($products)): ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            Nenhum produto encontrado para "<strong><?php echo escape($query); ?></strong>". Tente buscar por outro termo.
        </div>
    <?php elseif (!empty($products)): ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="product-card">
                        <div style="height: 280px; overflow: hidden; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); position: relative;">
                            <?php 
                            $mainImage = null;
                            if (!empty($product->images)) {
                                foreach ($product->images as $img) {
                                    if ($img->is_main) {
                                        $mainImage = $img->image_path;
                                        break;
                                    }
                                }
                                if (!$mainImage) {
                                    $mainImage = $product->images[0]->image_path;
                                }
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
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-search me-2"></i>
            Use o campo de busca acima para procurar produtos.
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
