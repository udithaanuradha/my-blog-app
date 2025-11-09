 <?php
// view_blog.php - Tailwind Ready with 2-Column Grid (Content + Sidebar)
require_once __DIR__ . '/db_connect.php';
include 'includes/header.php';

$blog_id = $_GET['id'] ?? null;
$pdo = $GLOBALS['pdo'];

// Database fetching and authorization logic (unchanged)
// NOTE: Since you use SELECT b.*, the image_url column is already available in the $blog array.
$stmt = $pdo->prepare("SELECT b.*, u.username, b.user_id FROM blogPost b JOIN user u ON b.user_id = u.id WHERE b.id = ?");
$stmt->execute([$blog_id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC); // Use FETCH_ASSOC for safety

$is_author = false;
if (isset($_SESSION['user_id']) && $blog) {
    if ($_SESSION['user_id'] == $blog['user_id']) {
        $is_author = true;
    }
}

// Handle not found
if (!$blog) {
    echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-10 mx-auto max-w-2xl" role="alert">';
    echo '    <h2 class="font-bold text-xl">Blog Post Not Found</h2>';
    echo '    <p>The requested blog post does not exist.</p>';
    echo '    <p class="mt-4"><a href="index.php" class="inline-block px-4 py-2 text-sm font-medium text-gray-600 border border-gray-400 rounded-lg hover:bg-gray-200 transition">Go back to Home</a></p>';
    echo '</div>';
    include 'includes/footer.php';
    exit;
}

// --- Placeholder for Like/Comment Counters ---
$like_count = 0; 
$comment_count = 0; 
$is_liked = false;
?>

<div class="flex justify-center mt-8">
    <div class="w-full max-w-6xl">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-2">
                <div class="p-8 md:p-12 shadow-xl rounded-xl" style="background-color: #abbbda;">
                    
                    <?php if (!empty($blog['image_url'])): 
                        // The image_url is already relative (e.g., 'uploads/blog_xyz.jpg')
                        $image_path = htmlspecialchars($blog['image_url']);
                    ?>
                        <div class="mb-8 overflow-hidden rounded-lg">
                            <img 
                                src="<?php echo $image_path; ?>" 
                                alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                                class="w-full max-h-96 object-cover shadow-lg"
                            />
                        </div>
                    <?php endif; ?>
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-2"><?php echo htmlspecialchars($blog['title']); ?></h1>
                    
                    <p class="text-sm text-gray-700 mb-8">
                        By <strong class="font-semibold"><?php echo htmlspecialchars($blog['username']); ?></strong> | Created: <?php echo date('F j, Y', strtotime($blog['created_at'])); ?> 
                        <?php if ($blog['created_at'] != $blog['updated_at']): ?>
                            | Updated: <?php echo date('F j, Y', strtotime($blog['updated_at'])); ?>
                        <?php endif; ?>
                    </p>
                    
                    <div class="prose max-w-none text-gray-800 leading-relaxed text-lg pb-8 border-b border-gray-300">
                        <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
                    </div>

                    <div class="flex items-center justify-start space-x-6 pt-4">
                        <button 
                            id="like-button" 
                            class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition"
                            data-post-id="<?php echo $blog['id']; ?>">
                            <span class="<?php echo $is_liked ? 'text-indigo-600' : 'text-gray-500'; ?>">
                                <?php echo $is_liked ? '❤️' : '🤍'; ?>
                            </span>
                            <span class="font-medium text-lg text-gray-800">
                                <?php echo $like_count; ?> Likes
                            </span>
                        </button>

                        <button 
                            id="comment-button" 
                            class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition">
                            <span class="text-gray-500">
                                💬
                            </span>
                            <span class="font-medium text-lg text-gray-800">
                                <?php echo $comment_count; ?> Comments
                            </span>
                        </button>
                    </div>
                    
                    <?php if ($is_author): ?>
                        <hr class="my-8 border-gray-300">
                        <div class="flex justify-end space-x-3">
                            <a href="create_blog.php?id=<?php echo $blog_id; ?>" class="px-4 py-2 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition">✏️ Edit</a>
                            
                            <button 
                                class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition" 
                                onclick="if(confirm('Are you sure you want to delete this blog post?')) 
                                            { window.location.href='blog_handler.php?action=delete&id=<?php echo $blog_id; ?>'; }">
                                🗑️ Delete
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="p-6 shadow-md rounded-xl mb-8" style="background-color: #adbbda;">
                    <h3 class="text-xl font-semibold mb-3 border-b pb-2 text-indigo-700">✍️ Author</h3>
                    <p class="text-gray-700">Post by: **<?php echo htmlspecialchars($blog['username']); ?>**</p>
                    <p class="text-sm text-gray-600 mt-2">A short bio about the author, or links to their profile page.</p>
                </div>

                <div class="p-6 shadow-md rounded-xl" style="background-color: #adbbda;">
                    <h3 class="text-xl font-semibold mb-3 border-b pb-2 text-indigo-700">🔥 Trending Topics</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="hover:text-indigo-500"><a href="#">- Tailwind vs. Bootstrap</a></li>
                        <li class="hover:text-indigo-500"><a href="#">- Secure PHP Forms</a></li>
                        <li class="hover:text-indigo-500"><a href="#">- Database Optimization</a></li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>