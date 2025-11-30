<?php
$pageTitle = 'หน้าแรก - Movie Review';
require_once 'includes/header.php';
require_once 'functions.php';

// ดึงข้อมูล
$movies = getAllMovies();
$reviews = getReviews(5);
$categories = getAllCategories();

// เพิ่มรีวิว
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    if (isLoggedIn()) {
        $movie_id = (int)$_POST['movie_id'];
        $comment = sanitizeInput($_POST['comment']);
        $rating = (int)$_POST['rating'];
        if (!empty($comment) && $rating >= 1 && $rating <= 5) {
            addReview($movie_id, $_SESSION['username'], $comment, $rating);
            $success = "เพิ่มรีวิวเรียบร้อย!";
            $reviews = getReviews(5);
        } else {
            $error = "กรุณากรอกข้อมูลให้ครบ";
        }
    } else {
        $error = "คุณต้องเข้าสู่ระบบก่อน";
    }
}
?>

<div class="container my-4">
  <div class="row">

    <!-- Sidebar หมวดหมู่ -->
    <div class="col-md-3 mb-4">
      <h4>หมวดหมู่หนัง</h4>
      <ul class="list-group">
        <?php foreach($categories as $cat): ?>
          <li class="list-group-item">
            <a href="category_detail.php?id=<?= $cat['id'] ?>">
              <?= htmlspecialchars($cat['name']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="col-md-9">
      <!-- หนังทั้งหมด -->
      <h4>หนังทั้งหมด</h4>
      <div class="row">
        <?php foreach($movies as $movie): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100 movie-card">
              <?php if(!empty($movie['poster'])): ?>
                <img src="assets/img/<?= $movie['poster'] ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>">
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($movie['description']) ?></p>
                <a href="movie_detail.php?id=<?= $movie['id'] ?>" class="btn btn-primary mt-auto">ดูรีวิว</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- รีวิวล่าสุด -->
      <h4 class="mt-4">รีวิวล่าสุด</h4>
      <?php foreach($reviews as $review): ?>
        <div class="card mb-2 review-card">
          <div class="card-body">
            <strong><?= htmlspecialchars($review['user_name']) ?></strong> รีวิว <em><?= htmlspecialchars($review['movie_title']) ?></em>: 
            <?= htmlspecialchars($review['comment']) ?>
            <span class="star">
              <?php for($i=0; $i<$review['rating']; $i++) echo "★"; 
                    for($i=$review['rating']; $i<5; $i++) echo "☆"; ?>
            </span>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- ฟอร์มเพิ่มรีวิว -->
      <?php if(isLoggedIn()): ?>
        <h4 class="mt-4">เพิ่มรีวิว</h4>
        <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="post" class="mb-4">
          <div class="mb-3">
            <label>เลือกหนัง</label>
            <select name="movie_id" class="form-select">
              <?php foreach($movies as $movie): ?>
                <option value="<?= $movie['id'] ?>"><?= htmlspecialchars($movie['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

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
      <?php endif; ?>
    </div>

  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
