<?php require 'header.php'; ?> 

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require 'db.php';
// Define fields and their default values into an array
$fields = [
    'profile_img' => '',
    'user_id' => '',
    'username' => '',
    'first_name' => '',
    'last_name' => '',
    'city' => '',
    'state' => '',
    'email' => '',
    'edit' =>''
  ]; // removed: 'new_password' => '','confirm_password' => '';
// Populate fields with the posted values from form
foreach ($fields as $key => $value) {
    $fields[$key] = $_POST[$key] ?? '';
}

// Sanitize form data using prepared statements
$stmt = $connection->prepare("UPDATE USERPROFILE
                             SET 
                             profile_img = ?,
                             username = ?,
                             first_name = ?,
                             last_name = ?,
                             city = ?,
                             state = ?,
                             email = ?
                             WHERE user_id = ?");
$stmt->bind_param("sssssssi", $fields['profile_img'], $fields['username'], $fields['first_name'], $fields['last_name'], $fields['city'], $fields['state'], $fields['email'], $user_id);
$stmt->execute();

//TODO: create an if statement to check the user, if admin- allow edits

// Update database with form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // form has been submitted
    if (isset($_POST['submit'])) {
        // Get user_id and username from form submitted form
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
                    $fields['profile_img'] = $image_name;
                } else {
                    // File upload failed, handle the error accordingly
                    echo '<p class="error">Failed to upload the image.</p>';
                }
            } else {
                // Invalid file type
                echo '<p class="error">Please upload a JPEG or PNG image.</p>';
            }
        }
//update changed info, does not include user_id, username and password
        $updateQuery = "UPDATE USERPROFILE
                        SET 
                        profile_img = '{$fields['profile_img']}',
                        username = '{$fields['username']}',
                        first_name = '{$fields['first_name']}',
                        last_name = '{$fields['last_name']}',
                        city = '{$fields['city']}',
                        state = '{$fields['state']}',
                        email = '{$fields['email']}'
                        WHERE user_id = $user_id";
                        //TODO: password = '{$fields['password']}'
        $updateResult = $connection->query($updateQuery);
        //echo $updateQuery; //testing 
        if ($updateResult) {
            //Success
            //echo "Updated successfully.";//testing
            header("location: list_users.php?msg=ok");
            exit;
        } else {
            //Fail
            echo "Update query failed: " . $connection->error;
        }
    }

// Delete the img file if it still exists:
if (file_exists ($_FILES['profile_img']['tmp_name']) && is_file($_FILES['profile_img']['tmp_name']) ) {
		unlink ($_FILES['profile_img']['tmp_name']);
}
}

//pull user_id and username from query, if form has not been submitted with id
else {
    $user_id = $_GET['id'];
    $username = isset($_GET['username']) ? $_GET['username'] : '';
    $query = "SELECT * FROM USERPROFILE WHERE user_id = ? AND username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("is", $user_id, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

}
?>

<!-- edit form -->
<div><div class='main'>
<form name='edit_user' method="POST" action="edit_user.php" enctype="multipart/form-data">
<!-- input group -->
    <div id='name'> 
    <h1>Add a Profile Image</h1>
    <!-- <p>Add a Profile Image</p> -->
    <!-- ID group -->
    <p><b>Star ID: <?php echo " ". $user_id;?></b></br>
    <b>Username: <?php echo " ". $username;?></b>
<!-- IMG upload-->
    <input type="hidden" name="current_profile_img" value="<?php echo $row['profile_img']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000">
    <legend>Select a JPEG/PNG image of 1MB or smaller:</legend>
    <p><strong>File:</strong> <input type="file" name="profile_img"></p>
    <?php
// Display the images associated with the user
date_default_timezone_set('America/Los_Angeles');
$dir = 'Lab6/uploads'; // Define the directory to view
$files = scandir($dir); // Read all the images into an array

// Display each image caption as a link to the JavaScript function:
foreach ($files as $image) {
    if (substr($image, 0, 1) != '.') { // Ignore anything starting with a period
        // Get the image's size in pixels
        $image_size = getimagesize("$dir/$image");
        // Calculate the image's size in kilobytes
        $file_size = round((filesize("$dir/$image")) / 1024) . "kb";
        // Determine the image's upload date and time
        $image_date = date("F d, Y H:i:s", filemtime("$dir/$image"));
        // Make the image's name URL-safe
        $image_name = urlencode($image);
        // Generate a unique ID for the image container
        $container_id = 'image_' . md5($image);

        // Print the information with the image displayed
        echo "<div><img src=\"$dir/$image\" alt=\"Image\" width=\"$image_size[0]\" height=\"$image_size[1]\"></div>";
        echo "<p>Image: $image | File Size: $file_size | Date Uploaded: $image_date</p>";
    }
}
?>
    <!-- hidden user_id and username in order to pass id into a POST variable -->
    <input type="hidden" name="user_id" value="<?php echo $user_id;?>"></br>
    <input type="hidden" name="username" value="<?php echo $username;?>"></p></br></br>

    <label for='first_name'><p>First Name:</p></label>
    <input type='text' name='first_name' value="<?php echo $row['first_name'];?>" placeholder="First Name">    

    <label for='last_name'><p>Last Name:</p></label>
    <input type='text' name='last_name' value="<?php echo $row['last_name'];?>" placeholder="Last Name">  
    
    <label for='city'><p>City:</p></label>
    <input type='text' name='city' value="<?php echo $row['city'];?>" placeholder="City">    

    <label for='state'><p>State:</p></label>
    <input type='text' name='state' value="<?php echo $row['state'];?>" placeholder="State"></br>   

    <label for="email"> Email:</label></br>
    <input type="email" id="email" name='email' value="<?php echo $row['email'];?>">   
            
    <!--TODO: password chage: -->
        <!-- <label for='password'><p>Password:</p></label>
        <input type='text' name='new_password' value="<?php echo $row['password'];?>" required> 

    <label for='new_password'><p>New Password:</p></label>
        <input type='password' name='new_password' value="<?php echo $row['new_password'];?>" placeholder="New Password">

    <label for='confirm_password'><p>Confirm Password:</p></label>
        <input type='password' name='confirm_password' value="<?php echo $row['confirm_password'];?>" placeholder="Confirm Password">

    Submit button -->
</br></br>
    <button type="submit" name="submit" class="signupbtn">Save Changes</button><br>
    <!-- <button onclick="window.location.href = 'list_users.php'" class="signupbtn">Cancel</button> -->
    <br><br>
    </div>
</form>
</div></div>

<?php require 'footer.php'; ?> 