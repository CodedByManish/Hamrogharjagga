<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'seller') {
    header("Location: login_register.php");
    exit;
}

$message = '';

$user_email = $_SESSION['userEmail'];
$stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$seller_id = $user_result->fetch_assoc()['id'];

// --- Handle Add Property ---
if (isset($_POST['add_property'])) {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $municipality = trim($_POST['municipality']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $size = trim($_POST['size']);
    $type = trim($_POST['type']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $contact_name = trim($_POST['contact_name']);
    $contact_phone = trim($_POST['contact_phone']);
    $contact_email = trim($_POST['contact_email']);
    
    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageError = $image['error'];
    
    if ($imageError === 0) {
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $imageMimeType = mime_content_type($imageTmpName);
        $allowed_exts = array('jpg', 'jpeg', 'png');
        $allowed_mimes = array('image/jpeg', 'image/png');

        if (in_array($imageExt, $allowed_exts) && in_array($imageMimeType, $allowed_mimes)) {
            $fileNameNew = uniqid('', true) . "." . $imageExt;
            $fileDestination = 'uploads/' . $fileNameNew;
            
            if (!is_dir('uploads')) { mkdir('uploads'); }
            if (move_uploaded_file($imageTmpName, $fileDestination)) {
                $stmt = $conn->prepare("INSERT INTO properties (seller_id, title, price, municipality, district, province, size, type, category, image, description, contact_name, contact_phone, contact_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isisssssssssss", $seller_id, $title, $price, $municipality, $district, $province, $size, $type, $category, $fileDestination, $description, $contact_name, $contact_phone, $contact_email);
                if ($stmt->execute()) {
                    $message = "<div class='bg-green-100 text-green-700 p-3 rounded'>Property added successfully!</div>";
                } else {
                    $message = "<div class='bg-red-100 text-red-700 p-3 rounded'>Error: " . $conn->error . "</div>";
                }
            } else { $message = "<div class='bg-red-100 text-red-700 p-3 rounded'>There was an error uploading your file.</div>"; }
        } else { $message = "<div class='bg-red-100 text-red-700 p-3 rounded'>You cannot upload files of this type!</div>"; }
    } else { $message = "<div class='bg-red-100 text-red-700 p-3 rounded'>Please upload an image for the property.</div>"; }
}

// --- Handle Delete Property ---
if (isset($_POST['delete_property'])) {
    $property_id_to_delete = $_POST['property_id'];
    $stmt_delete = $conn->prepare("DELETE FROM properties WHERE id = ? AND seller_id = ?");
    $stmt_delete->bind_param("ii", $property_id_to_delete, $seller_id);
    if ($stmt_delete->execute()) {
        $message = "<div class='bg-green-100 text-green-700 p-3 rounded'>Property deleted successfully!</div>";
    } else {
        $message = "<div class='bg-red-100 text-red-700 p-3 rounded'>Error deleting property.</div>";
    }
}

// --- Handle Update Property ---
if (isset($_POST['update_property'])) {
    $property_id_to_update = $_POST['property_id'];
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);
    $municipality = trim($_POST['municipality']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $size = trim($_POST['size']);
    $type = trim($_POST['type']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $contact_name = trim($_POST['contact_name']);
    $contact_phone = trim($_POST['contact_phone']);
    $contact_email = trim($_POST['contact_email']);
    
    // Check for new image upload
    $new_image = $_FILES['new_image'];
    $imagePath = $_POST['current_image']; // Use existing image path by default

    if ($new_image['error'] === 0) {
        $imageExt = strtolower(pathinfo($new_image['name'], PATHINFO_EXTENSION));
        $imageMimeType = mime_content_type($new_image['tmp_name']);
        $allowed_exts = array('jpg', 'jpeg', 'png');
        $allowed_mimes = array('image/jpeg', 'image/png');

        if (in_array($imageExt, $allowed_exts) && in_array($imageMimeType, $allowed_mimes)) {
            $fileNameNew = uniqid('', true) . "." . $imageExt;
            $fileDestination = 'uploads/' . $fileNameNew;
            if (move_uploaded_file($new_image['tmp_name'], $fileDestination)) {
                $imagePath = $fileDestination;
                // Optional: Delete old image from server
                if (file_exists($_POST['current_image'])) {
                    unlink($_POST['current_image']);
                }
            }
        }
    }

    $stmt_update = $conn->prepare("UPDATE properties SET title=?, price=?, municipality=?, district=?, province=?, size=?, type=?, category=?, image=?, description=?, contact_name=?, contact_phone=?, contact_email=? WHERE id = ? AND seller_id = ?");
    $stmt_update->bind_param("sisssssssssssii", $title, $price, $municipality, $district, $province, $size, $type, $category, $imagePath, $description, $contact_name, $contact_phone, $contact_email, $property_id_to_update, $seller_id);
    
    if ($stmt_update->execute()) {
        $message = "<div class='bg-green-100 text-green-700 p-3 rounded'>Property updated successfully!</div>";
    } else {
        $message = "<div class='bg-red-100 text-red-700 p-3 rounded'>Error updating property. " . $conn->error . "</div>";
    }
}

$stmt = $conn->prepare("SELECT * FROM properties WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$properties = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Properties - HamroGharJajja</title>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Your Properties</h1>
        
        <?= $message ?>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <?php if ($properties->num_rows > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ($property = $properties->fetch_assoc()): ?>
                        <div class="border rounded-lg overflow-hidden shadow-sm">
                            <img src="<?= htmlspecialchars($property['image']); ?>" alt="Property Image" class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($property['title']); ?></h3>
                                <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($property['municipality']); ?>, <?= htmlspecialchars($property['district']); ?></p>
                                <p class="text-blue-600 font-bold mt-2">$<?= number_format($property['price']); ?></p>
                                <div class="mt-4 flex justify-between space-x-2">
                                    <a href="edit_property.php?id=<?= $property['id']; ?>" class="flex-1 text-center py-2 px-4 rounded-md text-sm font-medium text-white bg-green-500 hover:bg-green-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form method="POST" action="manage_property.php" class="flex-1">
                                        <input type="hidden" name="property_id" value="<?= $property['id']; ?>">
                                        <button type="submit" name="delete_property" class="w-full py-2 px-4 rounded-md text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition-colors" onclick="return confirm('Are you sure you want to delete this property?');">
                                            <i class="fas fa-trash-alt mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center">You have not listed any properties yet.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>