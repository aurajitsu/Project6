<?php require 'header.php'; ?> 
<div class="main">

<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // form has been submitted
    //echo "Signup submitted successfully.";  //testing
}

// Define fields and their default values
$fields = [
    'username' => '',
    'first-name' => '',
    'last-name' => '',
    'city' => '',
    'state' => '',
    'email' => '',
    'password' => '',
    'confirmpass' => ''
];

// Populate fields with posted values
foreach ($fields as $key => $value) {
    $fields[$key] = $_POST[$key] ?? '';
}

// Sanitize form data
foreach ($fields as $key => $value) {
    $fields[$key] = mysqli_real_escape_string($connection, $value);
}

// Check if email or username already exists
$sqlCheck = "SELECT * FROM USERPROFILE WHERE email = ? OR username = ?";
$stmtCheck = $connection->prepare($sqlCheck);
$stmtCheck->bind_param("ss", $fields['email'], $fields['username']);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    // Email or username already exists
    echo "Email or username already exists. Please choose a different email or username.";
} else {
    // Insert form data into database
    $sql = "INSERT INTO USERPROFILE (username, first_name, last_name, city, state, email)
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssss", $fields['username'], $fields['first-name'], $fields['last-name'], $fields['city'], $fields['state'], $fields['email']);

    if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful.";
    } else {
        // Registration failed
        echo "Registration failed. Please try again later.";
    }
}

//PASSWORD HANDLING AFTER initial database info is inserted:
// Get the user ID from the database based on their email
$findEmail = $fields['email'];
$sql = "SELECT user_id FROM USERPROFILE WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $findEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    // Query failed, show an error message
    echo "Query failed: " . $connection->error;
    exit();
} elseif ($result->num_rows == 0) {
    // User does not exist, show an error message
    echo "User does not exist.";
    exit();
} else {
    // User exists, get their ID
    $row = $result->fetch_assoc();
    $id = $row["user_id"];
}

// If passwords match; insert the hashed password into the database
if ($fields['password'] == $fields['confirmpass']) {
  // Generate a random salt
  $salt = password_hash(random_bytes(16), PASSWORD_BCRYPT);
  // Hash the password with the salt
  $hashed_password = password_hash($fields['password'] . $salt, PASSWORD_BCRYPT);
  // Update the hashed password in the USERPROFILE table
  $sql = "UPDATE USERPROFILE SET password = ? WHERE user_id = ?";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("si", $hashed_password, $id);
  $result = $stmt->execute();

  // Check if the update was successful
  if ($result) {
      echo "<div class='main'><p>User ID: " . $connection->insert_id . "</br>";
      echo "Username: " . $fields['username'] . "</br>";
      echo "First Name: " . $fields['first-name'] . "</br>";
      echo "Last Name: " . $fields['last-name'] . "</br>";
      echo "Create Date: " . date('m-d-Y H:i:s') . "</p>";
      echo '<button type="submit" name="submit" class="signupbtn" onclick="window.location.href=\'list_users.php\'">Sign Up a New Star</button></br>';
      echo '<button type="submit" name="submit" class="signupbtn" onclick="window.location.href=\'profile_img.php?id=' . $id . '\'">Add a Profile Image!</button></br></div>';
  } else {
      echo "Error updating password: " . mysqli_error($connection);
  }
} else {
  // If passwords do not match, delete the row from the USERPROFILE table
  $sql = "DELETE FROM USERPROFILE WHERE user_id = ?";
  $stmt = $connection->prepare($sql);
  $stmt->bind_param("i", $id);
  $result = $stmt->execute();

  // Redirect to the signup.php form
  header("Location:signup.php?error=passwords_not_match&id=$id");
  exit();
}

// Close the prepared statements
$stmtCheck->close();
$stmt->close();
// Close the database connection
$connection->close();
exit;
?>

<img src='twins.png' height='271' width='300'>
</div>
<?php require 'footer.php'; ?> 