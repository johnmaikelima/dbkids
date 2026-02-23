<?php
$title = 'Editar Hero Carousel - DbKids';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-images"></i> Editar Carousel: <?php echo escape($carousel->title); ?></h2>
        </div>
    </div>

    <!-- Configurações do Carousel -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #FF6B9D 0%, #845EC2 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Configurações</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/hero-carousel/atualizar">
                        <input type="hidden" name="id" value="<?php echo $carousel->id; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Título do Carousel</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo escape($carousel->title); ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="interval" class="form-label">Intervalo (ms)</label>
                                <input type="number" class="form-control" id="interval" name="interval" value="<?php echo $carousel->interval; ?>" min="1000" step="1000">
                                <small class="text-muted">Tempo entre transições (5000 = 5 segundos)</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Opções</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_play" name="auto_play" <?php echo $carousel->auto_play ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="auto_play">Auto Play</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $carousel->is_active ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">Ativo</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Configurações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Adicionar Slide -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-plus"></i> Adicionar Novo Slide</h5>
                </div>
                <div class="card-body">
                    <!-- Abas para tipo de slide -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-content" type="button" role="tab">
                                <i class="fas fa-image"></i> Imagem
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="product-tab" data-bs-toggle="tab" data-bs-target="#product-content" type="button" role="tab">
                                <i class="fas fa-box"></i> Produto
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Aba: Imagem -->
                        <div class="tab-pane fade show active" id="image-content" role="tabpanel">
                            <form method="POST" action="/admin/hero-carousel/adicionar-imagem" enctype="multipart/form-data">
                                <input type="hidden" name="carousel_id" value="<?php echo $carousel->id; ?>">
                                <input type="hidden" name="type" value="image">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="image_desktop" class="form-label"><i class="fas fa-desktop"></i> Imagem Desktop (1920x600px)</label>
                                        <input type="file" class="form-control" id="image_desktop" name="image_desktop" accept="image/*" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="image_mobile" class="form-label"><i class="fas fa-mobile"></i> Imagem Mobile (600x800px)</label>
                                        <input type="file" class="form-control" id="image_mobile" name="image_mobile" accept="image/*" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="button_text" class="form-label">Texto do Botão (opcional)</label>
                                        <input type="text" class="form-control" id="button_text" name="button_text" placeholder="Ex: Comprar Agora">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="button_url" class="form-label">URL do Botão (opcional)</label>
                                        <input type="text" class="form-control" id="button_url" name="button_url" placeholder="Ex: /categoria/bodies">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload"></i> Adicionar Imagem
                                </button>
                            </form>
                        </div>

                        <!-- Aba: Produto -->
                        <div class="tab-pane fade" id="product-content" role="tabpanel">
                            <form method="POST" action="/admin/hero-carousel/adicionar-imagem" enctype="multipart/form-data">
                                <input type="hidden" name="carousel_id" value="<?php echo $carousel->id; ?>">
                                <input type="hidden" name="type" value="product">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="image_desktop_product" class="form-label"><i class="fas fa-desktop"></i> Imagem de Fundo Desktop (1920x600px)</label>
                                        <input type="file" class="form-control" id="image_desktop_product" name="image_desktop" accept="image/*" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="image_mobile_product" class="form-label"><i class="fas fa-mobile"></i> Imagem de Fundo Mobile (600x800px)</label>
                                        <input type="file" class="form-control" id="image_mobile_product" name="image_mobile" accept="image/*" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="product_id" class="form-label">Selecionar Produto</label>
                                        <select class="form-select" id="product_id" name="product_id">
                                            <option value="">-- Selecione um Produto --</option>
                                            <?php 
                                            $product = new \Product();
                                            $products = $product->all();
                                            foreach ($products as $p): 
                                            ?>
                                                <option value="<?php echo $p->id; ?>">
                                                    <?php echo escape($p->name); ?> (R$ <?php echo number_format($p->price, 2, ',', '.'); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">OU selecione uma categoria abaixo</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Selecionar Categoria</label>
                                        <select class="form-select" id="category_id" name="category_id">
                                            <option value="">-- Selecione uma Categoria --</option>
                                            <?php 
                                            $category = new \Category();
                                            $categories = $category->all();
                                            foreach ($categories as $c): 
                                            ?>
                                                <option value="<?php echo $c->id; ?>">
                                                    <?php echo escape($c->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Se selecionar categoria, mostrará produtos aleatórios dela</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="promotion_text" class="form-label">Texto de Promoção (opcional)</label>
                                        <textarea class="form-control" id="promotion_text" name="promotion_text" rows="2" placeholder="Ex: Aproveite nossos produtos infantis com até 30% de desconto!"></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload"></i> Adicionar Slide de Produto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Imagens -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-images"></i> Imagens do Carousel (<?php echo count($images); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($images)): ?>
                        <div class="row">
                            <?php foreach ($images as $image): ?>
                                <div class="col-md-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header" style="background: <?php echo $image->type === 'product' ? '#E8F4F8' : '#F0F0F0'; ?>;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <span class="badge" style="background: <?php echo $image->type === 'product' ? '#4ECDC4' : '#4A90E2'; ?>;">
                                                        <?php echo $image->type === 'product' ? 'PRODUTO' : 'IMAGEM'; ?>
                                                    </span>
                                                    <small class="text-muted ms-2">Adicionado em: <?php echo date('d/m/Y H:i', strtotime($image->created_at)); ?></small>
                                                </div>
                                                <div>
                                                    <a href="/admin/hero-carousel/editar-slide?image_id=<?php echo $image->id; ?>&carousel_id=<?php echo $carousel->id; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>
                                                    <a href="/admin/hero-carousel/deletar-imagem?image_id=<?php echo $image->id; ?>&carousel_id=<?php echo $carousel->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                                        <i class="fas fa-trash"></i> Deletar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <h6>Desktop</h6>
                                                    <div style="height: 250px; overflow: hidden; background: #f0f0f0; border-radius: 5px;">
                                                        <?php if ($image->image_desktop): ?>
                                                            <img src="<?php echo escape($image->image_desktop); ?>" alt="Desktop" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6>Mobile</h6>
                                                    <div style="height: 250px; overflow: hidden; background: #f0f0f0; border-radius: 5px;">
                                                        <?php if ($image->image_mobile): ?>
                                                            <img src="<?php echo escape($image->image_mobile); ?>" alt="Mobile" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if ($image->type === 'image'): ?>
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <h6>Informações do Slide</h6>
                                                        <?php if ($image->button_text): ?>
                                                            <p class="mb-2">
                                                                <strong>Botão:</strong> <?php echo escape($image->button_text); ?><br>
                                                                <small class="text-muted">URL: <?php echo escape($image->button_url); ?></small>
                                                            </p>
                                                        <?php else: ?>
                                                            <p class="text-muted small">Sem botão configurado</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <h6>Informações do Produto</h6>
                                                        <?php 
                                                        $productInfo = null;
                                                        if ($image->product_id) {
                                                            $p = new \Product();
                                                            $productInfo = $p->find($image->product_id);
                                                        } elseif ($image->category_id) {
                                                            $c = new \Category();
                                                            $productInfo = $c->find($image->category_id);
                                                        }
                                                        ?>
                                                        <p class="mb-2">
                                                            <strong>Tipo:</strong> <?php echo $image->product_id ? 'Produto Específico' : 'Categoria'; ?><br>
                                                            <strong>Seleção:</strong> <?php echo $productInfo ? escape($productInfo->name) : 'Não configurado'; ?><br>
                                                            <?php if ($image->promotion_text): ?>
                                                                <strong>Promoção:</strong> <?php echo escape($image->promotion_text); ?>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhum slide adicionado ainda. Adicione slides acima!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/admin/hero-carousel" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
