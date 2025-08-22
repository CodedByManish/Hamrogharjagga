<?php
session_start();
include 'db.php';

// Check if user is a seller and if a property ID is provided in the URL
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'seller' || !isset($_GET['id'])) {
    header("Location: manage_property.php");
    exit;
}

$property_id = $_GET['id'];
$user_email = $_SESSION['userEmail'];
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$seller_id = $stmt_user->get_result()->fetch_assoc()['id'];

// Fetch the property details for the logged-in seller
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ? AND seller_id = ? LIMIT 1");
$stmt->bind_param("ii", $property_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_property.php");
    exit;
}

$property = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Property - HamroGharJajja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 900px;
        }
    </style>
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-landmark text-2xl text-blue-600"></i>
                <a href="manage_property.php" class="text-xl font-bold text-gray-800">Seller Dashboard</a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="manage_property.php" class="flex items-center text-blue-600 font-medium transition-colors">
                    <i class="fas fa-list-ul mr-2"></i>Manage Properties
                </a>
                <a href="sell_property.php" class="flex items-center text-gray-700 hover:text-blue-600 font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Sell Property
                </a>
                <a href="logout.php" class="flex items-center text-gray-700 hover:text-blue-600 font-medium transition-colors">
                    <i class="fas fa-right-from-bracket mr-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-8">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Property</h1>
            <p class="text-gray-500 mb-8">Update the details for your property.</p>

            <form method="POST" action="manage_property.php" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="property_id" value="<?= $property['id']; ?>">
                <input type="hidden" name="current_image" value="<?= $property['image']; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-gray-700 font-medium mb-1">Property Title</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($property['title']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="price" class="block text-gray-700 font-medium mb-1">Price ($)</label>
                        <input type="number" id="price" name="price" value="<?= htmlspecialchars($property['price']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="municipality" class="block text-gray-700 font-medium mb-1">Municipality</label>
                        <input type="text" id="municipality" name="municipality" value="<?= htmlspecialchars($property['municipality']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="district" class="block text-gray-700 font-medium mb-1">District</label>
                        <input type="text" id="district" name="district" value="<?= htmlspecialchars($property['district']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="province" class="block text-gray-700 font-medium mb-1">Province</label>
                        <input type="text" id="province" name="province" value="<?= htmlspecialchars($property['province']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="size" class="block text-gray-700 font-medium mb-1">Size (sq ft)</label>
                        <input type="number" id="size" name="size" value="<?= htmlspecialchars($property['size']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-gray-700 font-medium mb-1">Property Type</label>
                        <select id="type" name="type" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="Residential Building" <?= $property['type'] == 'Residential Building' ? 'selected' : ''; ?>>Residential Building</option>
                            <option value="Commercial Space" <?= $property['type'] == 'Commercial Space' ? 'selected' : ''; ?>>Commercial Space</option>
                            <option value="Agricultural Land" <?= $property['type'] == 'Agricultural Land' ? 'selected' : ''; ?>>Agricultural Land</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="block text-gray-700 font-medium mb-1">Category</label>
                        <select id="category" name="category" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="Building" <?= $property['category'] == 'Building' ? 'selected' : ''; ?>>Building</option>
                            <option value="Land" <?= $property['category'] == 'Land' ? 'selected' : ''; ?>>Land</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-gray-700 font-medium mb-1">Property Description</label>
                    <textarea id="description" name="description" rows="5" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"><?= htmlspecialchars($property['description']); ?></textarea>
                </div>

                <div>
                    <label for="new_image" class="block text-gray-700 font-medium mb-1">Update Property Image (optional)</label>
                    <input type="file" id="new_image" name="new_image" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-2">Current Image:</p>
                    <img src="<?= htmlspecialchars($property['image']); ?>" alt="Current Property Image" class="w-32 h-32 object-cover mt-2 rounded-md">
                </div>

                <h2 class="text-xl font-bold text-gray-800 pt-6">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="contact_name" class="block text-gray-700 font-medium mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="contact_name" name="contact_name" value="<?= htmlspecialchars($property['contact_name']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-gray-700 font-medium mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="text" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($property['contact_phone']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="contact_email" class="block text-gray-700 font-medium mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="contact_email" name="contact_email" value="<?= htmlspecialchars($property['contact_email']); ?>" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <button type="submit" name="update_property" class="w-full py-3 mt-6 text-lg font-bold text-white rounded-xl bg-gradient-to-r from-purple-600 to-indigo-500 hover:from-purple-700 hover:to-indigo-600 transition-all duration-300">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </form>
        </div>
    </main>
</body>
</html>