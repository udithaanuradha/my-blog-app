 <?php
// auth_handler.php - Handles Registration, Login, and Logout
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db_connect.php';
$pdo = $GLOBALS['pdo']; // Assume PDO connection is globally available

// Check if an action is specified
$action = $_POST['action'] ?? $_GET['action'] ?? null;

// --- REGISTRATION HANDLER ---
if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        header('Location: register.php?error=' . urlencode('All fields are required.'));
        exit;
    }
    
    // Securely hash the password (CRITICAL FOR LOGIN)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            header('Location: register.php?error=' . urlencode('Username or email already taken.'));
            exit;
        }

        // Insert new user into database
        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        header('Location: login.php?message=' . urlencode('Registration successful! Please log in.'));
        exit;

    } catch (PDOException $e) {
        // Log the error for debugging
        error_log("Registration failed: " . $e->getMessage());
        header('Location: register.php?error=' . urlencode('Registration failed due to a database error.'));
        exit;
    }

// --- LOGIN HANDLER (FIXED) ---
} elseif ($action === 'login') {
    // FIX: Retrieve user identifier from the 'username' field (assuming the form uses 'username')
    $input_identifier = trim($_POST['username'] ?? ''); 
    $password = $_POST['password'] ?? '';

    if (empty($input_identifier) || empty($password)) {
        header('Location: login.php?error=' . urlencode('Both identifier and password are required.'));
        exit;
    }

    try {
        // Fetch user by email OR username
        $stmt = $pdo->prepare("SELECT id, username, password FROM user WHERE email = ? OR username = ?");
        $stmt->execute([$input_identifier, $input_identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // CRITICAL: Verify the plain password against the stored hash
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                session_regenerate_id(true); // Security measure
                
                header('Location: index.php?message=' . urlencode('Welcome back, ' . $user['username'] . '!'));
                exit;
            }
        }
        
        // Login failed (generic error for security)
        header('Location: login.php?error=' . urlencode('Invalid username/email or password.'));
        exit;

    } catch (PDOException $e) {
        error_log("Login failed: " . $e->getMessage());
        header('Location: login.php?error=' . urlencode('Login failed due to a server error.'));
        exit;
    }

// --- LOGOUT HANDLER ---
} elseif ($action === 'logout') {
    session_unset();
    session_destroy();
    
    header('Location: index.php?message=' . urlencode('You have been logged out successfully.'));
    exit;

} else {
    // Default action
    header('Location: index.php');
    exit;
}
?>