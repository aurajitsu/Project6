<?php 
require 'header.php';
?>

<div class='main'>
<h1>Welcome New Star!</h1>

<?php 
// Start the session
            session_start();
            require 'db.php';
            // Check if the user is logged in
            if (isset($_SESSION['user_id'])) {
                // User is logged in, display the user name
                $username = $_SESSION['username'];
                echo '<span>Welcome, ' . $username . '!</span>';
            } else {
                // User is not logged in, display the login link
                echo 'Please login:';
                echo '<a href="login.php">Login</a>';
            }
            ?>

<p>Please select option from the navigation menu.</p>
<!-- <img src='/quote.jpg' width='364px' height='182px'> -->
<img src='/fishing.png' width='296px' height='297px'></br>


</div>

<?php 
require 'footer.php';
?>