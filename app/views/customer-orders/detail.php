<?php
$title = 'Detalhes do Pedido - DbKids';
ob_start();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-4">Detalhes do Pedido #<?php echo escape($order->order_number); ?></h2>

            <!-- Dados do Cliente -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Dados do Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Nome:</strong><br>
                                <?php echo escape($order->customer_name); ?>
                            </p>
                            <p class="mb-0">
                                <strong>Email:</strong><br>
                                <?php echo escape($order->customer_email); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status do Pedido -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Status:</strong><br>
                                <?php 
                                $statusClass = 'secondary';
                                $statusText = 'Pendente';
                                
                                if ($order->status === 'paid') {
                                    $statusClass = 'success';
                                    $statusText = 'Pago';
                                } elseif ($order->status === 'shipped') {
                                    $statusClass = 'info';
                                    $statusText = 'Enviado';
                                } elseif ($order->status === 'delivered') {
                                    $statusClass = 'success';
                                    $statusText = 'Entregue';
                                } elseif ($order->status === 'cancelled') {
                                    $statusClass = 'danger';
                                    $statusText = 'Cancelado';
                                }
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?> fs-6">
                                    <?php echo $statusText; ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Data do Pedido:</strong><br>
                                <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?>
                            </p>
                        </div>
                    </div>

                    <?php if ($order->tracking_code): ?>
                        <p class="mb-0">
                            <strong>Código de Rastreamento:</strong><br>
                            <code><?php echo escape($order->tracking_code); ?></code>
                        </p>
                    <?php endif; ?>

                    <?php if ($order->status === 'pending'): ?>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-circle"></i> 
                            <strong>Pagamento Pendente</strong>
                            <p class="mb-2">Seu pedido está aguardando pagamento.</p>
                            <?php if (!empty($order->payment_link)): ?>
                                <a href="<?php echo escape($order->payment_link); ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fas fa-credit-card"></i> Pagar Agora
                                </a>
                            <?php else: ?>
                                <form method="POST" action="/checkout/gerar-link-pagamento" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $order->id; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-credit-card"></i> Gerar Link de Pagamento
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Itens do Pedido -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Itens do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <a href="/produto/<?php echo escape($item->slug); ?>">
                                            <?php echo escape($item->product_name); ?>
                                        </a>
                                    </td>
                                    <td><?php echo $item->quantity; ?></td>
                                    <td>R$ <?php echo number_format($item->price, 2, ',', '.'); ?></td>
                                    <td>R$ <?php echo number_format($item->price * $item->quantity, 2, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Endereço de Entrega -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Endereço de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Endereço:</strong><br>
                                <?php echo escape($order->shipping_address); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo do Pedido -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">Resumo</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>R$ <?php echo number_format($order->total_price, 2, ',', '.'); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Frete:</span>
                        <strong>R$ <?php echo number_format($order->shipping_cost, 2, ',', '.'); ?></strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5>Total:</h5>
                        <h5>R$ <?php echo number_format($order->total_price + $order->shipping_cost, 2, ',', '.'); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/meus-pedidos?email=<?php echo urlencode($order->customer_email); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar para Meus Pedidos
        </a>
        <a href="/" class="btn btn-outline-secondary">
            <i class="fas fa-home"></i> Voltar para Home
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
