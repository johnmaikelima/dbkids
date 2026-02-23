<?php
$title = (isset($slider) ? 'Editar' : 'Novo') . ' Hero Slider - DbKids';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-image"></i> <?php echo isset($slider) ? 'Editar' : 'Novo'; ?> Hero Slider</h2>
        </div>
    </div>

    <form method="POST" action="<?php echo isset($slider) ? '/admin/hero-slider/atualizar' : '/admin/hero-slider/salvar'; ?>" enctype="multipart/form-data">
        <?php if (isset($slider)): ?>
            <input type="hidden" name="id" value="<?php echo $slider->id; ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #FF6B9D 0%, #845EC2 100%); color: white;">
                        <h5 class="mb-0"><i class="fas fa-heading"></i> Informações</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Título</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo escape($slider->title ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo escape($slider->description ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="button_text" class="form-label">Texto do Botão</label>
                                <input type="text" class="form-control" id="button_text" name="button_text" value="<?php echo escape($slider->button_text ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="button_url" class="form-label">URL do Botão</label>
                                <input type="text" class="form-control" id="button_url" name="button_url" value="<?php echo escape($slider->button_url ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Ordem</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo $slider->sort_order ?? 0; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">Ativo</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo (isset($slider) && $slider->is_active) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">
                                        Ativar este slider
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%); color: white;">
                        <h5 class="mb-0"><i class="fas fa-desktop"></i> Imagem Desktop</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <?php if (isset($slider) && $slider->image_desktop): ?>
                                <div class="mb-3">
                                    <img src="<?php echo escape($slider->image_desktop); ?>" alt="Desktop" style="max-width: 100%; border-radius: 8px;">
                                </div>
                            <?php endif; ?>
                            <label for="image_desktop" class="form-label">Upload Imagem Desktop (Recomendado: 1200x400px)</label>
                            <input type="file" class="form-control" id="image_desktop" name="image_desktop" accept="image/*">
                            <small class="text-muted">Formato: PNG, JPG, GIF (máx. 5MB)</small>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%); color: white;">
                        <h5 class="mb-0"><i class="fas fa-mobile"></i> Imagem Mobile</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <?php if (isset($slider) && $slider->image_mobile): ?>
                                <div class="mb-3">
                                    <img src="<?php echo escape($slider->image_mobile); ?>" alt="Mobile" style="max-width: 100%; border-radius: 8px;">
                                </div>
                            <?php endif; ?>
                            <label for="image_mobile" class="form-label">Upload Imagem Mobile (Recomendado: 600x600px)</label>
                            <input type="file" class="form-control" id="image_mobile" name="image_mobile" accept="image/*">
                            <small class="text-muted">Formato: PNG, JPG, GIF (máx. 5MB)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> <?php echo isset($slider) ? 'Atualizar' : 'Criar'; ?> Slider
                </button>
                <a href="/admin/hero-slider" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
