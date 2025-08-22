<?php
session_start();
include 'db.php';

// Check if the user is a buyer and logged in
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'buyer' || !isset($_SESSION['userEmail'])) {
    header("Location: login_register.php");
    exit;
}

$user_email = $_SESSION['userEmail'];

// Get the user's ID from the users table
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_id = $user_result->fetch_assoc()['id'];

// Check for removal request
if (isset($_POST['remove_from_wishlist'])) {
    $property_id_to_remove = $_POST['property_id'];
    $stmt_remove = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND property_id = ?");
    $stmt_remove->bind_param("ii", $user_id, $property_id_to_remove);
    $stmt_remove->execute();
    header("Location: wishlist.php"); // Redirect to refresh the page
    exit;
}

// Fetch properties from the user's wishlist
$stmt = $conn->prepare("SELECT p.* FROM properties p JOIN wishlist w ON p.id = w.property_id WHERE w.user_id = ? ORDER BY w.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wishlist_properties = $stmt->get_result();

// Get the wishlist count for the navbar
$stmt_count = $conn->prepare("SELECT COUNT(*) AS wishlist_count FROM wishlist WHERE user_id = ?");
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$wishlist_count_result = $stmt_count->get_result();
$wishlist_count = $wishlist_count_result->fetch_assoc()['wishlist_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist - HamroGharJajja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="find_property.php" class="text-xl font-bold text-blue-600">
                <i class="fas fa-home mr-2"></i>HamroGharJajja
            </a>

            <div class="flex items-center space-x-6">
                <a href="find_property.php" class="flex items-center text-gray-700 hover:text-blue-600 font-medium">
                    <i class="fas fa-magnifying-glass mr-2"></i>Find Properties
                </a>
                <a href="wishlist.php" class="flex items-center text-blue-600 font-medium relative">
                    <i class="fas fa-heart mr-2"></i>Wishlist
                    <?php if ($wishlist_count > 0): ?>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"><?= $wishlist_count ?></span>
                    <?php endif; ?>
                </a>
                <a href="logout.php" class="flex items-center text-gray-700 hover:text-blue-600 font-medium">
                    <i class="fas fa-right-from-bracket mr-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">My Wishlist</h1>

        <?php if ($wishlist_properties->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($property = $wishlist_properties->fetch_assoc()): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden relative">
                        <img src="<?= htmlspecialchars($property['image']); ?>" alt="<?= htmlspecialchars($property['title']); ?>" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            <?= htmlspecialchars($property['category']); ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($property['title']); ?></h3>
                            <div class="flex items-center text-gray-500 mb-2">
                                <i class="fas fa-location-dot mr-2 text-sm"></i>
                                <span><?= htmlspecialchars($property['municipality']) . ', ' . htmlspecialchars($property['district']) . ', ' . htmlspecialchars($property['province']); ?></span>
                            </div>
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-2xl font-bold text-blue-600">$<?= number_format($property['price']); ?></span>
                                <span class="text-gray-500 text-sm"><?= number_format($property['size']); ?> sq ft</span>
                            </div>
                            <p class="text-gray-500 text-sm mb-4"><?= htmlspecialchars($property['type']); ?></p>

                            <form method="POST" action="wishlist.php">
                                <input type="hidden" name="property_id" value="<?= $property['id']; ?>">
                                <button type="submit" name="remove_from_wishlist" class="w-full block text-center py-3 font-bold text-red-500 rounded-xl bg-red-100 hover:bg-red-200 transition-all duration-300">
                                    <i class="fas fa-trash-can mr-2"></i>Remove from Wishlist
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center p-12 bg-white rounded-xl shadow-lg">
                <i class="fas fa-heart-crack text-6xl text-gray-400 mb-4"></i>
                <p class="text-lg text-gray-500 font-medium">Your wishlist is empty.</p>
                <a href="find_property.php" class="mt-4 inline-block text-blue-600 font-bold hover:underline">Start adding properties now!</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>