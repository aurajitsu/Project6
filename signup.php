<?php require 'header.php'; 
//display message if redirected from error
// Retrieve the error message and user ID from the URL parameters
$error = isset($_GET['error']) ? $_GET['error'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';


$error = $_GET['error'] ?? '';
// Display the error message if it exists
if ($error === 'passwords_not_match') {
    echo "<div class='main'><p>";
    echo "The passwords you entered do not match. Please try again.";
    echo "</div></p>";
}
?>

<form name='process_signup' method="POST" action="process_signup.php">
<div class='main'>
    <!--input group -->
    <div id='name'> 
    <img src='oncloud.png' height='268.25' width='285.25'> 
    <?php echo '<h1>New Star Sign Up</h1>'; ?> 
        <p>Create an account:</p>
        <input type='text' id='username' name='username' placeholder='username' required></br>
        <input type='text' id='first-name' name='first-name' placeholder='First' required></br>
        <input type='text' id='last-name' name='last-name' placeholder='Last' required></br>
        <input type="text" id='city' name="city" value="city" placeholder='city' required> </br>
        <!-- at some point validate city in selected state -->
        <input type="text" id='state' name="state" value="state" placeholder='state' required></br>
    <input type='email' id='email' name='email' value='email' placeholder='Email' required> </br>

    <input type="password" placeholder="Enter Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
    </br>
    <input type="password" placeholder="Confirm Password" name="confirmpass" required></br>
    <label><p>Password must contain: 1 uppercase, 1 lowercase, 1 number</br> and at least 8 or more characters.</p></label>
    <input type="hidden" readonly name="edit" value="edit">

    <?php
        if(isset($_POST['password']) && isset($_POST['confirm-password'])) {
            if($_POST['password'] != $_POST['confirm-password']) {
            echo "<p style='color: red;'>Passwords do not match!</p>";
            }
        }
    ?>
        </div>

    <!-- Submit button -->
    <button type='submit' id='signup' class='signupbtn' value="signup">Sign Up</button>
    <br><br>
    </div>
    </div>
</form>



<?php require 'footer.php'; ?> 