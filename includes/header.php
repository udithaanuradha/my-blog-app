 
 
 <?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Define your application name here for easy access
$app_name = "Script Hub"; 
$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($app_name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: Add smooth scrolling for navigation links */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="shadow-md sticky top-0 z-50" style="background-color: #ADBBDA;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            
            <a href="index.php" class="text-gray-900 transition duration-300">
                <span class="text-4xl font-extrabold tracking-tight" style="color: #0d0e0eff;">
                    <?php echo htmlspecialchars($app_name); ?>
                </span>
            </a>
            <nav class="hidden md:flex space-x-6 items-center">
                <a href="index.php#about" class="text-gray-700 hover:text-indigo-600 font-medium">About</a>
                <a href="index.php#blogs" class="text-gray-700 hover:text-indigo-600 font-medium">Blogs</a>
                <a href="index.php#contact" class="text-gray-700 hover:text-indigo-600 font-medium">Contact</a>

                <?php if ($is_logged_in): ?>
                    <a href="create_blog.php" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        ➕ Create Post
                    </a>
                    <a href="auth_handler.php?action=logout" class="text-red-600 hover:text-red-800 font-medium">Logout (<?php echo htmlspecialchars($username); ?>)</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-700 hover:text-indigo-600 font-medium">Login</a>
                    <a href="register.php" class="px-3 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg hover:bg-indigo-700 transition" href="register.php">Register</a>
                    </a>
                <?php endif; ?>
            </nav>
            <div class="md:hidden">
                </div>
        </div>
    </div>
</header>

<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <?php 
    // Display global messages/errors
    if (isset($_GET['message'])): ?>
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>