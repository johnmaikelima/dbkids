<?php
$title = 'Detalhes do Pedido';
ob_start();
?>

<div class="mb-4">
    <a href="/admin/pedidos" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informações do Pedido</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Número:</strong> <?php echo escape($order->order_number); ?></p>
                        <p><strong>Cliente:</strong> <?php echo escape($order->customer_name); ?></p>
                        <p><strong>Email:</strong> <?php echo escape($order->customer_email); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-<?php echo $order->status === 'pending' ? 'warning' : 'success'; ?>">
                                <?php echo ucfirst($order->status); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Itens do Pedido</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items ?? [] as $item): ?>
                            <tr>
                                <td><?php echo escape($item->product_name); ?></td>
                                <td><?php echo $item->quantity; ?></td>
                                <td><?php echo formatCurrency($item->price); ?></td>
                                <td><?php echo formatCurrency($item->price * $item->quantity); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Resumo Financeiro</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong><?php echo formatCurrency($order->total_price - $order->shipping_cost); ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Frete:</span>
                    <strong><?php echo formatCurrency($order->shipping_cost); ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Total:</span>
                    <strong class="h5"><?php echo formatCurrency($order->total_price); ?></strong>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Atualizar Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/pedidos/atualizar-status">
                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                    <div class="mb-3">
                        <select class="form-control" name="status" required>
                            <option value="pending" <?php echo $order->status === 'pending' ? 'selected' : ''; ?>>Pendente</option>
                            <option value="processing" <?php echo $order->status === 'processing' ? 'selected' : ''; ?>>Processando</option>
                            <option value="shipped" <?php echo $order->status === 'shipped' ? 'selected' : ''; ?>>Enviado</option>
                            <option value="delivered" <?php echo $order->status === 'delivered' ? 'selected' : ''; ?>>Entregue</option>
                            <option value="cancelled" <?php echo $order->status === 'cancelled' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Atualizar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
