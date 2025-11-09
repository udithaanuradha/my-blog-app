<?php
// data/dummy_data.php - Stores hardcoded data for the non-persistent phase

// Start the session if it hasn't been started yet (needed for storing changes)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hardcoded Users (Mock 'user' table)
$DUMMY_USERS = [
    // Alice is user_id 1
    1 => ['id' => 1, 'username' => 'Alice', 'email' => 'alice@blog.com', 'password' => password_hash('password', PASSWORD_DEFAULT), 'role' => 'author'],
    // Bob is user_id 2
    2 => ['id' => 2, 'username' => 'Bob', 'email' => 'bob@blog.com', 'password' => password_hash('password', PASSWORD_DEFAULT), 'role' => 'user'],
];

// Helper function to get username by ID
function get_username($user_id) {
    global $DUMMY_USERS;
    return $DUMMY_USERS[$user_id]['username'] ?? 'Unknown Author';
}

// --- Blog Post Management (Using Session for Temporary Persistence) ---

// Define the initial hardcoded blogs (Mock 'blogPost' table)
$INITIAL_DUMMY_BLOGS = [
    101 => [
        'id' => 101, 
        'user_id' => 1, 
        'title' => 'Getting Started with PHP', 
        'content' => "Welcome to Alice's blog! This is a detailed post about the basics of PHP scripting and why it's a great choice for web development.", 
        'created_at' => '2025-11-01', 
        'updated_at' => '2025-11-01'
    ],
    102 => [
        'id' => 102, 
        'user_id' => 2, 
        'title' => 'CSS Flexbox Guide', 
        'content' => 'Bob explains how to master Flexbox for responsive design. It is much better than floats! Learn how to align and distribute space between items in a container.', 
        'created_at' => '2025-10-28', 
        'updated_at' => '2025-10-28'
    ],
    103 => [
        'id' => 103, 
        'user_id' => 1, 
        'title' => 'Database Design Principles', 
        'content' => 'A quick look at normalization and relational schemas for beginners. This is a vital first step before writing any SQL code.', 
        'created_at' => '2025-11-03', 
        'updated_at' => '2025-11-03'
    ],
];

// Check if blogs are already stored in session (meaning changes might have been made)
if (isset($_SESSION['DUMMY_BLOGS'])) {
    // If they exist in the session, use the modified version
    $DUMMY_BLOGS = $_SESSION['DUMMY_BLOGS'];
} else {
    // If not in session, use the default hardcoded blogs and save them to the session
    $DUMMY_BLOGS = $INITIAL_DUMMY_BLOGS;
    $_SESSION['DUMMY_BLOGS'] = $DUMMY_BLOGS;
}
?>