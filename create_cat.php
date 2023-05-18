<?php require 'header.php'; ?>

 <?php
// create_cat.php
include 'db.php';
echo "<div class='main'>";
echo '<h2>Create a Category</h2>';

// Start the session
session_start();

// // Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     // Redirect to the login page or display an error message
//     header("Location: login.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // the form hasn't been posted yet, display it
    echo '<form method="POST" action="create_cat.php"> 
    Category name: <input type="text" name="cat_name" /></br></br>
    Category description:</br> <textarea name="cat_description"></textarea> 
    </br><input type="submit" value="Add category" /> 
    </form>';
} else {
    // the form has been posted, so save it
    $cat_name = mysqli_real_escape_string($connection, $_POST['cat_name']);
    $cat_description = mysqli_real_escape_string($connection, $_POST['cat_description']);

    $sql = "INSERT INTO CATEGORIES (cat_name, cat_description)
            VALUES ('$cat_name', '$cat_description')";

    $result = mysqli_query($connection, $sql);
    if (!$result) {
        // something went wrong, display the error
        echo 'Error: ' . mysqli_error($connection);
        echo '<td><a href="forums.php">Back to Forums</a></td>';
    } else {
        echo 'New category successfully added.';
        echo '<td><a href="forums.php">Back to Forums</a></td>';
    }
}

echo "</div>";
?>


<?php require 'footer.php'; ?>