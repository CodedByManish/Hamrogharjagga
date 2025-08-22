<?php
session_start();
include "db.php";

// ---------------- REGISTER ----------------
if (isset($_POST['register'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $pass    = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role    = $_POST['registerRole'];

    if ($pass !== $confirm) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: login_register.php");
        exit;
    }

    // Hash password before saving
    $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

    // Prevent duplicate emails
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: login_register.php");
        exit;
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $hashedPass, $role);
    if ($stmt->execute()) {
        $_SESSION['userEmail'] = $email;
        $_SESSION['userName']  = $name;
        $_SESSION['userRole']  = $role;
        header("Location: " . ($role === "buyer" ? "find_property.php" : "manage_property.php"));
        exit;
    } else {
        $_SESSION['error'] = "Something went wrong. Try again!";
        header("Location: login_register.php");
        exit;
    }
}

// ---------------- LOGIN ----------------
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $role  = $_POST['loginRole'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=? LIMIT 1");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($pass, $user['password'])) {
            $_SESSION['userEmail'] = $user['email'];
            $_SESSION['userName']  = $user['name'];
            $_SESSION['userRole']  = $user['role'];
            header("Location: " . ($user['role'] === "buyer" ? "find_property.php" : "manage_property.php"));
            exit;
        } else {
            $_SESSION['error'] = "Invalid password!";
            header("Location: login_register.php?form=login"); 
            exit;
        }
    } else {
        $_SESSION['error'] = "User not found or role is incorrect!";
        header("Location: login_register.php?form=login"); 
        exit;
    }
}
?>
