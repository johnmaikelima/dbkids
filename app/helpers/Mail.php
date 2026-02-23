<?php

class Mail {
    private $smtpHost;
    private $smtpPort;
    private $smtpUser;
    private $smtpPassword;
    private $fromName;
    private $fromEmail;

    public function __construct() {
        $this->smtpHost = Env::get('MAIL_HOST', 'smtp.gmail.com');
        $this->smtpPort = Env::get('MAIL_PORT', 587);
        $this->smtpUser = Env::get('MAIL_USERNAME');
        $this->smtpPassword = Env::get('MAIL_PASSWORD');
        $this->fromEmail = Env::get('MAIL_FROM');
        $this->fromName = Env::get('MAIL_FROM_NAME', 'DbKids');
    }

    /**
     * Enviar email de confirmação de pedido
     */
    public function sendOrderConfirmation($customerEmail, $customerName, $orderId, $orderNumber) {
        $subject = "Pedido Confirmado - DbKids #" . $orderNumber;
        
        $body = "
            <h2>Pedido Confirmado!</h2>
            <p>Olá <strong>{$customerName}</strong>,</p>
            <p>Seu pedido foi criado com sucesso!</p>
            <p><strong>ID do Pedido:</strong> #{$orderNumber}</p>
            <p>Você pode acompanhar seu pedido em: <a href='" . Env::get('APP_URL') . "/meus-pedidos?email=" . urlencode($customerEmail) . "'>Ver Meus Pedidos</a></p>
            <p>Obrigado por comprar conosco!</p>
            <p>Atenciosamente,<br>DbKids</p>
        ";

        return $this->send($customerEmail, $subject, $body);
    }

    /**
     * Enviar email de contato
     */
    public function sendContactMessage($name, $email, $message) {
        $subject = "Nova Mensagem de Contato - DbKids";
        
        $body = "
            <h2>Nova Mensagem de Contato</h2>
            <p><strong>Nome:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Mensagem:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
        ";

        return $this->send(Env::get('MAIL_FROM'), $subject, $body);
    }

    /**
     * Enviar email genérico
     */
    public function send($to, $subject, $body) {
        // Validar configurações
        if (!$this->smtpUser || !$this->smtpPassword) {
            error_log("Email não configurado. Verifique .env");
            return false;
        }

        // Headers do email
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $headers .= "Reply-To: {$this->fromEmail}\r\n";

        // Configurações SMTP
        $smtpParams = "-S smtp={$this->smtpHost}:{$this->smtpPort}";
        $smtpParams .= " -S smtp_use_tls=yes";
        $smtpParams .= " -S smtp_auth_user={$this->smtpUser}";
        $smtpParams .= " -S smtp_auth_password={$this->smtpPassword}";

        // Tentar enviar com mail()
        try {
            $result = @mail($to, $subject, $body, $headers, $smtpParams);
            
            if ($result) {
                error_log("Email enviado para {$to}");
                return true;
            } else {
                error_log("Falha ao enviar email para {$to}");
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
            return false;
        }
    }
}
?>
