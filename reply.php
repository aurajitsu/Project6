<?php require 'header.php'; ?>

<?php
include 'db.php';
echo "<div class='main'>";
// Start the session
session_start();

// Check if the user is not logged in
// if (!isset($_SESSION['user_id'])) {
//     // Store the current page URL in a session variable
//     $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

//     // Redirect to the login page
//     header("Location: login.php");
//     exit();
// }

$topicId = $_POST['topic_id'];
$replyContent = $_POST['content'];

// Validate the reply content (e.g., check for empty content, maximum length, etc.)

// Insert the reply into the database
$sql = "INSERT INTO POSTS (post_content, post_date, post_topic, post_by) 
        VALUES (?, NOW(), ?, ?)";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "sss", $replyContent, $topicId, $_SESSION['user_id']);
$result = mysqli_stmt_execute($stmt);

if (!$result) {
    echo 'There was an error while submitting your reply. Please try again.';
    echo '<td><a href="forums.php">Back to Forums</a></td>';
} else {
    // Redirect back to the topic view page
    header("Location: topic.php?cat_id=" . urlencode($catId) . "&topic_id=" . urlencode($topicId));
    exit();
}
echo "</div>";

?>
<?php require 'footer.php'; ?>





