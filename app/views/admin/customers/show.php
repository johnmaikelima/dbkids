<?php
$title = 'Detalhes do Cliente';
ob_start();
?>

<div class="mb-4">
    <a href="/admin/clientes" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informações do Cliente</h5>
            </div>
            <div class="card-body">
                <p><strong>Nome:</strong> <?php echo escape($customer->name); ?></p>
                <p><strong>Email:</strong> <?php echo escape($customer->email); ?></p>
                <p><strong>Telefone:</strong> <?php echo escape($customer->phone); ?></p>
                <p><strong>CPF:</strong> <?php echo escape($customer->cpf); ?></p>
                <p><strong>Endereço:</strong> <?php echo escape($customer->address); ?></p>
                <p><strong>Cidade:</strong> <?php echo escape($customer->city); ?></p>
                <p><strong>Estado:</strong> <?php echo escape($customer->state); ?></p>
                <p><strong>CEP:</strong> <?php echo escape($customer->zip_code); ?></p>
                <p><strong>Data de Cadastro:</strong> <?php echo date('d/m/Y H:i', strtotime($customer->created_at)); ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pedidos do Cliente</h5>
            </div>
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <p class="text-muted">Nenhum pedido encontrado</p>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <a href="/admin/pedidos/<?php echo $order->id; ?>">
                                            <?php echo escape($order->order_number); ?>
                                        </a>
                                    </td>
                                    <td><?php echo formatCurrency($order->total_price); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order->status === 'pending' ? 'warning' : 'success'; ?>">
                                            <?php echo ucfirst($order->status); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
