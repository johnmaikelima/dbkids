<?php
$title = 'Clientes';
ob_start();
?>

<div class="mb-4">
    <h3>Clientes</h3>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Cidade</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers ?? [] as $customer): ?>
                    <tr>
                        <td><?php echo escape($customer->name); ?></td>
                        <td><?php echo escape($customer->email); ?></td>
                        <td><?php echo escape($customer->phone); ?></td>
                        <td><?php echo escape($customer->city); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($customer->created_at)); ?></td>
                        <td>
                            <a href="/admin/clientes/<?php echo $customer->id; ?>" class="btn btn-sm btn-info">
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
