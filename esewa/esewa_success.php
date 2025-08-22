<?php
session_start();
include 'db.php';

// Check if the user is a buyer and required GET parameters are set
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'buyer' || !isset($_GET['property_id']) || !isset($_GET['pid']) || !isset($_GET['data'])) {
    header("Location: find_property.php");
    exit;
}

$property_id = $_GET['property_id'];
$user_email = $_SESSION['userEmail'];
$transaction_uuid = $_GET['pid'];

// Fetch user ID
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$user_id = $stmt_user->get_result()->fetch_assoc()['id'];

// Decode the Base64 response from eSewa
$decoded_data = base64_decode($_GET['data']);
$response = json_decode($decoded_data, true);

// Verify the transaction status and UUID
if (isset($response['status']) && $response['status'] === 'COMPLETE' && isset($response['transaction_uuid']) && $response['transaction_uuid'] === $transaction_uuid) {
    
    // Check if the property is already unlocked to prevent duplicate entries
    $stmt_check = $conn->prepare("SELECT id FROM unlocked_properties WHERE user_id = ? AND property_id = ?");
    $stmt_check->bind_param("ii", $user_id, $property_id);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows === 0) {
        
        // Insert record into unlocked_properties table
        $stmt_insert = $conn->prepare("INSERT INTO unlocked_properties (user_id, property_id) VALUES (?, ?)");
        $stmt_insert->bind_param("ii", $user_id, $property_id);
        $stmt_insert->execute();
        
        $_SESSION['success_message'] = "Payment successful! The contact information for this property has been unlocked.";
    }

    header("Location: view_property.php?id=" . $property_id);
    exit;

} else {
    // Transaction failed or details do not match
    $_SESSION['error_message'] = "Payment verification failed. Please try again or contact support.";
    header("Location: view_property.php?id=" . $property_id);
    exit;
}
?>