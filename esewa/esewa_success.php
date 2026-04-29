<?php
if (PHP_VERSION_ID >= 70300) {
    session_start(['cookie_samesite' => 'Lax']);
} else {
    session_start();
}
include '../db.php';

// --- DEBUGGING & VALIDATION ---
if (!isset($_GET['data']) || !isset($_SESSION['userRole'])) {
    echo "<h2>Payment Redirect Debug Info:</h2>";
    echo "GET Data: " . (isset($_GET['data']) ? "Received" : "MISSING") . "<br>";
    echo "Session Role: " . ($_SESSION['userRole'] ?? "MISSING (Session Lost)") . "<br>";
    echo "<p>If Session is missing, ensure you use <b>localhost</b> consistently.</p>";
    echo "<a href='../find_property.php'>Return to Home</a>";
    exit;
}

// Decode response
$decoded_data = base64_decode($_GET['data']);
$response = json_decode($decoded_data, true);

$status           = $response['status'] ?? '';
$transaction_uuid = $response['transaction_uuid'] ?? '';
$total_amount     = str_replace(',', '', ($response['total_amount'] ?? 0));
$ref_id           = $response['transaction_code'] ?? '';

// EXTRACT PROPERTY_ID FROM UUID (Format: HGJ-ID-UNIQUE)
$uuid_parts = explode('-', $transaction_uuid);
$property_id = isset($uuid_parts[1]) ? intval($uuid_parts[1]) : 0;

$user_email = $_SESSION['userEmail'];
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$user_res = $stmt_user->get_result()->fetch_assoc();
$user_id = $user_res['id'] ?? 0;

if (strtoupper($status) === 'COMPLETE' && $property_id > 0 && $user_id > 0) {
    $conn->begin_transaction();
    try {
        // 1. Transaction Table
        $stmt_trans = $conn->prepare("INSERT INTO transactions (user_id, property_id, amount, status, transaction_id) VALUES (?, ?, ?, ?, ?)");
        $stmt_trans->bind_param("iisss", $user_id, $property_id, $total_amount, $status, $ref_id);
        $stmt_trans->execute();

        // 2. Unlock Table
        $stmt_unlock = $conn->prepare("INSERT IGNORE INTO unlocked_properties (user_id, property_id) VALUES (?, ?)");
        $stmt_unlock->bind_param("ii", $user_id, $property_id);
        $stmt_unlock->execute();

        $conn->commit();
        header("Location: ../view_property.php?id=" . $property_id);
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("Database Error: " . $e->getMessage());
    }
} else {
    die("Payment failed or invalid data received. Status: $status, Property: $property_id");
}
?>