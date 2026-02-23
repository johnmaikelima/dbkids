<?php
$title = 'Novo Usuário';
ob_start();
?>

<div class="mb-4">
    <h3>Novo Usuário Admin</h3>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/usuarios/salvar">
            <div class="mb-3">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha *</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Função</label>
                <select class="form-control" id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Salvar
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
