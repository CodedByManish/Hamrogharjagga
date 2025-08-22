<?php
session_start();
include 'db.php';

// Check if the user is a buyer and required GET parameters are set
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'buyer' || !isset($_GET['property_id'])) {
    header("Location: find_property.php");
    exit;
}

$property_id = $_GET['property_id'];

// Add a simple error message to a session variable
$_SESSION['error_message'] = "Payment failed or was canceled. Please try again.";

// Redirect back to the view property page
header("Location: view_property.php?id=" . $property_id);
exit;
?>