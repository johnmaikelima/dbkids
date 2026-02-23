<?php
$title = 'Categorias';
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Categorias de Produtos</h3>
    <a href="/admin/categorias/nova" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nova Categoria
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories ?? [] as $category): ?>
                    <tr>
                        <td><?php echo escape($category->name); ?></td>
                        <td><code><?php echo escape($category->slug); ?></code></td>
                        <td><?php echo substr(escape($category->description), 0, 50); ?></td>
                        <td>
                            <a href="/admin/categorias/editar/<?php echo $category->id; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="/admin/categorias/deletar/<?php echo $category->id; ?>" style="display:inline;">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
