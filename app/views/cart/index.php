<?php
$title = 'Carrinho de Compras';
ob_start();
?>

<div class="container">
    <h2 class="mb-4">Carrinho de Compras</h2>

    <?php if (empty($cart)): ?>
        <div class="alert alert-info">
            <p class="mb-0">Seu carrinho está vazio.</p>
            <a href="/" class="btn btn-primary mt-2">Continuar Comprando</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Preço</th>
                                    <th>Quantidade</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="/produto/<?php echo $item['slug']; ?>">
                                                <?php echo escape($item['name']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo formatCurrency($item['price']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo formatCurrency($item['price'] * $item['quantity']); ?></td>
                                        <td>
                                            <form method="POST" action="/carrinho/remover" style="display: inline;">
                                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
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
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Resumo</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal:</span>
                            <strong><?php echo formatCurrency($total); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Frete:</span>
                            <strong>A calcular</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <strong class="h5"><?php echo formatCurrency($total); ?></strong>
                        </div>
                        <a href="/checkout" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-credit-card"></i> Ir para Checkout
                        </a>
                        <a href="/" class="btn btn-secondary w-100">
                            Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
