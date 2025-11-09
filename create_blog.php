 <?php
// create_blog.php - Tailwind Ready with Image Upload
require_once __DIR__ . '/db_connect.php';
include 'includes/header.php';

// (Authentication check)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=' . urlencode('You must be logged in to create or edit blogs.'));
    exit;
}

// --- PHP Logic for Edit/Create ---
$current_user_id = $_SESSION['user_id'];
$blog_id = $_GET['id'] ?? null;
$is_editing = ($blog_id !== null);
$pdo = $GLOBALS['pdo'];

$title = '';
$content = '';
$current_image_url = ''; 
$page_title = '➕ Create New Blog Post';

if ($is_editing) {
    // Fetch blog data for editing, including image_url
    $stmt = $pdo->prepare("SELECT user_id, title, content, image_url FROM blogPost WHERE id = ?");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch();
    
    if (!$blog || $blog['user_id'] != $current_user_id) {
        header('Location: index.php?error=' . urlencode('Unauthorized access or post not found.'));
        exit;
    }

    $title = $blog['title'];
    $content = $blog['content'];
    $current_image_url = $blog['image_url']; 
    $page_title = '✏️ Edit Blog Post: ' . htmlspecialchars(substr($title, 0, 50));
}
// --- End PHP Logic ---

?>

<div class="flex justify-center mt-8">
    <div class="w-full max-w-3xl">
        <div class="max-w-4xl mx-auto p-8 rounded-xl shadow-2xl" style="background-color: #ADBBDA;"> 
            <h2 class="text-3xl font-semibold text-gray-800 mb-6 border-b pb-3">
                <?php echo $page_title; ?>
            </h2>

            <form action="blog_handler.php" method="POST" enctype="multipart/form-data" class="space-y-6"> 
                <input type="hidden" name="action" value="<?php echo $is_editing ? 'update' : 'create'; ?>">
                <?php if ($is_editing): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($blog_id); ?>">
                <?php endif; ?>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Blog Title:</label>
                    <input type="text" id="title" name="title" 
                        class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" 
                        value="<?php echo htmlspecialchars($title); ?>" required>
                </div>
                
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Feature Image (Optional):</label>
                    
                    <?php if ($is_editing && $current_image_url): ?>
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 mb-2">Current Image:</p>
                            <img src="<?php echo htmlspecialchars($current_image_url); ?>" 
                                 alt="Current Blog Image" 
                                 class="w-48 h-auto rounded-lg shadow-md object-cover">
                            <p class="text-xs text-gray-500 mt-1">Upload a new file below to replace it.</p>
                        </div>
                    <?php endif; ?>

                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*"
                        class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content:</label>
                    <textarea id="content" name="content" rows="15" 
                        class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" 
                        required><?php echo htmlspecialchars($content); ?></textarea>
                </div>

                <button type="submit" 
                    class="w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50">
                    <?php echo $is_editing ? '💾 Save Changes' : '🚀 Publish Blog'; ?>
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>