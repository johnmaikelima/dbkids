<?php
$title = 'Dashboard';
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-3">
        <a href="/admin/produtos" style="text-decoration: none;">
            <div class="card bg-primary text-white h-100" style="cursor: pointer; transition: transform 0.3s;">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-box"></i> Total de Produtos</h6>
                    <h2><?php echo $total_products ?? 0; ?></h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/clientes" style="text-decoration: none;">
            <div class="card bg-success text-white h-100" style="cursor: pointer; transition: transform 0.3s;">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-users"></i> Total de Clientes</h6>
                    <h2><?php echo $total_customers ?? 0; ?></h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/pedidos" style="text-decoration: none;">
            <div class="card bg-warning text-white h-100" style="cursor: pointer; transition: transform 0.3s;">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-hourglass-half"></i> Pedidos Pendentes</h6>
                    <h2><?php echo $pending_orders ?? 0; ?></h2>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/pedidos" style="text-decoration: none;">
            <div class="card bg-info text-white h-100" style="cursor: pointer; transition: transform 0.3s;">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-dollar-sign"></i> Total de Vendas</h6>
                    <h2><?php echo formatCurrency($total_sales ?? 0); ?></h2>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Menu de Atalhos -->
<div class="row mb-4">
    <div class="col-md-12">
        <h5 class="mb-3"><i class="fas fa-link"></i> Atalhos Rápidos</h5>
        <div class="row">
            <div class="col-md-2 mb-2">
                <a href="/admin/produtos/novo" class="btn btn-primary w-100">
                    <i class="fas fa-plus"></i> Novo Produto
                </a>
            </div>
            <div class="col-md-2 mb-2">
                <a href="/admin/categorias" class="btn btn-secondary w-100">
                    <i class="fas fa-folder"></i> Categorias
                </a>
            </div>
            <div class="col-md-2 mb-2">
                <a href="/admin/usuarios" class="btn btn-info w-100">
                    <i class="fas fa-user-tie"></i> Usuários
                </a>
            </div>
            <div class="col-md-2 mb-2">
                <a href="/admin/configuracoes" class="btn btn-warning w-100">
                    <i class="fas fa-cog"></i> Configurações
                </a>
            </div>
            <div class="col-md-2 mb-2">
                <a href="/" class="btn btn-success w-100">
                    <i class="fas fa-eye"></i> Ver Loja
                </a>
            </div>
            <div class="col-md-2 mb-2">
                <a href="/admin/logout" class="btn btn-danger w-100">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pedidos Recentes</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders ?? [] as $order): ?>
                            <tr>
                                <td><?php echo escape($order->order_number); ?></td>
                                <td><?php echo escape($order->customer_name); ?></td>
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
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Clientes Recentes</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_customers ?? [] as $customer): ?>
                            <tr>
                                <td><?php echo escape($customer->name); ?></td>
                                <td><?php echo escape($customer->email); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($customer->created_at)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
