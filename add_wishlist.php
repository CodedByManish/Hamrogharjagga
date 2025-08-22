<?php
session_start();
include 'db.php';

// Check if the user is a buyer and if a property ID was submitted
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'buyer' || !isset($_POST['property_id'])) {
    header("Location: find_property.php");
    exit;
}

$user_email = $_SESSION['userEmail'];
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$user_id = $stmt_user->get_result()->fetch_assoc()['id'];

$property_id = $_POST['property_id'];

// Check if the property is already in the wishlist
$stmt_check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND property_id = ?");
$stmt_check->bind_param("ii", $user_id, $property_id);
$stmt_check->execute();

if ($stmt_check->get_result()->num_rows > 0) {
    // Property is already in wishlist, redirect back
    header("Location: find_property.php?message=already_added");
    exit;
}

// Add the property to the wishlist
$stmt_insert = $conn->prepare("INSERT INTO wishlist (user_id, property_id) VALUES (?, ?)");
$stmt_insert->bind_param("ii", $user_id, $property_id);

if ($stmt_insert->execute()) {
    header("Location: find_property.php?message=added_to_wishlist");
} else {
    header("Location: find_property.php?message=error");
}

exit;
?>