<?php
// เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "root"; // เปลี่ยนตามจริง
$pass = "";     // เปลี่ยนตามจริง
$db   = "moviereview"; // ชื่อฐานข้อมูล
$conn = mysqli_connect($host, $user, $pass, $db);
if(!$conn) die("Connection failed: " . mysqli_connect_error());

// ฟังก์ชัน sanitize input
function sanitizeInput($str) {
    global $conn;
    return htmlspecialchars(mysqli_real_escape_string($conn, trim($str)));
}

// ตรวจสอบผู้ใช้ล็อกอิน
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// ฟังก์ชันดึงหนังทั้งหมด
function getAllMovies($limit = 0) {
    global $conn;
    $sql = "SELECT * FROM movies ORDER BY id DESC";
    if($limit > 0) $sql .= " LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// ดึงหมวดหมู่ทั้งหมด
function getAllCategories() {
    global $conn;
    $sql = "SELECT * FROM categories ORDER BY name";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// ดึงหนังตาม category
function getMoviesByCategory($category_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE category_id=? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// ดึงหนังตาม id
function getMovieById($movie_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $movie_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// ดึงรีวิวล่าสุด (จำนวนจำกัด)
function getReviews($limit=5) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT r.*, u.username AS user_name, m.title AS movie_title
                                   FROM reviews r
                                   JOIN users u ON r.user_name = u.username
                                   JOIN movies m ON r.movie_id = m.id
                                   ORDER BY r.created_at DESC
                                   LIMIT ?");
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// ดึงรีวิวทั้งหมดของหนัง
function getReviewsByMovie($movie_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT r.*, u.username AS user_name
                                   FROM reviews r
                                   JOIN users u ON r.user_name = u.username
                                   WHERE r.movie_id=?
                                   ORDER BY r.created_at DESC");
    mysqli_stmt_bind_param($stmt, "i", $movie_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// เพิ่มรีวิว
function addReview($movie_id, $user_name, $comment, $rating) {
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO reviews (movie_id, user_name, comment, rating, created_at) VALUES (?,?,?,?,NOW())");
    mysqli_stmt_bind_param($stmt, "issi", $movie_id, $user_name, $comment, $rating);
    return mysqli_stmt_execute($stmt);
}

// ลบรีวิว (ผู้ใช้ลบรีวิวตัวเอง)
function deleteReview($review_id, $user_name) {
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM reviews WHERE id=? AND user_name=?");
    mysqli_stmt_bind_param($stmt, "is", $review_id, $user_name);
    return mysqli_stmt_execute($stmt);
}

// ฟังก์ชันสมัครสมาชิก
function registerUser($username, $password) {
    global $conn;
    $username = sanitizeInput($username);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่ามีชื่อผู้ใช้นี้แล้วหรือไม่
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) > 0) return false;

    // เพิ่มผู้ใช้ใหม่
    $stmt2 = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?,?)");
    mysqli_stmt_bind_param($stmt2, "ss", $username, $password_hash);
    return mysqli_stmt_execute($stmt2);
}

// ฟังก์ชันล็อกอิน
function loginUser($username, $password) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            return true;
        }
    }
    return false;
}
?>
