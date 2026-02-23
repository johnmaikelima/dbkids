<?php
$title = 'Produtos';
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Produtos</h3>
    <a href="/admin/produtos/novo" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Produto
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products ?? [] as $product): ?>
                    <tr>
                        <td><?php echo $product->id; ?></td>
                        <td><?php echo escape($product->name); ?></td>
                        <td><?php echo escape($product->category_name); ?></td>
                        <td><?php echo formatCurrency($product->price); ?></td>
                        <td><?php echo $product->stock; ?></td>
                        <td>
                            <a href="/produto/<?php echo $product->slug; ?>" class="btn btn-sm btn-info" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/admin/produtos/editar/<?php echo $product->id; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="/admin/produtos/deletar/<?php echo $product->id; ?>" style="display:inline;">
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
