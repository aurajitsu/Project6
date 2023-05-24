<?php require 'header.php'; ?>

<?php
include 'db.php';
echo "<div class='main'>";

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Retrieve user information from the session
$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];

// Display personalized welcome message
echo "<h2>Welcome, $first_name $last_name!</h2>";

$sql = "SELECT 
        cat_id, 
        cat_name, 
        cat_description
        FROM 
        CATEGORIES";
$result = mysqli_query($connection, $sql);

if (!$result) {
    echo 'The categories could not be displayed, please try again later.';
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $catId = $row['cat_id'];

        $sqlTopic = "SELECT 
                        topic_id, 
                        topic_subject, 
                        topic_date
                        FROM 
                        TOPICS
                        WHERE
                        topic_cat = " . mysqli_real_escape_string($connection, $catId);
        $resultTopic = mysqli_query($connection, $sqlTopic);
        //display Categories
        echo '<h3>' . $row['cat_name'] . '</h3>' . $row['cat_description']; //removed link, seemed redundant: <a href="category.php?cat_id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a>
        echo '<table>'; 
        echo '<tr><th>Latest Topics</th></tr>';
        //display topics in each category
        while ($latestTopic = mysqli_fetch_assoc($resultTopic)) {
            echo '<tr class="">';
            echo '<td><a href="topic.php?topic_id=' . $latestTopic['topic_id'] . '">' . $latestTopic['topic_subject'] . '</a> - ' . date('m/d/Y', strtotime($latestTopic['topic_date'])) . '</td>';
            echo '</tr>';
        }

        echo '</table><br/>'; 
    }
}

echo '<td><a href="create_cat.php">Create a Category</a></td>';
echo '<td><a href="create_topic.php">Post New Topic</a></td>';
echo "</div>";
?>


<?php require 'footer.php'; ?>