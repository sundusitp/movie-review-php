<?php $pageTitle='หน้าแรก - Movie Review'; require_once 'includes/header.php'; ?>

<?php
$host = "localhost";
$user = "root";
$pass = "";  // ค่าเริ่มต้น XAMPP
$db   = "moviereview";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<?php require_once 'includes/footer.php'; ?>
