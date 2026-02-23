<?php
$title = 'Pedidos';
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Pedidos</h3>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders ?? [] as $order): ?>
                    <tr>
                        <td><?php echo escape($order->order_number); ?></td>
                        <td><?php echo escape($order->customer_name); ?></td>
                        <td><?php echo formatCurrency($order->total_price); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $order->status === 'pending' ? 'warning' : ($order->status === 'paid' ? 'success' : 'info'); ?>">
                                <?php echo ucfirst($order->status); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                        <td>
                            <a href="/admin/pedidos/<?php echo $order->id; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
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
