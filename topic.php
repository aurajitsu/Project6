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
$catId = $_GET['cat_id'];
$topicId = $_GET['topic_id'];

// Retrieve and display the topic
$sql = "SELECT
            topic_id,
            topic_subject,
            topic_cat
        FROM
            TOPICS
        WHERE
            topic_id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "s", $topicId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo 'The topic could not be displayed, please try again later.</br>';
    echo '<td><a href="forums.php">Back to Forums</a></td>';
} else {
    if (mysqli_num_rows($result) == 0) {
        echo 'This topic does not exist.</br>';
        echo '<td><a href="forums.php">Back to Forums</a></td>';
    } else {
        // Display the topic details
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<h2>' . $row['topic_subject'] . '</h2>';
            echo '<p>Category: ' . $row['topic_cat'] . '</p>';
        }

        // Retrieve and display POSTS
        $sqlPosts = "SELECT
                    post_id,
                    post_content,
                    post_date,
                    post_by
                FROM
                    POSTS
                WHERE
                    post_topic = " . mysqli_real_escape_string($connection, $topicId);
        $resultPosts = mysqli_query($connection, $sqlPosts);

        if (!$resultPosts) {
            echo 'The replies could not be displayed, please try again later.</br>';
            echo '<td><a href="forums.php">Back to Forums</a></td>';
        } else {
            // Loop through and display the retrieved posts
            while ($rowPosts = mysqli_fetch_assoc($resultPosts)) {
                echo '<div>';
                echo '<p>Author: ' . $rowPosts['post_by'] . '</p>';
                echo '<p>Date: ' . date('d-m-Y H:i:s', strtotime($rowPosts['post_date'])) . '</p>';
                echo '<p>' . $rowPosts['post_content'] . '</p>';
                echo '</div>';
            }
        }
        
        // Provide a form for users to submit new posts
        echo '</br></br><h4>Add a New Post</h4>';
        echo '<form method="POST" action="reply.php">';
        echo 'Your Name:</br>';
        echo '<input type="text" name="name"></br>';
        echo 'Content:</br>';
        echo '<textarea name="content"></textarea></br>';
        echo '<input type="hidden" name="topic_id" value="' . $topicId . '">';
        echo '<input type="submit" value="Submit">';
        echo '</form></br>';
    }
}

echo "</div>";
?>


<?php require 'footer.php'; ?>