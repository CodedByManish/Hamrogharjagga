<?php
// index.php

// Start session for authentication if needed
session_start();

// Example: Redirect logged-in users directly to dashboard
if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'buyer') {
        header("Location: buyer_dashboard.php");
        exit;
    } elseif ($_SESSION['user_role'] === 'seller') {
        header("Location: seller_dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HamroGharJagga - Real Estate</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    .role-select { background: linear-gradient(135deg,#f8fafc,#e2e8f0);border:2px solid #e2e8f0;border-radius:12px;transition:all .3s ease; }
    .role-select:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    .role-select option { background:white;color:#374151;padding:8px; }
    .role-select option:hover { background:#f3f4f6; }
    .gradient-text { background: linear-gradient(to right, #3b82f6, #6366f1, #a855f7); -webkit-background-clip: text; color: transparent; }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">

  <!-- Landing Page -->
  <div id="landing-page" class="min-h-screen">
    <!-- Navbar -->
    <nav class="fixed w-full z-20 bg-white/90 backdrop-blur-md border-b border-gray-200/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
            <i class="fas fa-home text-white text-sm"></i>
          </div>
          <span class="text-xl font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">HamroGharJagga</span>
        </div>
        <div class="hidden md:flex items-center space-x-8">
          <button onclick="showAuthFlow()" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-2 rounded-full hover:from-blue-600 hover:to-indigo-700 shadow-md transition-all">
            Get Started
          </button>
        </div>
        <button class="md:hidden text-gray-600"><i class="fas fa-bars text-xl"></i></button>
      </div>
    </nav>

    <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" 
                     alt="Premium real estate" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/30 via-indigo-500/20 to-purple-500/30"></div>
                <div class="absolute inset-0 bg-black/30"></div>
            </div>
            
            <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    Find Your 
                    <span class="bg-gradient-to-r from-blue-300 via-indigo-300 to-purple-300 bg-clip-text text-transparent">Dream Property</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-2xl mx-auto leading-relaxed">
                    Nepal's most trusted platform for buying and selling properties, lands, and commercial spaces
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <button onclick="showAuthFlow()" 
                            class="bg-white text-blue-600 px-8 py-3 rounded-full text-lg font-semibold hover:bg-gray-100 transition-all shadow-lg">
                        <i class="fas fa-search mr-2"></i>Find Properties
                    </button>
                    <button onclick="showAuthFlow()" 
                            class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-white/10 transition-all">
                        <i class="fas fa-plus mr-2"></i>List Property
                    </button>
                </div>
            </div>
            
            <!-- Floating elements for visual appeal -->
            <div class="absolute top-1/4 left-1/4 opacity-10">
                <i class="fas fa-home text-8xl text-white"></i>
            </div>
            <div class="absolute bottom-1/4 right-1/4 opacity-10">
                <i class="fas fa-landmark text-8xl text-white"></i>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4">
                        Simple & <span class="gradient-text">Effective</span>
                    </h2>
                    <p class="text-xl text-gray-600">Everything you need to find or sell your property</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mx-auto mb-4 flex items-center justify-center floating">
                            <i class="fas fa-search text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Search Properties</h3>
                        <p class="text-gray-600">Browse thousands of verified properties and lands across all municipalities, districts, and provinces in Nepal</p>
                    </div>
                    
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center floating">
                            <i class="fas fa-shield-alt text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Verified Listings</h3>
                        <p class="text-gray-600">Every property and land listing is thoroughly verified for authenticity and legal compliance</p>
                    </div>
                    
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full mx-auto mb-4 flex items-center justify-center floating">
                            <i class="fas fa-handshake text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Direct Contact</h3>
                        <p class="text-gray-600">Connect directly with buyers, sellers, and property agents without any middlemen</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-4">
                    Ready to <span class="gradient-text">Get Started</span>?
                </h2>
                <p class="text-xl text-gray-600 mb-8">Join thousands of satisfied customers who found their perfect property</p>
                <button onclick="showAuthFlow()" 
                        class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl">
                    Start Your Journey <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer-unified">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-home text-white text-sm"></i>
                            </div>
                            <span class="text-xl font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">HamroGharJagga</span>
                        </div>
                        <p class="text-gray-300 text-sm mt-2">
                            Nepal's premier real estate platform connecting buyers and sellers with trusted, verified properties across all municipalities, districts, and provinces in Nepal.
                        </p>
                    </div>
                    
                    <div>
                        <h4>Quick Links</h4>
                        <ul class="space-y-1 text-gray-300 text-sm">
                            <li><a href="pages/about.html" class="hover:text-blue-400 transition-colors">About</a></li>
                            <li><a href="pages/privacy.html" class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                            <li><a href="pages/help.html" class="hover:text-blue-400 transition-colors">Help Center</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4>Contact</h4>
                        <ul class="space-y-1 text-gray-300 text-sm">
                            <li><i class="fas fa-envelope mr-2 text-blue-400"></i>info@hamrogharjagga.com</li>
                            <li><i class="fas fa-phone mr-2 text-blue-400"></i>+977 1-234-5678</li>
                            <li><i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>Kathmandu, Nepal</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4>Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
                                <i class="fab fa-linkedin-in text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                    <p>&copy; 2024 HamroGharJagga. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Enhanced Auth Section (Compact Design) -->
    <div id="auth-section" class="hidden min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700"></div>
        <div class="absolute inset-0 bg-black/20"></div>
        
        <!-- Back to Landing Button -->
        <button onclick="backToLanding()" class="absolute top-4 left-4 z-20 bg-white/20 text-white px-4 py-2 rounded-full hover:bg-white/30 transition-all backdrop-blur-sm text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </button>
        
        <div class="relative bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-home text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">HamroGharJagga</h1>
                <p class="text-gray-600 text-sm mt-1">Choose your journey</p>
            </div>
            
            <div class="flex mb-4 bg-gray-100 rounded-xl p-1">
                <button id="login-tab" class="flex-1 py-2 px-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg transition-all" onclick="showLogin()">Login</button>
                <button id="register-tab" class="flex-1 py-2 px-3 text-gray-700 rounded-lg transition-all" onclick="showRegister()">Register</button>
            </div>

            <form id="login-form" onsubmit="handleLogin(event)" class="space-y-3">
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="email" placeholder="Email Address" required
                           class="w-full p-3 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="password" placeholder="Password" required
                           class="w-full p-3 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                
                <!-- Enhanced Role Selection -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">What are you here for?</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="relative">
                            <input type="radio" name="role" value="buyer" class="sr-only peer" required>
                            <div class="p-2 border-2 rounded-lg cursor-pointer text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-gray-300">
                                <div class="text-lg">🏠</div>
                                <div class="text-xs font-medium">I want to Buy Property</div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="role" value="seller" class="sr-only peer" required>
                            <div class="p-2 border-2 rounded-lg cursor-pointer text-center transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700 hover:border-gray-300">
                                <div class="text-lg">💼</div>
                                <div class="text-xs font-medium">I want to Sell Property</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <form id="register-form" onsubmit="handleRegister(event)" class="hidden space-y-3">
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Full Name" required
                           class="w-full p-3 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="email" placeholder="Email Address" required
                           class="w-full p-3 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="password" placeholder="Password" required
                           class="w-full p-3 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="password" placeholder="Confirm Password" required
                           class="w-full p-3 pl-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                </div>
                
                <!-- Enhanced Role Selection -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">What are you here for?</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="relative">
                            <input type="radio" name="registerRole" value="buyer" class="sr-only peer" required>
                            <div class="p-2 border-2 rounded-lg cursor-pointer text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:border-gray-300">
                                <div class="text-lg">🏠</div>
                                <div class="text-xs font-medium">I want to Buy Property</div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="registerRole" value="seller" class="sr-only peer" required>
                            <div class="p-2 border-2 rounded-lg cursor-pointer text-center transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700 hover:border-gray-300">
                                <div class="text-lg">💼</div>
                                <div class="text-xs font-medium">I want to Sell Property</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
            </form>
            
            <div id="loading-indicator" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-2xl shadow-2xl animate-bounce">
                    <div class="loading-spinner mx-auto mb-4"></div>
                    <p class="text-gray-600 text-center font-medium">Processing...</p>
                </div>
            </div>
        </div>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    function showAuthFlow() {
      window.location.href = "login_register.php"; // redirect to auth page
    }
  </script>

</body>
</html>