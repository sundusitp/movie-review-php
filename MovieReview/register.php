<?php
$pageTitle = 'สมัครสมาชิก - Movie Review';
require_once 'includes/header.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        $error = "รหัสผ่านทั้งสองช่องไม่ตรงกัน";
    } elseif (registerUser($username, $password)) {
        $success = "สมัครสมาชิกเรียบร้อย! สามารถเข้าสู่ระบบได้ทันที";
    } else {
        $error = "ชื่อผู้ใช้นี้ถูกใช้แล้ว";
    }
}
?>

<style>
.auth-container {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: #f7f8fa;
}

.auth-card {
    background: rgba(255, 255, 255, 0.98);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 400px;
    transition: transform 0.2s;
}

.auth-card:hover {
    transform: translateY(-5px);
}

.form-control {
    border-radius: 8px;
}

.btn-success {
    border-radius: 8px;
    transition: background 0.2s, transform 0.2s;
}

.btn-success:hover {
    background: #28a745cc;
    transform: scale(1.02);
}
</style>

<div class="auth-container">
    <div class="auth-card">
        <h2 class="text-center mb-4">สมัครสมาชิก</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
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
            <div class="mb-3">
                <label class="form-label">ยืนยันรหัสผ่าน</label>
                <input type="password" name="password2" class="form-control" required placeholder="ยืนยันรหัสผ่าน">
            </div>
            <button type="submit" class="btn btn-success w-100">สมัครสมาชิก</button>
        </form>

        <div class="mt-3 text-center">
            <small>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></small>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
