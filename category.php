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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $topicSubject = $_POST['topic_subject'];
    $topicContent = $_POST['post_content'];
    $categoryId = $_POST['cat_id'];
    $userId = 1; // Replace with the appropriate user ID

    // Insert the new topic into the database
    $sql = "INSERT INTO topics (topic_subject, topic_cat, topic_by) VALUES ('$topicSubject', $categoryId, $userId)";
    $result = mysqli_query($connection, $sql);
    echo $sql;
    if (!$result) {
        echo 'Error creating the topic. Please try again later.';
        echo '<td><a href="forums.php">Back to Forums</a></td>';
    } else {
        $topicId = mysqli_insert_id($connection); // Get the ID of the newly inserted topic

        // Insert the first post into the database
        $sql = "INSERT INTO posts (post_content, post_topic, post_by) VALUES ('$topicContent', $topicId, $userId)";
        $result = mysqli_query($connection, $sql);
        
        if (!$result) {
            echo 'Error creating the post. Please try again later.'. mysqli_error($connection);;
            echo '<td><a href="forums.php">Back to Forums</a></td>';
        } else {
            echo 'Topic created successfully. <a href="topic.php?id=' . $topicId . '">View the topic</a>.';
            echo '<td><a href="forums.php">Back to Forums</a></td>';
        }
    }
}

// First select the category based on cat_id
$catId = $_GET['cat_id'];
$sql = "SELECT
            cat_id,
            cat_name,
            cat_description
        FROM
            CATEGORIES
        WHERE
            cat_id = " . mysqli_real_escape_string($connection, $catId);
$result = mysqli_query($connection, $sql);

if (!$result) {
    echo 'The category could not be displayed, please try again later.' . mysqli_error($connection);
} else {
    if (mysqli_num_rows($result) == 0) {
        echo 'This category does not exist.';
    } else {
        // Display category data
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<h2>Topics in ′' . $row['cat_name'] . '′ category</h2>';
        }
        // Do a query for the topics
        $sql = "SELECT
                    topic_id,
                    topic_subject,
                    topic_date,
                    topic_cat
                FROM
                    TOPICS
                WHERE
                    topic_cat = " . mysqli_real_escape_string($connection, $catId);
        $result = mysqli_query($connection, $sql);

        if (!$result) {
            echo 'The topics could not be displayed, please try again later.';
        } else {
            if (mysqli_num_rows($result) == 0) {
                echo 'There are no topics in this category yet.';
                echo '</br><td><a href="create_topic.php">Create one!</a></td>';
            } else {
                // Prepare the table
                echo '<table>
                        <tr>
                            <th>Topic</th>
                            <th>Created at</th>
                        </tr>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>';
                    echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a><h3>';
                    echo '</td>';
                    echo '<td>';
                    echo date('d-m-Y', strtotime($row['topic_date']));
                    echo '</td>';
                    echo '</tr>';
                }
            }
        }
    }
}


?>


<?php require 'footer.php'; ?>