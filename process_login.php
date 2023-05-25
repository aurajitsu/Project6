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
  $stored_password = '';
  // testing
  echo "Entered Username: " . $username . "<br>";
  echo "Entered Password: " . $password . "<br>";

  if ($user) {
     // Ensure the 'password' index exists and is not empty
     if (!isset($user['password']) || empty($user['password'])) {
      echo "The 'password' index does not exist or is empty.";
  } else {
      $stored_password = $user['password'];
      echo 'Hashed password from database: ' . $stored_password . "<br>";
  }
  //testing
    //echo 'User fetched from the database: ';
    //print_r($user) . "<br>";
    $verification_result = password_verify($password, $stored_password);
    echo 'Verification result: ' . ($verification_result ? 'true' : 'false') . '</br>';

    $stored_password = $user['password'];
    // Verify the entered password against the stored hashed password
    if ($verification_result) {
      // Login successful
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $username;
      $user_id = $user['user_id'];
      echo "Passed login successful! Entered Username: " . $username . "</br>";
      header("Location: home.php");
    } else {
      // Login failed
            echo 'Invalid password.';
        
    }
  }

  // Close the database connection
  $stmt->close();
  $connection->close();
}

// Close the database connection
$connection = null;
?>