<?php
$pageTitle = 'หมวดหมู่หนัง - Movie Review';
require_once 'includes/header.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>หมวดหมู่ไม่ถูกต้อง</div>";
    require_once 'includes/footer.php';
    exit;
}

$category_id = (int)$_GET['id'];
$movies = getMoviesByCategory($category_id);

if(!$movies) {
    echo "<div class='alert alert-info'>ยังไม่มีหนังในหมวดหมู่นี้</div>";
}
?>

<h2>หนังในหมวดหมู่</h2>
<div class="row mb-4">
<?php foreach($movies as $movie): ?>
<div class="col-md-4">
<div class="card mb-3">
<div class="card-body">
<h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
<p><?= htmlspecialchars($movie['description']) ?></p>
<a href="movie_detail.php?id=<?= $movie['id'] ?>" class="btn btn-primary">ดูรีวิว</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
