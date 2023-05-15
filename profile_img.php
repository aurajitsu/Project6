<?php require 'header.php'; ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require 'db.php';

// Update database with new $user_id
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user_id and username from form
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];

    // Check if a file was uploaded
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        // Validate the type. Should be JPEG or PNG.
        $allowed = ['image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png'];
        if (in_array($_FILES['profile_img']['type'], $allowed)) {
            $upload_dir = 'Lab6/uploads'; // Define the directory to upload the images
            $uploaded_file = $_FILES['profile_img']['tmp_name'];
            $image_name = "{$user_id}_{$username}_profile.jpg"; // Adjust the image filename based on your naming convention
            // Move the uploaded file to the desired directory with the user ID
            if (move_uploaded_file($uploaded_file, "$upload_dir/$image_name")) {
                // File upload successful, update the 'profile_img' field in the database with the new image filename
                $updateQuery = "UPDATE USERPROFILE SET profile_img = '$image_name' WHERE user_id = $user_id";
                $updateResult = $connection->query($updateQuery);

                if ($updateResult) {
                    // Success
                    header("location: list_users.php?msg=ok");
                    exit;
                } else {
                    // Failed to update
                    echo "Update query failed: " . $connection->error;
                }
            } else {
                // File upload failed
                echo '<p class="error">Failed to upload the image.</p>';
            }
        } else {
            // Invalid file type
            echo '<p class="error">Please upload a JPEG or PNG image.</p>';
        }
    }
}

// Display the edit form
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || isset($_FILES['profile_img']['error'])) {
    $user_id = $_GET['id'];
    $username = $_GET['username'];
    $query = "SELECT * FROM USERPROFILE WHERE user_id = $user_id AND username = '$username'";
    $result = $connection->query($query);
    $row = $result->fetch_array(MYSQLI_ASSOC);
}
?>

<!-- Edit form -->
<div>
    <div class='main'>
        <form name='profile_img' method="POST" action="profile_img.php" enctype="multipart/form-data">
            <div id='name'> 
                <h1>Add a Profile Image</h1>
                <p><b>Star ID: <?php echo " " . $user_id;?></b></br>
                <b>Username: <?php echo " " . $username;?></b></p>
                <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                <input type="hidden" name="username" value="<?php echo $username;?>">

                <input type="hidden" name="current_profile_img" value="<?php echo $row['profile_img']; ?>">

<!-- Image upload -->
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
<legend>Select a JPEG/PNG image of 1MB or smaller:</legend>
<p><strong>File:</strong> <input type="file" name="profile_img"></p>

<!-- Submit button -->
<button type="submit" name="submit" class="signupbtn">Save Changes</button><br>
</div>
</form>
</div>
</div>

<?php require 'footer.php'; ?>

