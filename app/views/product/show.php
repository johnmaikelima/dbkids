<?php
$title = escape($product->name) . ' - DbKids';
$description = substr(escape($product->description), 0, 160);
ob_start();
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <?php if (isset($firstCategory) && $firstCategory): ?>
                <li class="breadcrumb-item"><a href="/categoria/<?php echo escape($firstCategory->slug); ?>"><?php echo escape($firstCategory->name); ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?php echo escape($product->name); ?></li>
        </ol>
    </nav>

    <!-- Seção Principal: Imagem + Informações de Compra -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div id="productImages">
                <?php if (!empty($images)): ?>
                    <div style="width: 100%; height: 400px; overflow: hidden; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <img src="<?php echo escape($images[0]->image_path); ?>" alt="<?php echo escape($product->name); ?>" id="mainImage" style="max-width: 100%; max-height: 100%; object-fit: contain; transition: opacity 0.3s ease;">
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="row">
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-3 mb-2">
                                    <div style="width: 100%; height: 80px; overflow: hidden; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center; border: 2px solid transparent; transition: all 0.3s ease; cursor: pointer;" onmouseover="document.getElementById('mainImage').src = this.querySelector('img').src; this.style.borderColor = '#007bff';" onmouseout="this.style.borderColor = 'transparent';" onclick="document.getElementById('mainImage').src = this.querySelector('img').src">
                                        <img src="<?php echo escape($image->image_path); ?>" alt="<?php echo escape($product->name); ?>" class="img-fluid cursor-pointer thumbnail-image" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-light p-5 text-center" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <h1><?php echo escape($product->name); ?></h1>
            <p class="text-muted"><?php echo escape($product->category_name); ?></p>

            <div class="mb-4">
                <h3 class="text-primary"><?php echo formatCurrency($product->price); ?></h3>
                <p class="text-success">
                    <i class="fas fa-check-circle"></i> 
                    <?php echo $product->stock > 0 ? 'Em Estoque (' . $product->stock . ')' : 'Fora de Estoque'; ?>
                </p>
            </div>

            <!-- Variações do Produto -->
            <?php if (!empty($variationTypes)): ?>
                <div class="mb-4">
                    <form method="POST" action="/carrinho/adicionar" id="addToCartForm">
                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                        
                        <?php foreach ($variationTypes as $type): ?>
                            <?php 
                            $variationType = new VariationType();
                            $options = $variationType->getOptions($type->id);
                            ?>
                            <div class="mb-3">
                                <label class="form-label"><strong><?php echo escape($type->name); ?></strong></label>
                                <div class="btn-group w-100" role="group">
                                    <?php foreach ($options as $option): ?>
                                        <input type="radio" class="btn-check" name="variation_<?php echo $type->id; ?>" id="option_<?php echo $option->id; ?>" value="<?php echo $option->id; ?>" required>
                                        <label class="btn btn-outline-primary" for="option_<?php echo $option->id; ?>">
                                            <?php echo escape($option->value); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div style="display: flex; gap: 10px; align-items: flex-end;">
                            <div style="flex: 0 0 auto;">
                                <label for="quantity" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product->stock; ?>" style="width: 80px;">
                            </div>
                            <div style="flex: 1;">
                                <?php if ($product->stock > 0): ?>
                                    <button type="submit" class="btn btn-success btn-lg" style="width: 100%;">
                                        <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-lg" style="width: 100%;" disabled>
                                        Fora de Estoque
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- Sem variações -->
                <form method="POST" action="/carrinho/adicionar">
                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product->stock; ?>">
                    </div>
                    <?php if ($product->stock > 0): ?>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-shopping-cart"></i> Adicionar ao Carrinho
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100" disabled>
                            Fora de Estoque
                        </button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Seção de Detalhes: Descrição, Características e Dimensões -->
    <div class="row">
        <div class="col-md-12">
            <hr class="my-5">
            
            <div class="mb-5">
                <h5>Descrição</h5>
                <p><?php echo escape($product->description); ?></p>
            </div>

            <div class="row">
                <?php if (!empty($attributes)): ?>
                    <div class="col-md-6 mb-5">
                        <h5>Características</h5>
                        <ul class="list-group">
                            <?php foreach ($attributes as $attr): ?>
                                <li class="list-group-item">
                                    <strong><?php echo escape($attr->attribute_name); ?>:</strong>
                                    <?php echo escape($attr->attribute_value); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="col-md-6 mb-5">
                    <h5>Dimensões</h5>
                    <ul class="list-unstyled">
                        <?php if ($product->weight): ?>
                            <li><strong>Peso:</strong> <?php echo $product->weight; ?> kg</li>
                        <?php endif; ?>
                        <?php if ($product->length): ?>
                            <li><strong>Comprimento:</strong> <?php echo $product->length; ?> cm</li>
                        <?php endif; ?>
                        <?php if ($product->width): ?>
                            <li><strong>Largura:</strong> <?php echo $product->width; ?> cm</li>
                        <?php endif; ?>
                        <?php if ($product->height): ?>
                            <li><strong>Altura:</strong> <?php echo $product->height; ?> cm</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
