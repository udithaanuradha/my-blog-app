 <?php
// blog_handler.php - FINAL WORKING VERSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db_connect.php';
$pdo = $GLOBALS['pdo'];

// Authentication check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=' . urlencode('You must be logged in.'));
    exit;
}

// --- Image Upload Path Setup ---
// CRITICAL FIX: Define the upload directory using the absolute server path
define('UPLOAD_DIR_ABS', __DIR__ . '/uploads/'); 
define('UPLOAD_DIR_WEB', 'uploads/');


/**
 * Handles image upload, saves to the uploads/ directory, and returns the path.
 * Returns null if no file was uploaded or if the upload/move failed.
 */
function handle_image_upload() {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $new_file_name = uniqid('blog_') . '.' . $file_ext;
        
        // 1. Path for the server (ABSOLUTE PATH for move_uploaded_file)
        $upload_path_full = UPLOAD_DIR_ABS . $new_file_name;
        
        // 2. Path for the database (RELATIVE PATH for display)
        $upload_path_db = UPLOAD_DIR_WEB . $new_file_name; 

        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        
        if (!in_array($file_ext, $allowed_extensions)) {
            error_log("Image Upload Failed: Extension not allowed.");
            return null;
        }
        
        // Use the ABSOLUTE path for the move operation
        if (move_uploaded_file($file_tmp, $upload_path_full)) {
            // SUCCESS: Return the clean, RELATIVE PATH for the database
            return $upload_path_db; 
        } else {
            error_log("Failed to move uploaded file. Check folder permissions on 'uploads/'.");
            return null;
        }
    }
    return null; // No file uploaded or upload failed
}

// --- Request Handling ---
// FIX: Check both POST and GET for action and ID
$action = $_POST['action'] ?? $_GET['action'] ?? null; 
$user_id = $_SESSION['user_id'];
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$blog_id = $_POST['id'] ?? $_GET['id'] ?? null; 

// Initial validation (checks if required fields/action are present)
if (empty($action) || (empty($title) || empty($content)) && $action != 'delete') {
    header('Location: index.php?error=' . urlencode('Invalid action or missing required fields.'));
    exit;
}

// --- 1. CREATE HANDLER ---
if ($action === 'create') {
    $image_url = handle_image_upload(); 
    
    if ($image_url === null && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_INI_SIZE) {
        header('Location: create_blog.php?error=' . urlencode('Image file is too large. Check server limits.'));
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO blogPost (user_id, title, content, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $content, $image_url]); 

        header('Location: index.php?message=' . urlencode('Blog post published successfully!'));
        exit;

    } catch (PDOException $e) {
        error_log("Blog Creation Error: " . $e->getMessage());
        header('Location: create_blog.php?error=' . urlencode('Post creation failed due to a database error.'));
        exit;
    }
} 

// --- 2. UPDATE HANDLER ---
elseif ($action === 'update' && $blog_id) {
    $fail_url = 'Location: create_blog.php?id=' . $blog_id . '&error=';

    $new_image_url = handle_image_upload();
    
    if ($new_image_url === null && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_INI_SIZE) {
        header($fail_url . urlencode('New image file is too large. Check server limits.'));
        exit;
    }
    
    try {
        $sql = "UPDATE blogPost SET title = ?, content = ?, updated_at = NOW()";
        $params = [$title, $content];

        if ($new_image_url) {
            $sql .= ", image_url = ?";
            $params[] = $new_image_url;
        }

        $sql .= " WHERE id = ? AND user_id = ?";
        $params[] = $blog_id;
        $params[] = $user_id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        header('Location: view_blog.php?id=' . $blog_id . '&message=' . urlencode('Blog post updated successfully! (Image updated if selected)'));
        exit;

    } catch (PDOException $e) {
        error_log("Blog Update Error: " . $e->getMessage());
        header($fail_url . urlencode('Post update failed due to a database error.'));
        exit;
    }
}

// 💥 --- 3. DELETE HANDLER (FINAL CLEAN FIX) --- 💥
elseif ($action === 'delete' && $blog_id) {
    // 1. Check ownership
    $stmt = $pdo->prepare("SELECT user_id FROM blogPost WHERE id = ?");
    $stmt->execute([$blog_id]);
    $post_owner_id = $stmt->fetchColumn();

    if ($post_owner_id != $user_id) {
        header('Location: index.php?error=' . urlencode('Unauthorized action.'));
        exit;
    }

    try {
        // Start transaction (only one query remains, but good practice)
        $pdo->beginTransaction();

        // 🚀 ONLY DELETE THE BLOG POST ITSELF
        // All dependent deletion queries were removed since 'comments' and 'likes' 
        // tables do not exist in your current schema.
        $stmt = $pdo->prepare("DELETE FROM blogPost WHERE id = ?"); 
        $stmt->execute([$blog_id]);

        $pdo->commit(); // Commit the transaction
        
        // If it reaches here, the deletion succeeded
        header('Location: index.php?message=' . urlencode('Blog post deleted successfully.'));
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack(); 
        error_log("Blog Deletion Error: " . $e->getMessage());
        
        // If this still fails, the constraint is likely on the user_id foreign key, 
        // or a third table exists that you haven't identified.
        header('Location: view_blog.php?id=' . $blog_id . '&error=' . urlencode('Post deletion failed. A database constraint is still blocking the operation.'));
        exit;
    }
}

header('Location: index.php?error=' . urlencode('Invalid request.'));
exit;
?>