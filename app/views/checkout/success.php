<?php
$title = 'Pedido Confirmado - DbKids';
ob_start();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
            </div>

            <h2 class="mb-3">Pedido Confirmado!</h2>
            <p class="text-muted mb-4">
                Obrigado pela sua compra. Seu pedido foi criado com sucesso.
            </p>

            <?php if (isset($_GET['order_id'])): ?>
                <div class="alert alert-info mb-4">
                    <strong>ID do Pedido:</strong> #<?php echo escape($_GET['order_id']); ?>
                </div>
            <?php endif; ?>

            <p class="mb-4">
                Você receberá um email de confirmação com os detalhes do seu pedido e informações de rastreamento.
            </p>

            <div class="d-flex gap-2 justify-content-center">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home"></i> Voltar para Home
                </a>
                <a href="/meus-pedidos" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Ver Meus Pedidos
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
