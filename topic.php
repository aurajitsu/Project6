<?php require 'header.php'; ?>

<?php
include 'db.php';
$dir = 'Lab6/uploads';  // Path to the directory where images are stored

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
mysqli_stmt_bind_param($stmt, "i", $topicId);
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
            //echo '<p>Category: ' . $row['topic_cat'] . '</p>';
        }

        // Retrieve and display POSTS, retireve USER profile img
        $sqlPosts = "SELECT
                        POSTS.post_id,
                        POSTS.post_content,
                        POSTS.post_date,
                        USERPROFILE.username,
                        USERPROFILE.profile_img
                    FROM
                        POSTS
                    INNER JOIN
                        USERPROFILE
                    ON
                        POSTS.post_by = USERPROFILE.user_id
                    WHERE
                        POSTS.post_topic = ?";
        $stmtPosts = mysqli_prepare($connection, $sqlPosts);
        mysqli_stmt_bind_param($stmtPosts, "s", $topicId);
        mysqli_stmt_execute($stmtPosts);
        $resultPosts = mysqli_stmt_get_result($stmtPosts);

        if (!$resultPosts) {
            echo 'The replies could not be displayed, please try again later.</br>';
            echo '<td><a href="forums.php">Back to Forums</a></td>';
        } else {
            // Loop through and display the retrieved posts
            echo "<div class='postBox'>";
            while ($rowPosts = mysqli_fetch_assoc($resultPosts)) {
                $imagePath = $dir . '/' . $rowPosts['profile_img'];
                echo "<div class='postBox'>";
                echo '<p><img src="' . $imagePath . '" alt="Profile image" class="thumbnail">';
                echo $rowPosts['username'] .' says: ' . '</br>';
                echo '' . '</br>'. $rowPosts['post_content'] . '</p></br></br>';
                echo ' Date: ' . date('m-d-Y H:i:s', strtotime($rowPosts['post_date'])) .' ';
                echo '</div></br>';
            }
            echo "</div></br>";
        }
        
        // Provide a form for users to submit new posts
        echo '<div class="postBox"></br></br><h4>Add a New Post</h4>';
        echo '<form method="POST" action="reply.php"><p>';
        echo 'Your Post:</br>';
        echo '<textarea name="content"></textarea></br>';
        echo '<input type="hidden" name="topic_id" value="' . $topicId . '">';
        echo '<button class="button" type="submit">Submit</button>';
        echo '</form></p></br></div></br>';
        // Add a back button
        echo '<button class="button" onclick="goBack()">Back</button>';
        echo '</div></br>';

        // JavaScript function to go back to the previous page
        echo '<script>';
        echo 'function goBack() {';
        echo '    history.go(-1);';
        echo '}';
        echo '</script>';

    }
}

echo "</div>";
?>


<?php require 'footer.php'; ?>