<?php
$title = 'Meus Pedidos - DbKids';
ob_start();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Meus Pedidos</h2>

            <div class="card">
                <div class="card-body">
                    <p class="text-muted text-center mb-4">
                        Digite seu email para visualizar seus pedidos
                    </p>

                    <form method="GET" action="/meus-pedidos">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="seu@email.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-search"></i> Buscar Pedidos
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-muted">
                    <small>Você receberá um email de confirmação com o ID do seu pedido quando fizer uma compra.</small>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
