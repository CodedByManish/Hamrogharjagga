<?php
session_start();
// Check if the user is a seller, otherwise redirect
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'seller') {
    header("Location: login_register.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sell Property - HamroGharJajja</title>
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
                <a href="manage_property.php" class="flex items-center text-gray-700 hover:text-blue-600 font-medium transition-colors">
                    <i class="fas fa-list-ul mr-2"></i>Manage Properties
                </a>
                <a href="sell_property.php" class="flex items-center text-blue-600 font-medium transition-colors">
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6">List a New Property</h1>
            <p class="text-gray-500 mb-8">Fill out the details below to list your property for sale.</p>

            <form method="POST" action="manage_property.php" enctype="multipart/form-data" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-gray-700 font-medium mb-1">Property Title</label>
                        <input type="text" id="title" name="title" placeholder="e.g., Modern 3BHK Apartment" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="price" class="block text-gray-700 font-medium mb-1">Price ($)</label>
                        <input type="number" id="price" name="price" placeholder="e.g., 500000" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="municipality" class="block text-gray-700 font-medium mb-1">Municipality</label>
                        <input type="text" id="municipality" name="municipality" placeholder="e.g., Kathmandu Metropolitan" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="district" class="block text-gray-700 font-medium mb-1">District</label>
                        <input type="text" id="district" name="district" placeholder="e.g., Kathmandu" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="province" class="block text-gray-700 font-medium mb-1">Province</label>
                        <input type="text" id="province" name="province" placeholder="e.g., Bagmati Province" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="size" class="block text-gray-700 font-medium mb-1">Size (sq ft)</label>
                        <input type="number" id="size" name="size" placeholder="e.g., 2500" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-gray-700 font-medium mb-1">Property Type</label>
                        <select id="type" name="type" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Select Type</option>
                            <option value="Residential Building">Residential Building</option>
                            <option value="Commercial Space">Commercial Space</option>
                            <option value="Agricultural Land">Agricultural Land</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="block text-gray-700 font-medium mb-1">Category</label>
                        <select id="category" name="category" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Select Category</option>
                            <option value="Building">Building</option>
                            <option value="Land">Land</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-gray-700 font-medium mb-1">Property Description</label>
                    <textarea id="description" name="description" rows="5" placeholder="Describe your property in detail..." required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label for="image" class="block text-gray-700 font-medium mb-1">Property Images</label>
                    <input type="file" id="image" name="image" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <h2 class="text-xl font-bold text-gray-800 pt-6">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="contact_name" class="block text-gray-700 font-medium mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="contact_name" name="contact_name" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="contact_phone" class="block text-gray-700 font-medium mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="text" id="contact_phone" name="contact_phone" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label for="contact_email" class="block text-gray-700 font-medium mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="contact_email" name="contact_email" required class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <button type="submit" name="add_property" class="w-full py-3 mt-6 text-lg font-bold text-white rounded-xl bg-gradient-to-r from-purple-600 to-indigo-500 hover:from-purple-700 hover:to-indigo-600 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>List Property
                </button>
            </form>
        </div>
    </main>
</body>
</html>