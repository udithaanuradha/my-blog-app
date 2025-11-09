 <?php
// index.php - Complete Single Page Scroll Layout with Images
require_once __DIR__ . '/db_connect.php';
include 'includes/header.php'; 

// Fetch all blog posts, associated username, and image_url
$pdo = $GLOBALS['pdo']; 
$sql = "SELECT 
            b.id, b.title, b.content, b.created_at, b.image_url, 
            u.username 
        FROM blogPost b 
        JOIN user u ON b.user_id = u.id 
        ORDER BY b.created_at DESC";

$stmt = $pdo->query($sql);
$blogs = $stmt->fetchAll();
?>

<div id="about" style="background-color: #ADBBDA;" class="p-12 shadow-xl rounded-xl mb-16 pt-16 relative overflow-hidden"> 
    <div class="max-w-3xl mx-auto"> 
        <h2 class="text-4xl font-extrabold text-gray-900 mb-6 border-b pb-3">About Us</h2>
        <p class="text-lg text-gray-700 mb-4">
Welcome to WriteHub, your creative space to write, share, and inspire.

At WriteHub, we believe that every voice deserves to be heard. Our platform empowers writers, creators, and thinkers from around the world to publish their stories, articles, and ideas effortlessly. Whether you’re a professional blogger or just starting your writing journey, WriteHub gives you the tools and freedom to express yourself.        </p>
        <p class="text-gray-600">
🌟 Our Mission

To build a connected community of writers and readers where creativity meets technology — making online publishing simple, beautiful, and powerful.        </p>
    </div>
</div>

<div id="blogs" class="mb-16 pt-20"> 
    <div class="border-b pb-2 mb-8">
        <h2 class="text-4xl font-extrabold text-gray-800">Latest Blog Posts</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php if (empty($blogs)): ?>
            <div class="md:col-span-2">
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                    <p class="font-bold">No Posts Yet</p>
                    <p>No blog posts found. Be the first to create one!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($blogs as $blog): ?>
                <div>
                    <div style="background-color: #8697C4;" class="p-6 shadow-lg rounded-xl flex flex-col h-full hover:shadow-xl transition duration-300"> 
                        
                        <?php if ($blog['image_url']): ?>
                            <div class="mb-4">
                                <a href="view_blog.php?id=<?php echo $blog['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                                         class="w-full h-48 object-cover rounded-lg shadow-md transition duration-300 hover:opacity-90">
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <h3 class="text-2xl font-bold mb-2">
                                <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="text-gray-900 hover:text-indigo-800 transition">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </a>
                            </h3>
                            <p class="text-sm text-gray-700 mb-4">
                                By <strong class="font-semibold"><?php echo htmlspecialchars($blog['username']); ?></strong> on <?php echo date('F j, Y', strtotime($blog['created_at'])); ?>
                            </p>
                            <p class="text-gray-700 mb-6 flex-grow">
                                <?php echo htmlspecialchars(substr($blog['content'], 0, 150)) . '...'; ?>
                            </p>
                        </div>
                        <div class="mt-auto">
                            <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="inline-block px-4 py-2 text-sm font-medium text-indigo-900 border border-indigo-900 rounded-lg hover:bg-indigo-50 transition">
                                Read More &raquo;
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="contact" style="background-color: #ADBBDA;" class="p-12 shadow-xl rounded-xl mb-16 pt-16"> 
    <div class="max-w-5xl mx-auto"> 
        <h2 class="text-4xl font-extrabold text-gray-900 mb-8 border-b pb-3">Contact Us</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            
            <div class="md:col-span-1">
                <h3 class="text-2xl font-semibold text-indigo-700 mb-4">Get in Touch</h3>
                
                <div class="flex flex-col sm:flex-row sm:space-x-8 space-y-4 sm:space-y-0 mb-4">
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-indigo-500 text-lg"></span>
                        <div class="text-sm"> 
                            <p class="font-medium text-gray-800">Phone:</p>
                            <a href="tel:+1234567890" class="text-gray-600 hover:text-indigo-600 transition">76) 567-890</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-indigo-500 text-lg"></span>
                        <div class="text-sm"> 
                            <p class="font-medium text-gray-800">Email:</p>
                            <a href="mailto:support@yourblog.com" class="text-gray-600 hover:text-indigo-600 transition">support@yourblog.com</a>
                        </div>
                    </div>
                </div>
                
                
            </div>
            
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>