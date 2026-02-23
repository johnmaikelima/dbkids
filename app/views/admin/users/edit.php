<?php
$title = 'Editar Usuário';
ob_start();
?>

<div class="mb-4">
    <h3>Editar Usuário</h3>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/usuarios/atualizar">
            <input type="hidden" name="id" value="<?php echo $user->id; ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo escape($user->name); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo escape($user->email); ?>" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Função</label>
                <select class="form-control" id="role" name="role">
                    <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="editor" <?php echo $user->role === 'editor' ? 'selected' : ''; ?>>Editor</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Atualizar
                </button>
                <a href="/admin/usuarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
