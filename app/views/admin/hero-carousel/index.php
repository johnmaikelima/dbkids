<?php
$title = 'Hero Carousel - DbKids';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-images"></i> Hero Carousel Fullwidth</h2>
            <p class="text-muted">Gerencie o carousel de imagens fullwidth da home</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead style="background: linear-gradient(135deg, #FF6B9D 0%, #845EC2 100%); color: white;">
                <tr>
                    <th>Título</th>
                    <th>Imagens</th>
                    <th>Auto Play</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carousels ?? [] as $carousel): 
                    $carouselModel = new \HeroCarousel();
                    $imageCount = count($carouselModel->getImages($carousel->id));
                ?>
                    <tr>
                        <td>
                            <strong><?php echo escape($carousel->title); ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo $imageCount; ?> imagem(ns)</span>
                        </td>
                        <td>
                            <?php if ($carousel->auto_play): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($carousel->is_active): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/hero-carousel/editar?id=<?php echo $carousel->id; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($carousels)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Nenhum carousel criado
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
