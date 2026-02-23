<?php
$title = 'Editar Slide - DbKids';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-edit"></i> Editar Slide</h2>
            <p class="text-muted">Carousel: <strong><?php echo escape($carousel->title); ?></strong></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Slide</h5>
                </div>
                <div class="card-body">
                    <!-- Abas para tipo de slide -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $image->type === 'image' ? 'active' : ''; ?>" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-content" type="button" role="tab">
                                <i class="fas fa-image"></i> Imagem
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $image->type === 'product' ? 'active' : ''; ?>" id="product-tab" data-bs-toggle="tab" data-bs-target="#product-content" type="button" role="tab">
                                <i class="fas fa-box"></i> Produto
                            </button>
                        </li>
                    </ul>

                    <form method="POST" action="/admin/hero-carousel/atualizar-slide" enctype="multipart/form-data">
                        <input type="hidden" name="image_id" value="<?php echo $image->id; ?>">
                        <input type="hidden" name="carousel_id" value="<?php echo $carousel->id; ?>">
                        <input type="hidden" name="type" id="slideType" value="<?php echo $image->type; ?>">

                        <div class="tab-content">
                            <!-- Aba: Imagem -->
                            <div class="tab-pane fade <?php echo $image->type === 'image' ? 'show active' : ''; ?>" id="image-content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="image_desktop" class="form-label"><i class="fas fa-desktop"></i> Imagem Desktop (1920x600px)</label>
                                        <input type="file" class="form-control" id="image_desktop" name="image_desktop" accept="image/*">
                                        <?php if ($image->image_desktop): ?>
                                            <small class="text-muted d-block mt-2">Imagem atual:</small>
                                            <img src="<?php echo escape($image->image_desktop); ?>" alt="Desktop" style="max-width: 100%; max-height: 150px; margin-top: 5px;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="image_mobile" class="form-label"><i class="fas fa-mobile"></i> Imagem Mobile (600x800px)</label>
                                        <input type="file" class="form-control" id="image_mobile" name="image_mobile" accept="image/*">
                                        <?php if ($image->image_mobile): ?>
                                            <small class="text-muted d-block mt-2">Imagem atual:</small>
                                            <img src="<?php echo escape($image->image_mobile); ?>" alt="Mobile" style="max-width: 100%; max-height: 150px; margin-top: 5px;">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="button_text" class="form-label">Texto do Botão (opcional)</label>
                                        <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo escape($image->button_text ?? ''); ?>" placeholder="Ex: Comprar Agora">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="button_url" class="form-label">URL do Botão (opcional)</label>
                                        <input type="text" class="form-control" id="button_url" name="button_url" value="<?php echo escape($image->button_url ?? ''); ?>" placeholder="Ex: /categoria/bodies">
                                    </div>
                                </div>
                            </div>

                            <!-- Aba: Produto -->
                            <div class="tab-pane fade <?php echo $image->type === 'product' ? 'show active' : ''; ?>" id="product-content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="image_desktop_product" class="form-label"><i class="fas fa-desktop"></i> Imagem de Fundo Desktop (1920x600px)</label>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="image_desktop_product" name="image_desktop" accept="image/*">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#imageGalleryDesktop">
                                                <i class="fas fa-images"></i> Galeria
                                            </button>
                                        </div>
                                        <input type="hidden" id="selected_desktop_path" name="selected_desktop_path" value="">
                                        <?php if ($image->image_desktop): ?>
                                            <small class="text-muted d-block mt-2">Imagem atual:</small>
                                            <img src="<?php echo escape($image->image_desktop); ?>" alt="Desktop" id="preview_desktop" style="max-width: 100%; max-height: 150px; margin-top: 5px;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="image_mobile_product" class="form-label"><i class="fas fa-mobile"></i> Imagem de Fundo Mobile (600x800px)</label>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="image_mobile_product" name="image_mobile" accept="image/*">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#imageGalleryMobile">
                                                <i class="fas fa-images"></i> Galeria
                                            </button>
                                        </div>
                                        <input type="hidden" id="selected_mobile_path" name="selected_mobile_path" value="">
                                        <?php if ($image->image_mobile): ?>
                                            <small class="text-muted d-block mt-2">Imagem atual:</small>
                                            <img src="<?php echo escape($image->image_mobile); ?>" alt="Mobile" id="preview_mobile" style="max-width: 100%; max-height: 150px; margin-top: 5px;">
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="product_id" class="form-label">Selecionar Produto</label>
                                        <select class="form-select" id="product_id" name="product_id">
                                            <option value="">-- Selecione um Produto --</option>
                                            <?php foreach ($products as $p): ?>
                                                <option value="<?php echo $p->id; ?>" <?php echo $image->product_id == $p->id ? 'selected' : ''; ?>>
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
                                            <?php foreach ($categories as $c): ?>
                                                <option value="<?php echo $c->id; ?>" <?php echo $image->category_id == $c->id ? 'selected' : ''; ?>>
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
                                        <textarea class="form-control" id="promotion_text" name="promotion_text" rows="2" placeholder="Ex: Aproveite nossos produtos infantis com até 30% de desconto!"><?php echo escape($image->promotion_text ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <a href="/admin/hero-carousel/editar?id=<?php echo $carousel->id; ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Galeria Desktop -->
<div class="modal fade" id="imageGalleryDesktop" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-images"></i> Selecionar Imagem Desktop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    // Buscar todas as imagens do servidor
                    $uploadDirs = [
                        BASE_PATH . '/public/uploads/hero/',
                        BASE_PATH . '/public/uploads/products/',
                        BASE_PATH . '/public/uploads/'
                    ];
                    
                    $allImages = [];
                    foreach ($uploadDirs as $dir) {
                        if (is_dir($dir)) {
                            $files = glob($dir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
                            foreach ($files as $file) {
                                $relativePath = str_replace(BASE_PATH, '', $file);
                                $relativePath = str_replace('\\', '/', $relativePath);
                                $allImages[] = $relativePath;
                            }
                        }
                    }
                    
                    // Remover duplicatas e ordenar
                    $allImages = array_unique($allImages);
                    rsort($allImages);
                    
                    if (!empty($allImages)):
                        foreach ($allImages as $imgPath):
                    ?>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100" style="cursor: pointer;" onclick="selectDesktopImage('<?php echo escape($imgPath); ?>')">
                                <img src="<?php echo escape($imgPath); ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <small class="text-muted d-block mb-2" style="font-size: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo basename($imgPath); ?></small>
                                    <button type="button" class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-check"></i> Selecionar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Nenhuma imagem disponível. Faça upload de uma nova imagem.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Galeria Mobile -->
<div class="modal fade" id="imageGalleryMobile" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-images"></i> Selecionar Imagem Mobile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    // Buscar todas as imagens do servidor (reutilizar a mesma lista)
                    if (!empty($allImages)):
                        foreach ($allImages as $imgPath):
                    ?>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100" style="cursor: pointer;" onclick="selectMobileImage('<?php echo escape($imgPath); ?>')">
                                <img src="<?php echo escape($imgPath); ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <small class="text-muted d-block mb-2" style="font-size: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo basename($imgPath); ?></small>
                                    <button type="button" class="btn btn-sm btn-primary w-100">
                                        <i class="fas fa-check"></i> Selecionar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Nenhuma imagem disponível. Faça upload de uma nova imagem.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mudar o tipo de slide quando trocar de aba
    document.getElementById('image-tab').addEventListener('click', function() {
        document.getElementById('slideType').value = 'image';
    });
    
    document.getElementById('product-tab').addEventListener('click', function() {
        document.getElementById('slideType').value = 'product';
    });

    // Selecionar imagem desktop da galeria
    function selectDesktopImage(imagePath) {
        document.getElementById('selected_desktop_path').value = imagePath;
        
        // Atualizar preview se existir
        const preview = document.getElementById('preview_desktop');
        if (preview) {
            preview.src = imagePath;
            preview.style.display = 'block';
        } else {
            // Criar preview se não existir
            const container = document.getElementById('image_desktop_product').parentElement;
            const img = document.createElement('img');
            img.id = 'preview_desktop';
            img.src = imagePath;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '150px';
            img.style.marginTop = '10px';
            img.className = 'd-block';
            container.appendChild(img);
        }
        
        document.getElementById('image_desktop_product').value = '';
        
        // Fechar modal
        const modalElement = document.getElementById('imageGalleryDesktop');
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.hide();
        
        alert('Imagem desktop selecionada!');
    }

    // Selecionar imagem mobile da galeria
    function selectMobileImage(imagePath) {
        document.getElementById('selected_mobile_path').value = imagePath;
        
        // Atualizar preview se existir
        const preview = document.getElementById('preview_mobile');
        if (preview) {
            preview.src = imagePath;
            preview.style.display = 'block';
        } else {
            // Criar preview se não existir
            const container = document.getElementById('image_mobile_product').parentElement;
            const img = document.createElement('img');
            img.id = 'preview_mobile';
            img.src = imagePath;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '150px';
            img.style.marginTop = '10px';
            img.className = 'd-block';
            container.appendChild(img);
        }
        
        document.getElementById('image_mobile_product').value = '';
        
        // Fechar modal
        const modalElement = document.getElementById('imageGalleryMobile');
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.hide();
        
        alert('Imagem mobile selecionada!');
    }
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
