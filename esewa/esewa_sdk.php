<?php
class eSewa {
    // These keys are used for HMAC signature generation on the server side
    private $product_code = "EPAYTEST"; // Use EPAYTEST for sandbox
    private $secret_key = "8gBm/:&EnhH.1/q"; // Secret Key for UAT (test)

    public function generateSignature($total_amount, $transaction_uuid, $product_code) {
        $message = "total_amount={$total_amount},transaction_uuid={$transaction_uuid},product_code={$product_code}";
        $signature = hash_hmac('sha256', $message, $this->secret_key, true);
        return base64_encode($signature);
    }
}
?>