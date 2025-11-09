 <?php 
// login.php
include 'includes/header.php'; 
// Assuming session_start() is handled in header.php
?>

<div class="flex items-center justify-center min-h-screen -mt-16">
    
    <div class="max-w-md mx-auto p-8 rounded-xl shadow-2xl" style="background-color: #8697C4;">
        
        <h2 class="text-3xl font-extrabold text-gray-900 mb-6 text-center">Login</h2>
        
        <form action="auth_handler.php?action=login" method="POST" class="space-y-6">
            
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="w-full border border-gray-300 p-3 rounded-lg text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                    required
                >
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full border border-gray-300 p-3 rounded-lg text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                    required
                >
            </div>
            
            <button 
                type="submit" 
                class="w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition"
            >
                Log In
            </button>
        </form>
        
        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account? <a href="register.php" class="text-indigo-600 hover:text-indigo-800 font-semibold transition">Register here</a>
        </p>

    </div>
</div>

<?php include 'includes/footer.php'; ?>