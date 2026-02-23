<?php
$title = 'Checkout - DbKids';
ob_start();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <h2 class="mb-4">Finalizar Compra</h2>

            <form method="POST" action="/checkout/processar">
                <!-- Dados do Cliente -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Dados Pessoais</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço de Entrega -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Endereço de Entrega</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Rua, número, complemento" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="state" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="state" name="state" placeholder="SP, RJ, etc" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip_code" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="00000-000" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Frete -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Frete</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="shipping_cost" class="form-label">Custo de Frete</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" id="shipping_cost" name="shipping_cost" step="0.01" value="0" required>
                            </div>
                            <small class="text-muted">Deixe 0 para frete grátis</small>
                        </div>
                    </div>
                </div>

                <!-- Parcelamento -->
                <?php if (!empty($installments)): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Opções de Parcelamento</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php 
                                $count = 0;
                                foreach ($installments as $option): 
                                    if ($count >= 6) break; // Mostrar no máximo 6 opções
                                    $installmentCount = $option['installments'] ?? 1;
                                    $installmentAmount = $option['amount'] ?? $total;
                                ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="installments" 
                                                   id="installment_<?php echo $installmentCount; ?>" 
                                                   value="<?php echo $installmentCount; ?>"
                                                   <?php echo $count === 0 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="installment_<?php echo $installmentCount; ?>">
                                                <strong><?php echo $installmentCount; ?>x</strong> de R$ <?php echo number_format($installmentAmount / $installmentCount, 2, ',', '.'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php $count++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="fas fa-check"></i> Finalizar Compra
                </button>
            </form>
        </div>

        <!-- Resumo do Pedido -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">Resumo do Pedido</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($cart)): ?>
                        <div class="mb-3">
                            <?php foreach ($cart as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>
                                        <strong><?php echo escape($item['name']); ?></strong>
                                        <br>
                                        <small class="text-muted">Qtd: <?php echo $item['quantity']; ?></small>
                                    </span>
                                    <span>R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Subtotal:</strong>
                            <strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Frete:</strong>
                            <strong id="shippingDisplay">R$ 0,00</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5>Total:</h5>
                            <h5 id="totalDisplay">R$ <?php echo number_format($total, 2, ',', '.'); ?></h5>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Carrinho vazio</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Atualizar total com frete
document.getElementById('shipping_cost').addEventListener('change', function() {
    const subtotal = <?php echo $total; ?>;
    const shipping = parseFloat(this.value) || 0;
    const total = subtotal + shipping;
    
    document.getElementById('shippingDisplay').textContent = 'R$ ' + shipping.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalDisplay').textContent = 'R$ ' + total.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
});
</script>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
