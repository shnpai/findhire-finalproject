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


$userId = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$userId) {
    die("Invalid user session.");
}


$sql = "SELECT * FROM job_posts WHERE posted_by = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$jobPosts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Your Job Posts</title>
    <link rel="stylesheet" href="admin/../styles/styles.css">
</head>
<body>

    <?php include 'navbar.php'; ?>
    <h1>Your Job Posts</h1>

    <?php if (empty($jobPosts)): ?>
        <p>You have not posted any jobs yet.</p>
    <?php else: ?>
        <table class="job-posts-table">
            <thead>
                <tr>
                    <th>Post ID</th>
                    <th>Job Title</th>
                    <th>Date Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobPosts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['id']); ?></td>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['date_posted']); ?></td>
                        <td>
                            <a href="viewPost.php?id=<?php echo urlencode($post['id']); ?>">View</a> |
                            <a href="editPost.php?id=<?php echo urlencode($post['id']); ?>">Edit</a> |
                            <a href="deletePost.php?id=<?php echo urlencode($post['id']); ?>" 
                               onclick="return confirm('Are you sure you want to delete this job post?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?> 
</body>
</html>