<?php
$title = escape($category->name) . ' - DbKids';
$description = escape($category->description);
ob_start();
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active"><?php echo escape($category->name); ?></li>
        </ol>
    </nav>

    <div class="row mb-5">
        <div class="col-md-12">
            <h1 style="font-family: 'Fredoka', sans-serif; font-size: 36px; font-weight: 700; margin-bottom: 10px;">
                <?php echo escape($category->name); ?>
            </h1>
            <p class="text-muted"><?php echo escape($category->description); ?></p>
        </div>
    </div>

    <!-- Filtros de Ordenação -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px;">
                <p class="mb-0"><strong><?php echo count($products); ?> produtos encontrados</strong></p>
                <div>
                    <label for="sortSelect" class="form-label mb-0 me-2" style="display: inline;">Ordenar por:</label>
                    <select id="sortSelect" class="form-select" style="display: inline; width: auto;" onchange="window.location.href = '/categoria/<?php echo $category->slug; ?>?sort=' + this.value">
                        <option value="relevancia" <?php echo $currentSort === 'relevancia' ? 'selected' : ''; ?>>Relevância</option>
                        <option value="nome" <?php echo $currentSort === 'nome' ? 'selected' : ''; ?>>Nome (A-Z)</option>
                        <option value="preco_asc" <?php echo $currentSort === 'preco_asc' ? 'selected' : ''; ?>>Menor Preço</option>
                        <option value="preco_desc" <?php echo $currentSort === 'preco_desc' ? 'selected' : ''; ?>>Maior Preço</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Produtos -->
    <?php if (!empty($products)): ?>
        <div class="row mb-5">
            <?php foreach ($products as $product): 
                $mainImage = null;
                if (!empty($product->images)) {
                    foreach ($product->images as $img) {
                        if ($img->is_main) {
                            $mainImage = $img->image_path;
                            break;
                        }
                    }
                    if (!$mainImage && !empty($product->images)) {
                        $mainImage = $product->images[0]->image_path;
                    }
                }
            ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">
                        <div style="width: 100%; height: 200px; overflow: hidden; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
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
                        <div class="card-body">
                            <h5 class="card-title" style="font-family: 'Fredoka', sans-serif; font-weight: 700; min-height: 50px;">
                                <?php echo escape($product->name); ?>
                            </h5>
                            <p class="text-muted small" style="min-height: 40px;">
                                <?php echo substr(escape($product->description), 0, 60); ?>...
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 mb-0" style="color: #FF6B9D; font-weight: 700;">
                                    R$ <?php echo number_format($product->price, 2, ',', '.'); ?>
                                </span>
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
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Nenhum produto encontrado nesta categoria.
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
