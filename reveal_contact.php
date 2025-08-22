<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'buyer' || !isset($_POST['property_id'])) {
    header("Location: login_register.php");
    exit;
}

$user_email = $_SESSION['userEmail'];
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$user_id = $stmt_user->get_result()->fetch_assoc()['id'];

$property_id = $_POST['property_id'];
$amount = 50; // The fee to reveal

// Check if the user has already unlocked this property
$stmt_check = $conn->prepare("SELECT id FROM unlocked_properties WHERE user_id = ? AND property_id = ?");
$stmt_check->bind_param("ii", $user_id, $property_id);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'unlocked', 'message' => 'Contact already revealed.']);
    exit;
}

// --- Payment Gateway Integration Logic Goes Here ---
// Example with a placeholder for a payment gateway (e.g., Stripe)
try {
    // 1. Initiate a payment transaction with your chosen payment gateway.
    // This would typically involve sending a request to the gateway's API with the amount, user ID, and property ID.
    // The gateway would return a transaction ID or a payment confirmation.
    $transaction_status = 'completed'; // Assume payment is successful for this example
    $transaction_id = 'txn_' . uniqid(); // A unique ID from the payment gateway

    if ($transaction_status === 'completed') {
        // 2. Record the successful transaction in your database
        $stmt_trans = $conn->prepare("INSERT INTO transactions (user_id, property_id, amount, status, transaction_id) VALUES (?, ?, ?, ?, ?)");
        $stmt_trans->bind_param("iisis", $user_id, $property_id, $amount, $transaction_status, $transaction_id);
        $stmt_trans->execute();

        // 3. Record that the property has been unlocked for this user
        $stmt_unlock = $conn->prepare("INSERT INTO unlocked_properties (user_id, property_id) VALUES (?, ?)");
        $stmt_unlock->bind_param("ii", $user_id, $property_id);
        $stmt_unlock->execute();

        // 4. Fetch and return the contact details to the frontend
        $stmt_contact = $conn->prepare("SELECT contact_name, contact_phone, contact_email FROM properties WHERE id = ? LIMIT 1");
        $stmt_contact->bind_param("i", $property_id);
        $stmt_contact->execute();
        $contact_info = $stmt_contact->get_result()->fetch_assoc();

        echo json_encode([
            'status' => 'success',
            'contact' => $contact_info
        ]);
        exit;
    } else {
        echo json_encode(['status' => 'failed', 'message' => 'Payment failed.']);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'An error occurred.']);
    exit;
}
?>