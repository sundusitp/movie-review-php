<?php
$pageTitle = 'เข้าสู่ระบบ - Movie Review';
require_once 'includes/header.php';

if(isLoggedIn()) {
    header("Location: index.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    if(loginUser($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h2 class="card-title text-center mb-4">เข้าสู่ระบบ</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">ชื่อผู้ใช้</label>
                <input type="text" name="username" class="form-control" required placeholder="ใส่ชื่อผู้ใช้">
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" required placeholder="ใส่รหัสผ่าน">
            </div>
            <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
        </form>

        <div class="mt-3 text-center">
            <small>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></small>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
