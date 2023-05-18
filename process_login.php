<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // if form has been submitted
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query the database for a user with the entered last name
  $stmt = $connection->prepare("SELECT * FROM USERPROFILE WHERE username = ?");
  // bind the parameter, then execute the statement, then fetch the result
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $user_result = $stmt->get_result();
  $user = mysqli_fetch_assoc($user_result);
  print_r($user);  //testing
  //print_r($_SESSION['id']); //testing

  // testing
  echo "Entered Username: " . $username . "<br>";
  echo "Entered Password: " . $password . "<br>";

  if ($user) {
      $stored_password = $user['password'];
  // Verify the entered password against the stored hashed password
  if (($password == $stored_password)) {
      // Login successful
      $_SESSION['id'] = $user['user_id'];
      $user_id = $user['user_id'];
      echo "Passed login successful! Entered Username: " . $username . "<br>";
      header("Location: forums.php");
      // Check if a redirect URL is stored in the session
      // if (isset($_SESSION['redirect_url'])) {
        
      //   // Redirect the user back to the stored URL
      //   $redirectUrl = $_SESSION['redirect_url'];
      //   unset($_SESSION['redirect_url']); // Remove the stored URL from the session
      //   header("Location: $redirectUrl");
      //   exit();
      // } else {
      //   // No redirect URL available, redirect to a default page
      //   header("Location: home.php");
      //   exit();
      //   }
    } else {
        // Login failed
        echo "Invalid name or password.";
      }
  }
  // Close the database connection
  $stmt->close();
  $connection->close();
}
// Close the database connection
$connection = null;
?>


