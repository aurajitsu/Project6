<?php require 'header.php'; ?>

<?php
include 'db.php';

// Start the session
session_start();

// // Check if the user is not logged in
// if (!isset($_SESSION['user_id'])) {
//     // Store the current page URL in a session variable
//     $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

//     // Redirect to the login page
//     header("Location: login.php");
//     exit();
// }

echo "<div class='main'>";
echo '<h2>Create a Topic</h2>';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $topicSubject = $_POST['topic_subject'];
    $topicContent = $_POST['post_content'];
    $categoryId = $_POST['category_id'];
    $userId = 1; // Replace with the appropriate user ID

    // Insert the new topic into the database
    $sql = "INSERT INTO TOPICS (topic_subject, topic_cat, topic_by) VALUES ('$topicSubject', $categoryId, $userId)";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        echo 'Error creating the topic. Please try again later.';
        echo '<td><a href="forums.php">Back to Forums</a></td>';
    } else {
        $topicId = mysqli_insert_id($connection); // Get the ID of the newly inserted topic

        // Insert the first post into the database
        $sql = "INSERT INTO posts (post_content, post_topic, post_by) VALUES ('$topicContent', $topicId, $userId)";
        $result = mysqli_query($connection, $sql);

        if (!$result) {
            echo 'Error creating the post. Please try again later.';
            echo '<td><a href="forums.php">Back to Forums</a></td>';
        } else {
            echo 'Topic created successfully. <a href="topic.php?id=' . $topicId . '">View the topic</a>.';
            echo '<td><a href="forums.php">Back to Forums</a></td>';
        }
    }
}

// Display the form
echo '
<form method="POST" action="create_topic.php">
    <label for="topic_subject">Topic Subject:</label></br>
    <input type="text" name="topic_subject" id="topic_subject" required><br></br>

    <label for="post_content">Post Content:</label></br>
    <textarea name="post_content" id="post_content" rows="5" required></textarea><br>

    <label for="category_id">Under What Category? (Enter ID number of Category):</label></br>
    <input type="number" name="category_id" id="category_id" required><br></br>

    <input type="submit" value="Create Topic">
</form>';

echo "</div>";
?>


<?php require 'footer.php'; ?>