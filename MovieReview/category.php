<?php $pageTitle='หน้าแรก - Movie Review'; require_once 'includes/header.php'; ?>

<?php
require_once 'functions.php';
$categories = getAllCategories();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>หมวดหมู่หนัง</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<h1>หมวดหมู่หนัง</h1>
<ul class="list-group">
<?php foreach($categories as $cat): ?>
<li class="list-group-item"><a href="category_detail.php?id=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
<?php endforeach; ?>
</ul>
<a href="index.php" class="btn btn-secondary mt-3">กลับหน้าหลัก</a>
</div>
</body>
</html>
<?php require_once 'includes/footer.php'; ?>
