<?php
$title = 'Gerenciar Hero Slider - DbKids';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-images"></i> Hero Slider</h2>
                <a href="/admin/hero-slider/novo" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Slider
                </a>
            </div>
            <p class="text-muted">Gerencie as imagens e conteúdo do hero slider da home</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #FF6B9D 0%, #845EC2 100%); color: white;">
                <tr>
                    <th>Título</th>
                    <th>Desktop</th>
                    <th>Mobile</th>
                    <th>Ativo</th>
                    <th>Ordem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sliders ?? [] as $slider): ?>
                    <tr>
                        <td>
                            <strong><?php echo escape($slider->title); ?></strong><br>
                            <small class="text-muted"><?php echo substr(escape($slider->description), 0, 50); ?>...</small>
                        </td>
                        <td>
                            <?php if ($slider->image_desktop): ?>
                                <img src="<?php echo escape($slider->image_desktop); ?>" alt="Desktop" style="max-width: 80px; max-height: 50px; border-radius: 5px;">
                            <?php else: ?>
                                <span class="badge bg-warning">Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($slider->image_mobile): ?>
                                <img src="<?php echo escape($slider->image_mobile); ?>" alt="Mobile" style="max-width: 50px; max-height: 80px; border-radius: 5px;">
                            <?php else: ?>
                                <span class="badge bg-warning">Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($slider->is_active): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $slider->sort_order; ?></td>
                        <td>
                            <a href="/admin/hero-slider/editar?id=<?php echo $slider->id; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="/admin/hero-slider/deletar?id=<?php echo $slider->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                <i class="fas fa-trash"></i> Deletar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($sliders)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Nenhum slider criado ainda. <a href="/admin/hero-slider/novo">Criar primeiro slider</a>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
