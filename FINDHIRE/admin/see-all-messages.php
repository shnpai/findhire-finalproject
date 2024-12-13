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


if (!isset($_GET['application_id'])) {
    $_SESSION['message'] = "No application specified!";
    header("Location: viewyourJobPost.php");
    exit;
}


$unreadCount = getUnreadMessagesCount($pdo, $_SESSION['user_id']);


$applicationId = intval($_GET['application_id']);


$messages = getMessagesByApplicationId($pdo, $applicationId);


$applicationDetails = getJobApplicationById($pdo, $applicationId);


$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;


$applicant_id = $_SESSION['user_id'];


if (!$applicationDetails) {
    $_SESSION['message'] = "Application not found!";
    header("Location: viewyourJobPost.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages for Application</title>
    <link rel="stylesheet" href="admin/../styles/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h1>Messages from: <?php echo htmlspecialchars($applicationDetails['first_name']); ?></h1>
    <h2>Position Applied: <?php echo htmlspecialchars($applicationDetails['position_applied']); ?></h2>

    <!-- Chatbox Layout -->
    <div class="chat-container">
    <div class="message-box">
    <?php if (empty($messages)): ?>
        <p style="text-align: center;">No messages found for this application.</p>
    <?php else: ?>
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo ($message['sender_id'] == $_SESSION['user_id']) ? 'sent' : 'received'; ?>">
                <p><strong><?php echo htmlspecialchars($message['sender_username']); ?>:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                <p><small><?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($message['timestamp']))); ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



        <!-- Admin Reply Form -->
        <form action="core/handleForms.php" method="POST" style="max-width: 800px; margin: 0 auto;">
            <input type="hidden" name="application_id" value="<?php echo $applicationId; ?>">
            <input type="hidden" name="receiver_id" value="<?php echo $applicant_id; ?>"> <!-- The receiver is the applicant -->

            <textarea name="message" required placeholder="Type your reply here..."></textarea>
            <input type="submit" name="sendMessage" value="Send Reply">
        </form>
    </div>

</body>
</html>