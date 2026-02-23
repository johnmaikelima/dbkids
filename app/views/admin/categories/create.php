<?php
$title = 'Nova Categoria';
ob_start();
?>

<div class="mb-4">
    <h3>Nova Categoria</h3>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/categorias/salvar">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome da Categoria *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="text-muted">O slug será gerado automaticamente</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar Categoria
                        </button>
                        <a href="/admin/categorias" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
