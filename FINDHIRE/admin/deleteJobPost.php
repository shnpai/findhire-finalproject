
<?php
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM job_posts WHERE posted_by = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$jobPosts = $stmt->fetchAll();
?>