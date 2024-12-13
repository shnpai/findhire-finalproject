<?php 
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

// Get job details from the URL
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;
if ($job_id <= 0) {
    header("Location: index.php");
}

$jobDetails = getJobDetails($pdo, $job_id);

if (!$jobDetails) {
    echo "Job not found!";
    exit;
}

// Check if the applicant has already applied for this job
$applicant_id = $_SESSION['user_id'];
$existingApplication = checkExistingApplication($pdo, $applicant_id, $job_id);

if ($existingApplication) {
    $_SESSION['applicationMessage'] = "Your application is successfully submitted.";
    $_SESSION['applicationStatus'] = '200';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="apply-job-container">
        <h1>Apply for the job: <?php echo htmlspecialchars($jobDetails['title']); ?></h1>
        <p><strong>Job Description:</strong> <?php echo nl2br(htmlspecialchars($jobDetails['description'])); ?></p>

        <?php
       
        if (isset($_SESSION['applicationMessage'])) {
            $message = $_SESSION['applicationMessage'];
            $status = $_SESSION['applicationStatus'];

            
            $cssClass = ($status === '200') ? 'alert-success' : 'alert-danger';

            echo "<div class='alert {$cssClass}'>{$message}</div>";

          
            unset($_SESSION['applicationMessage'], $_SESSION['applicationStatus']);
        } elseif ($existingApplication) {
            
            echo "<p>You have already applied for this job. If you need to update your application, please contact HR.</p>";
        }
        ?>

        <?php if (!$existingApplication): ?>
            <form action="core/handleForms.php" method="POST" enctype="multipart/form-data" class="apply-form">
                <h2>Please fill in the Form:</h2>
                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                <input type="hidden" name="applicant_id" value="<?php echo $_SESSION['user_id']; ?>">

                <label for="name">Name:</label>
                <input type="text" name="first_name" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" required><br>

                <label for="position_applied">Position Applied:</label>
                <input type="text" name="position_applied" required><br>

                <label for="description">Profile Description:</label>
                <textarea name="description" required placeholder="Tell us summary about your skills and how you want to be known as a worker."></textarea><br>

                <label for="resume">Upload Resume (PDF only):</label>
                <input type="file" name="resume" accept=".pdf" required><br>

                <input type="submit" name="submitApplication" value="Submit Application" class="submit-button">
            </form>
        <?php endif; ?>
        <div>
            <h2>Send a Message to HR Representative</h2>
            <a href="messageAdmin.php?job_id=<?php echo $job_id; ?>" class="message-hr-link">Send a message</a>
        </div>
    </div>
</body>
</html>
