<?php
$title = 'Configurações da Loja - DbKids';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-cog"></i> Configurações da Loja</h2>
            <p class="text-muted">Personalize sua loja infantil</p>
        </div>
    </div>

    <form method="POST" action="/admin/configuracoes/atualizar" enctype="multipart/form-data">
        <!-- Informações da Loja -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-store"></i> Informações da Loja</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="store_name" class="form-label">Nome da Loja</label>
                        <input type="text" class="form-control" id="store_name" name="store_name" value="<?php echo escape($settings['store_name'] ?? 'DbKids'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="store_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="store_email" name="store_email" value="<?php echo escape($settings['store_email'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="store_phone" class="form-label">Telefone</label>
                        <input type="tel" class="form-control" id="store_phone" name="store_phone" value="<?php echo escape($settings['store_phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="store_address" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="store_address" name="store_address" value="<?php echo escape($settings['store_address'] ?? ''); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="store_description" class="form-label">Descrição da Loja</label>
                    <textarea class="form-control" id="store_description" name="store_description" rows="3"><?php echo escape($settings['store_description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="promo_text" class="form-label">Texto de Promoção (Barra Topo)</label>
                    <input type="text" class="form-control" id="promo_text" name="promo_text" value="<?php echo escape($settings['promo_text'] ?? 'ENVIO GRÁTIS ACIMA DE R$150,00'); ?>" placeholder="Ex: ENVIO GRÁTIS ACIMA DE R$150,00">
                    <small class="text-muted">Este texto aparece na barra azul no topo do site</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="banner_text" class="form-label">Texto do Banner Lateral</label>
                        <input type="text" class="form-control" id="banner_text" name="banner_text" value="<?php echo escape($settings['banner_text'] ?? 'aqui'); ?>" placeholder="Ex: aqui, NOVO, SALE">
                        <small class="text-muted">Texto que aparece no banner ao lado do logo</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="banner_color" class="form-label">Cor do Banner</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="banner_color" name="banner_color" value="<?php echo escape($settings['banner_color'] ?? '#4ECDC4'); ?>">
                            <span class="input-group-text"><?php echo escape($settings['banner_color'] ?? '#4ECDC4'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo da Loja</label>
                    <div class="mb-2">
                        <?php if (!empty($settings['store_logo'])): ?>
                            <img src="<?php echo escape($settings['store_logo']); ?>" alt="Logo" style="max-width: 150px; max-height: 100px;">
                        <?php endif; ?>
                    </div>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    <small class="text-muted">Formatos aceitos: PNG, JPG, GIF (máx. 2MB)</small>
                </div>
            </div>
        </div>

        <!-- Tema e Cores -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-palette"></i> Tema e Cores</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="theme" class="form-label">Tema da Loja</label>
                    <select class="form-control" id="theme" name="theme">
                        <option value="colorful" <?php echo ($settings['theme'] ?? '') === 'colorful' ? 'selected' : ''; ?>>Colorido (Infantil)</option>
                        <option value="pastel" <?php echo ($settings['theme'] ?? '') === 'pastel' ? 'selected' : ''; ?>>Pastel (Suave)</option>
                        <option value="vibrant" <?php echo ($settings['theme'] ?? '') === 'vibrant' ? 'selected' : ''; ?>>Vibrante (Energético)</option>
                        <option value="modern" <?php echo ($settings['theme'] ?? '') === 'modern' ? 'selected' : ''; ?>>Moderno (Limpo)</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="primary_color" class="form-label">Cor Primária</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="<?php echo escape($settings['primary_color'] ?? '#FF6B9D'); ?>">
                            <span class="input-group-text"><?php echo escape($settings['primary_color'] ?? '#FF6B9D'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="secondary_color" class="form-label">Cor Secundária</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color" value="<?php echo escape($settings['secondary_color'] ?? '#FFC75F'); ?>">
                            <span class="input-group-text"><?php echo escape($settings['secondary_color'] ?? '#FFC75F'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="accent_color" class="form-label">Cor de Destaque</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="accent_color" name="accent_color" value="<?php echo escape($settings['accent_color'] ?? '#845EC2'); ?>">
                            <span class="input-group-text"><?php echo escape($settings['accent_color'] ?? '#845EC2'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <strong>Dica:</strong> As cores escolhidas serão aplicadas em toda a loja para criar uma identidade visual consistente.
                </div>
            </div>
        </div>

        <!-- Mercado Pago -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-credit-card"></i> Mercado Pago (Pagamentos)</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong><i class="fas fa-exclamation-triangle"></i> Importante:</strong> 
                    Configure suas credenciais do Mercado Pago para aceitar pagamentos online.
                    <a href="https://www.mercadopago.com.br/developers/panel/app" target="_blank" class="alert-link">
                        Obter credenciais <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>

                <div class="mb-3">
                    <label for="mercado_pago_token" class="form-label">Access Token (Produção)</label>
                    <input type="text" class="form-control" id="mercado_pago_token" name="mercado_pago_token" 
                           value="<?php echo escape($settings['MERCADO_PAGO_TOKEN'] ?? ''); ?>" 
                           placeholder="APP_USR-XXXXXXXXXXXXXXXXX">
                    <small class="text-muted">Token de acesso para processar pagamentos</small>
                </div>

                <div class="mb-3">
                    <label for="mercado_pago_public_key" class="form-label">Public Key (Produção)</label>
                    <input type="text" class="form-control" id="mercado_pago_public_key" name="mercado_pago_public_key" 
                           value="<?php echo escape($settings['MERCADO_PAGO_PUBLIC_KEY'] ?? ''); ?>" 
                           placeholder="APP_USR-XXXXXXXXXXXXXXXXX">
                    <small class="text-muted">Chave pública para o frontend</small>
                </div>

                <div class="mb-3">
                    <label for="mercado_pago_webhook_secret" class="form-label">Webhook Secret</label>
                    <input type="text" class="form-control" id="mercado_pago_webhook_secret" name="mercado_pago_webhook_secret" 
                           value="<?php echo escape($settings['MERCADO_PAGO_WEBHOOK_SECRET'] ?? ''); ?>" 
                           placeholder="XXXXXXXXXXXXXXXXX">
                    <small class="text-muted">Assinatura secreta para validar webhooks</small>
                </div>

                <div class="alert alert-info">
                    <strong>URL do Webhook:</strong> 
                    <code>https://seu-dominio.com/checkout/webhook</code>
                    <br>
                    <small>Configure esta URL no painel do Mercado Pago para receber notificações de pagamento.</small>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Salvar Configurações
            </button>
            <a href="/admin/dashboard" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </form>
</div>

<style>
    .form-control-color {
        height: 45px;
        cursor: pointer;
    }
</style>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/admin.php';
?>
