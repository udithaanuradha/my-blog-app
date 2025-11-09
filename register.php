 <?php
// register.php - User Registration Form - Tailwind Ready
include 'includes/header.php'; 
?>

<div class="flex justify-center mt-12">
    <div class="w-full max-w-md">
 <div class="max-w-md mx-auto p-8 rounded-xl shadow-2xl" style="background-color: #8697C4;">            
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">📝 User Registration</h2>
            
            <form action="auth_handler.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="register">
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username:</label>
                    <input type="text" id="username" name="username" 
                        class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" 
                        required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
                    <input type="email" id="email" name="email" 
                        class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" 
                        required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
                    <input type="password" id="password" name="password" 
                        class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" 
                        required>
                </div>

                <button type="submit" 
                    class="w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 mt-6">
                    Register Account
                </button>
            </form>
            
            <p class="text-center text-sm text-gray-600 mt-4">
                Already have an account? <a href="login.php" class="text-indigo-600 hover:text-indigo-800 font-semibold transition">Log In here</a>.
            </p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>