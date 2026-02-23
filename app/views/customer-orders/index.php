<?php
$title = 'Meus Pedidos - DbKids';
ob_start();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Meus Pedidos</h2>

            <div class="card mb-4">
                <div class="card-body">
                    <p class="mb-0">
                        <strong>Cliente:</strong> <?php echo escape($customer->name); ?><br>
                        <strong>Email:</strong> <?php echo escape($customer->email); ?>
                    </p>
                </div>
            </div>

            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID do Pedido</th>
                                <th>Data</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <strong>#<?php echo escape($order->order_number); ?></strong>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?>
                                </td>
                                <td>
                                    R$ <?php echo number_format($order->total_price + $order->shipping_cost, 2, ',', '.'); ?>
                                </td>
                                <td>
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
                                    <span class="badge bg-<?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/meus-pedidos/detalhe?id=<?php echo $order->id; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Ver Detalhes
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhum pedido encontrado para este email.
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="/meus-pedidos" class="btn btn-secondary">
                    <i class="fas fa-search"></i> Buscar Outro Email
                </a>
                <a href="/" class="btn btn-outline-secondary">
                    <i class="fas fa-home"></i> Voltar para Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
