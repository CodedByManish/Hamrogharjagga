<?php 
 session_start();
 include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HamroGharJajja - Choose your journey</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #a7bfe8, #6190e8);
        }
        .form-container {
            transition: opacity 0.3s ease-in-out;
        }
        .form-container.hidden {
            opacity: 0;
            height: 0;
            overflow: hidden;
        }
        .active-tab {
            background-color: #6190e8;
            color: white;
            border-radius: 9999px;
            font-weight: 600;
        }
        .inactive-tab {
            background-color: transparent;
            color: #4a5568;
        }
        input[type="radio"]:checked + label {
            background-color: #e2e8f0;
            border-color: #6190e8;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="absolute top-4 left-4">
        <a href="index.php" class="bg-white p-2 rounded-full shadow-lg text-gray-700">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md text-center">
        <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                <i class="fas fa-home text-4xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-blue-600 mb-1">HamroGharJajja</h1>
            <p class="text-gray-500 text-sm">Choose your journey</p>
        </div>

        <div class="flex justify-center p-1 bg-gray-100 rounded-full mb-6">
            <button id="login-tab" class="flex-1 py-2 px-4 text-sm font-semibold rounded-full transition-all duration-300 active-tab">Login</button>
            <button id="register-tab" class="flex-1 py-2 px-4 text-sm font-semibold rounded-full transition-all duration-300 inactive-tab">Register</button>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form id="login-form" method="POST" action="auth.php" class="space-y-4 form-container">
            <input type="hidden" name="login" value="1">

            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="email" name="email" placeholder="Email Address" required class="w-full p-3 pl-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
            </div>

            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="password" id="login-password" name="password" placeholder="Password" required class="w-full p-3 pl-12 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer" onclick="togglePassword('login-password', 'login-eye-icon')">
                    <i id="login-eye-icon" class="fas fa-eye-slash"></i>
                </span>
            </div>

            <p class="text-gray-500 font-semibold mt-6 mb-2">What are you here for?</p>
            <div class="grid grid-cols-2 gap-4">
                <input type="radio" id="login-buyer" name="loginRole" value="buyer" class="hidden" required>
                <label for="login-buyer" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-colors duration-200 hover:border-blue-500">
                    <i class="fas fa-house-chimney text-3xl text-gray-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">I want to Buy Property</span>
                </label>
                <input type="radio" id="login-seller" name="loginRole" value="seller" class="hidden" required>
                <label for="login-seller" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-colors duration-200 hover:border-blue-500">
                    <i class="fas fa-briefcase text-3xl text-gray-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">I want to Sell Property</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 mt-6 text-lg font-bold text-white rounded-xl bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 transition-all duration-300">
                <i class="fas fa-arrow-right-to-bracket mr-2"></i>Login
            </button>
        </form>

        <form id="register-form" method="POST" action="auth.php" class="space-y-4 form-container hidden">
            <input type="hidden" name="register" value="1">

            <div class="relative">
                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="name" placeholder="Full Name" required class="w-full p-3 pl-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
            </div>

            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="email" name="email" placeholder="Email Address" required class="w-full p-3 pl-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
            </div>

            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="password" id="register-password" name="password" placeholder="Password" required class="w-full p-3 pl-12 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer" onclick="togglePassword('register-password', 'register-eye-icon')">
                    <i id="register-eye-icon" class="fas fa-eye-slash"></i>
                </span>
            </div>

            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required class="w-full p-3 pl-12 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 text-sm">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer" onclick="togglePassword('confirm-password', 'confirm-eye-icon')">
                    <i id="confirm-eye-icon" class="fas fa-eye-slash"></i>
                </span>
            </div>

            <p class="text-gray-500 font-semibold mt-6 mb-2">What are you here for?</p>
            <div class="grid grid-cols-2 gap-4">
                <input type="radio" id="register-buyer" name="registerRole" value="buyer" class="hidden" required>
                <label for="register-buyer" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-colors duration-200 hover:border-blue-500">
                    <i class="fas fa-house-chimney text-3xl text-gray-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">I want to Buy Property</span>
                </label>
                <input type="radio" id="register-seller" name="registerRole" value="seller" class="hidden" required>
                <label for="register-seller" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-colors duration-200 hover:border-blue-500">
                    <i class="fas fa-briefcase text-3xl text-gray-500 mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">I want to Sell Property</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 mt-6 text-lg font-bold text-white rounded-xl bg-gradient-to-r from-purple-600 to-indigo-500 hover:from-purple-700 hover:to-indigo-600 transition-all duration-300">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </button>
        </form>
    </div>

    <script>
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        function showLoginForm() {
            loginTab.classList.add('active-tab');
            loginTab.classList.remove('inactive-tab');
            registerTab.classList.add('inactive-tab');
            registerTab.classList.remove('active-tab');
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
        }

        function showRegisterForm() {
            registerTab.classList.add('active-tab');
            registerTab.classList.remove('inactive-tab');
            loginTab.classList.add('inactive-tab');
            loginTab.classList.remove('active-tab');
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        }

        loginTab.addEventListener('click', showLoginForm);
        registerTab.addEventListener('click', showRegisterForm);

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('form') === 'register') {
                showRegisterForm();
            } else {
                showLoginForm();
            }
        });

        // New function to toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>