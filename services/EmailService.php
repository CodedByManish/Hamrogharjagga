<?php

namespace App\Services;

class EmailService {
    public static function sendOTP(string $toEmail, string $code, string $purpose): bool {
        $subject = ($purpose === 'email_verification') ? "Verify Your Account" : "Reset Your Password";
        $message = "Your verification OTP is: " . $code . ". It expires in 10 minutes.";
        
        $logEntry = "[" . date('Y-m-d H:i:s') . "] TO: $toEmail | SUBJECT: $subject | BODY: $message" . PHP_EOL;
        file_put_contents(__DIR__ . '/../../local_email_log.txt', $logEntry, FILE_APPEND);

        return true; 
    }
}