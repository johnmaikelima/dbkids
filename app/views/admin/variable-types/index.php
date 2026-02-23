<?php
$title = 'Tipos de Variáveis';
ob_start();
?>

<div class="mb-4">
    <h3>Gerenciar Tipos de Variáveis</h3>
    <p class="text-muted">Configure os tipos de variáveis (Tamanho, Cor, etc.) e seus valores</p>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Criar Novo Tipo de Variável</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="/admin/tipos-variaveis/criar">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nome do Tipo</label>
                        <input type="text" class="form-control" name="name" placeholder="Ex: Tamanho, Cor, Material" required>
                        <small class="text-muted">Exemplos: Tamanho, Cor, Material, Marca</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Criar Tipo
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($types)): ?>
    <?php foreach ($types as $type): ?>
        <?php 
        $variableType = new VariableType();
        $values = $variableType->getValues($type->id);
        ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo escape($type->name); ?></h5>
                <form method="POST" action="/admin/tipos-variaveis/deletar" style="display: inline;">
                    <input type="hidden" name="type_id" value="<?php echo $type->id; ?>">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deletar este tipo? Todas as variações associadas serão deletadas.')">
                        <i class="fas fa-trash"></i> Deletar Tipo
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Adicionar Novo Valor</h6>
                    <form method="POST" action="/admin/tipos-variaveis/adicionar-valor">
                        <input type="hidden" name="type_id" value="<?php echo $type->id; ?>">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="value" placeholder="Ex: P, M, G (para Tamanho) ou Vermelho, Azul (para Cor)" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Adicionar Valor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (!empty($values)): ?>
                    <h6>Valores Cadastrados</h6>
                    <div class="list-group">
                        <?php foreach ($values as $value): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?php echo escape($value->value); ?></span>
                                <form method="POST" action="/admin/tipos-variaveis/deletar-valor" style="display: inline;">
                                    <input type="hidden" name="value_id" value="<?php echo $value->id; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deletar este valor?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        Nenhum valor cadastrado para este tipo
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Nenhum tipo de variável cadastrado. Crie um novo tipo acima.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
