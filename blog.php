<?php
// blog.php
session_start();
require_once 'config.php';

// Connect to the database using PDO
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle backend CRUD actions
$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        // Create new blog post
        $title    = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '';
        $content  = $_POST['content'] ?? '';
        $readTime = $_POST['read_time'] ?? 0;
        $image    = $_POST['image'] ?? ''; // In production, handle file uploads properly
        $stmt = $pdo->prepare("INSERT INTO posts (title, category, content, post_date, read_time, image) VALUES (?, ?, ?, NOW(), ?, ?)");
        $stmt->execute([$title, $category, $content, $readTime, $image]);
        header("Location: blog.php");
        exit;
    } elseif ($action === 'update' && isset($_GET['id'])) {
        // Update existing post
        $id       = (int)$_GET['id'];
        $title    = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '';
        $content  = $_POST['content'] ?? '';
        $readTime = $_POST['read_time'] ?? 0;
        $image    = $_POST['image'] ?? '';
        $stmt = $pdo->prepare("UPDATE posts SET title=?, category=?, content=?, read_time=?, image=? WHERE id=?");
        $stmt->execute([$title, $category, $content, $readTime, $image, $id]);
        header("Location: blog.php");
        exit;
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id=?");
    $stmt->execute([$id]);
    header("Location: blog.php");
    exit;
}

// For displaying blog posts with pagination
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Count total posts for pagination
$totalPosts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Fetch posts for current page
$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY post_date DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Insights & Industry Trends | Applause IT Solutions</title>
    <link rel="icon" href="img/favicon/favicon.ico">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Icon Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="lib/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="lib/animate/animate.min.css">
    <!-- Main Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .blog-hero {
            background: linear-gradient(rgba(244, 120, 10, 0.9), rgba(244, 120, 10, 0.9)), url('img/blog-banner.jpg') center/cover;
            min-height: 400px;
        }
        .post-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .category-badge {
            background: #F4780A;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .pagination .page-link {
            color: #F4780A;
        }
        .pagination .page-item.active .page-link {
            background: #F4780A;
            border-color: #F4780A;
        }
    </style>
</head>
<body>
    <!-- Header (loaded dynamically via header.html) -->
    <div id="header"></div>

    <!-- Page Header Start -->
    <div class="container-fluid bg-primary py-5 bg-header">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Blog</h1>
                <a href="index.html" class="h5 text-white">Home</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="blog.php" class="h5 text-white">Blog</a>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Main Content -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Blog Posts -->
                <div class="col-lg-8">
                    <!-- Categories Navigation -->
                    <nav class="mb-5">
                        <div class="nav nav-pills" id="nav-tab" role="tablist">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all">All</button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#web-dev">Web Development</button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ui-ux">UI/UX</button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#seo">SEO</button>
                        </div>
                    </nav>

                    <!-- Blog Grid -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all">
                            <div class="row g-4">
                                <?php foreach($posts as $post): ?>
                                <div class="col-md-6 wow fadeInUp">
                                    <div class="post-card bg-white rounded overflow-hidden h-100">
                                        <img src="<?= htmlspecialchars($post['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($post['title']) ?>">
                                        <div class="p-4">
                                            <span class="category-badge"><?= htmlspecialchars($post['category']) ?></span>
                                            <h3 class="h4 my-3"><?= htmlspecialchars($post['title']) ?></h3>
                                            <div class="post-meta mb-3">
                                                <span class="me-3"><i class="far fa-calendar"></i> <?= date("M d, Y", strtotime($post['post_date'])) ?></span>
                                                <span><i class="far fa-clock"></i> <?= htmlspecialchars($post['read_time']) ?> min read</span>
                                            </div>
                                            <p><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</p>
                                            <a href="blog-details.php?id=<?= $post['id'] ?>" class="btn btn-primary">Read More</a>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- Additional tab panes for filtering by category can be added here -->
                    </div>

                    <!-- Pagination -->
                    <nav class="mt-5" aria-label="Blog pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page-1 ?>">Previous</a>
                            </li>
                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page+1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Newsletter -->
                    <div class="bg-white p-4 mb-5 rounded shadow-sm">
                        <h3 class="h4 mb-4">Subscribe to Our Newsletter</h3>
                        <form>
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Enter your email">
                                <button class="btn btn-primary" type="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>

                    <!-- Popular Posts -->
                    <div class="bg-white p-4 rounded shadow-sm">
                        <h3 class="h4 mb-4">Popular Articles</h3>
                        <div class="list-group">
                            <?php
                            // Fetch 5 recent posts as popular posts
                            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY post_date DESC LIMIT 5");
                            $stmt->execute();
                            $popularPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php foreach($popularPosts as $pop): ?>
                            <a href="blog-details.php?id=<?= $pop['id'] ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($pop['image']) ?>" alt="<?= htmlspecialchars($pop['title']) ?>" width="60" class="me-3">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($pop['title']) ?></h6>
                                        <small class="text-muted"><?= date("M d, Y", strtotime($pop['post_date'])) ?></small>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer (loaded dynamically via footer.html) -->
    <div id="footer"></div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
