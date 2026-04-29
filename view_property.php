<?php
// Session fix for localhost redirects
if (PHP_VERSION_ID >= 70300) {
    session_start(['cookie_samesite' => 'Lax']);
} else {
    session_start();
}
include 'db.php';
include 'esewa/esewa_sdk.php';

if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'buyer' || !isset($_GET['id'])) {
    header("Location: find_property.php");
    exit;
}

$property_id = intval($_GET['id']);
$user_email = $_SESSION['userEmail'];

$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$user_id = $stmt_user->get_result()->fetch_assoc()['id'];

$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $property_id);
$stmt->execute();
$property = $stmt->get_result()->fetch_assoc();

if (!$property) {
    echo "Property not found.";
    exit;
}

$stmt_unlocked = $conn->prepare("SELECT id FROM unlocked_properties WHERE user_id = ? AND property_id = ?");
$stmt_unlocked->bind_param("ii", $user_id, $property_id);
$stmt_unlocked->execute();
$is_unlocked = $stmt_unlocked->get_result()->num_rows > 0;

$amount_to_pay = 50;
$total_amount = $amount_to_pay;
// EMBED PROPERTY_ID IN UUID TO KEEP SUCCESS_URL CLEAN
$transaction_uuid = "HGJ-" . $property_id . "-" . uniqid(); 

$esewa = new eSewa();
$signature = $esewa->generateSignature($total_amount, $transaction_uuid, "EPAYTEST");

// CLEAN SUCCESS/FALURE URL
$success_url = "http://localhost/HamroGharJagga/esewa/esewa_success.php";
$failure_url = "http://localhost/HamroGharJagga/esewa/esewa_failure.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($property['title']); ?> - HamroGharJajja</title>
    <?php include('head.php'); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }</style>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="find_property.php" class="text-xl font-bold text-blue-600"><i class="fas fa-home mr-2"></i>HamroGharJajja</a>
            <div class="flex items-center space-x-6">
                <a href="find_property.php" class="text-gray-700 hover:text-blue-600 font-medium"><i class="fas fa-magnifying-glass mr-2"></i>Find Properties</a>
                <a href="wishlist.php" class="text-gray-700 hover:text-blue-600 font-medium"><i class="fas fa-heart mr-2"></i>Wishlist</a>
                <a href="logout.php" class="text-gray-700 hover:text-blue-600 font-medium"><i class="fas fa-right-from-bracket mr-2"></i>Logout</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-8">
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <a href="find_property.php" class="text-blue-600 hover:underline mb-4 inline-block"><i class="fas fa-arrow-left mr-2"></i>Back to properties</a>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden lg:flex lg:space-x-8 p-6">
            <div class="lg:w-1/2">
                <img src="<?= htmlspecialchars($property['image']); ?>" class="w-full rounded-lg shadow-md">
            </div>
            <div class="lg:w-1/2 mt-6 lg:mt-0">
                <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($property['title']); ?></h1>
                <p class="text-gray-500 mb-4"><?= htmlspecialchars($property['municipality'] . ', ' . $property['district']); ?></p>
                <p class="text-blue-600 text-4xl font-extrabold mb-6">Rs. <?= number_format($property['price']); ?></p>
                
                <div class="bg-gray-100 p-6 rounded-lg border border-gray-200">
                    <?php if ($is_unlocked): ?>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Contact Information</h2>
                        <div class="space-y-2 text-gray-700">
                            <p><i class="fas fa-user mr-2 text-blue-500"></i><strong>Name:</strong> <?= htmlspecialchars($property['contact_name']); ?></p>
                            <p><i class="fas fa-phone mr-2 text-blue-500"></i><strong>Phone:</strong> <?= htmlspecialchars($property['contact_phone']); ?></p>
                            <p><i class="fas fa-envelope mr-2 text-blue-500"></i><strong>Email:</strong> <?= htmlspecialchars($property['contact_email']); ?></p>
                        </div>
                    <?php else: ?>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Reveal Contact Information</h2>
                        <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                            <input type="hidden" name="amount" value="50">
                            <input type="hidden" name="tax_amount" value="0">
                            <input type="hidden" name="product_service_charge" value="0">
                            <input type="hidden" name="product_delivery_charge" value="0">
                            <input type="hidden" name="total_amount" value="50">
                            <input type="hidden" name="transaction_uuid" value="<?= $transaction_uuid ?>">
                            <input type="hidden" name="product_code" value="EPAYTEST">
                            <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
                            <input type="hidden" name="signature" value="<?= $signature ?>">
                            <input type="hidden" name="success_url" value="<?= $success_url ?>">
                            <input type="hidden" name="failure_url" value="<?= $failure_url ?>">
                            <button type="submit" class="w-full py-3 font-bold text-white rounded-lg bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 transition-all">
                                <i class="fas fa-unlock mr-2"></i>Pay Rs. 50 with eSewa
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
