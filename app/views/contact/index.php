<?php
$title = 'Contato - DbKids';
ob_start();
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Entre em Contato</h2>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/contato/enviar">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Assunto *</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Mensagem *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Informações de Contato</h5>
                    <p>
                        <i class="fas fa-envelope"></i> contato@dbkids.com<br>
                        <i class="fas fa-phone"></i> (11) 9999-9999<br>
                        <i class="fas fa-map-marker-alt"></i> São Paulo, SP
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>Horário de Atendimento</h5>
                    <p>
                        Segunda a Sexta: 9h às 18h<br>
                        Sábado: 9h às 13h<br>
                        Domingo: Fechado
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_PATH . '/views/layouts/base.php';
?>
