<?php
$title = 'Usuários';
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Usuários Admin</h3>
    <a href="/admin/usuarios/novo" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Usuário
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Função</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users ?? [] as $user): ?>
                    <tr>
                        <td><?php echo escape($user->name); ?></td>
                        <td><?php echo escape($user->email); ?></td>
                        <td><?php echo ucfirst($user->role); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($user->created_at)); ?></td>
                        <td>
                            <a href="/admin/usuarios/editar/<?php echo $user->id; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="/admin/usuarios/deletar/<?php echo $user->id; ?>" style="display:inline;">
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
