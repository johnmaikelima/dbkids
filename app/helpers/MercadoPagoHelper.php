<?php

namespace App\Helpers;

use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoHelper {
    
    private $client;
    private $accessToken;

    public function __construct() {
        $this->accessToken = getEnv('MERCADO_PAGO_TOKEN');
        
        if (!$this->accessToken) {
            throw new \Exception('Mercado Pago token não configurado');
        }

        MercadoPagoConfig::setAccessToken($this->accessToken);
        $this->client = new PaymentClient();
    }

    /**
     * Obter opções de parcelamento para um valor
     */
    public function getInstallments($amount, $bin = null) {
        try {
            $url = "https://api.mercadopago.com/v1/payment_methods/installments";
            
            $params = [
                'amount' => $amount,
                'bin' => $bin
            ];

            $ch = curl_init($url . '?' . http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$this->accessToken}",
                "Content-Type: application/json"
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return json_decode($response, true);
            }

            error_log("Erro ao buscar parcelamento: HTTP {$httpCode}");
            return [];

        } catch (\Exception $e) {
            error_log("Erro ao obter parcelamento: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obter detalhes de um pagamento
     */
    public function getPayment($paymentId) {
        try {
            $url = "https://api.mercadopago.com/v1/payments/{$paymentId}";
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$this->accessToken}",
                "Content-Type: application/json"
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return json_decode($response, true);
            }

            error_log("Erro ao buscar pagamento: HTTP {$httpCode}");
            return null;

        } catch (\Exception $e) {
            error_log("Erro ao obter pagamento: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Processar reembolso
     */
    public function refundPayment($paymentId, $amount = null) {
        try {
            $url = "https://api.mercadopago.com/v1/payments/{$paymentId}/refunds";
            
            $data = [];
            if ($amount) {
                $data['amount'] = $amount;
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$this->accessToken}",
                "Content-Type: application/json"
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 201 || $httpCode === 200) {
                error_log("Reembolso processado para pagamento: {$paymentId}");
                return json_decode($response, true);
            }

            error_log("Erro ao processar reembolso: HTTP {$httpCode}");
            return null;

        } catch (\Exception $e) {
            error_log("Erro ao reembolsar: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar pagamentos com filtros
     */
    public function searchPayments($filters = []) {
        try {
            $url = "https://api.mercadopago.com/v1/payments/search";
            
            $query = http_build_query($filters);
            
            $ch = curl_init($url . '?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$this->accessToken}",
                "Content-Type: application/json"
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return json_decode($response, true);
            }

            error_log("Erro ao buscar pagamentos: HTTP {$httpCode}");
            return null;

        } catch (\Exception $e) {
            error_log("Erro ao buscar pagamentos: " . $e->getMessage());
            return null;
        }
    }
}
?>
