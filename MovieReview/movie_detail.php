<?php
$pageTitle = 'รายละเอียดหนัง - Movie Review';
require_once 'includes/header.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>หนังไม่ถูกต้อง</div>";
    require_once 'includes/footer.php';
    exit;
}

$movie_id = (int)$_GET['id'];
$movie = getMovieById($movie_id); // ต้องสร้างฟังก์ชัน getMovieById ใน functions.php
$reviews = getReviewsByMovie($movie_id);

if(!$movie) {
    echo "<div class='alert alert-danger'>ไม่พบหนังนี้</div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<h2><?= htmlspecialchars($movie['title']) ?></h2>
<p><?= htmlspecialchars($movie['description']) ?></p>

<h3>รีวิว</h3>
<?php foreach($reviews as $review): ?>
<div class="card mb-2">
<div class="card-body">
<strong><?= $review['user_name'] ?></strong> รีวิว: <?= $review['comment'] ?>
<span class="star"><?php for($i=0;$i<$review['rating'];$i++) echo "★"; for($i=$review['rating'];$i<5;$i++) echo "☆"; ?></span>
</div>
</div>
<?php endforeach; ?>

<?php if(isLoggedIn()): ?>
<h3 class="mt-4">เพิ่มรีวิว</h3>
<form method="post" action="index.php">
<input type="hidden" name="movie_id" value="<?= $movie['id'] ?>">
<div class="mb-3">
<label>คะแนน (1-5)</label>
<input type="number" name="rating" min="1" max="5" class="form-control">
</div>
<div class="mb-3">
<label>ความคิดเห็น</label>
<textarea name="comment" rows="3" class="form-control"></textarea>
</div>
<button type="submit" name="add_review" class="btn btn-primary">ส่งรีวิว</button>
</form>
<?php else: ?>
<p>คุณต้อง <a href="login.php">เข้าสู่ระบบ</a> เพื่อเพิ่มรีวิว</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
