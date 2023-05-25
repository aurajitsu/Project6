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
    echo '<td><a href="signup.php">Back to Signup</a></td></br></br>';
} else {
    // If passwords match; insert the hashed password and other user info into the database
    if ($fields['password'] == $fields['confirmpass']) {
        // Hash the password with the salt
        $hashed_password = password_hash($fields['password'], PASSWORD_BCRYPT);
        
        // Insert form data into database along with hashed password
        $sql = "INSERT INTO USERPROFILE (username, first_name, last_name, city, state, email, password)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssssss", $fields['username'], $fields['first-name'], $fields['last-name'], $fields['city'], $fields['state'], $fields['email'], $hashed_password);

        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful.";
            echo "<p>User ID: " . $connection->insert_id . "</br>";
            echo "Username: " . $fields['username'] . "</br>";
            echo "First Name: " . $fields['first-name'] . "</br>";
            echo "Last Name: " . $fields['last-name'] . "</br>";
            echo "Create Date: " . date('m-d-Y H:i:s') . "</p>";
            echo '<td><a href="login.php">Login!</a></td></br></br>';
            //echo '<button type="submit" name="submit" class="signupbtn" onclick="window.location.href=\'profile_img.php?id=' . $connection->insert_id . '\'">Add a Profile Image!</button></br>';

        } else {
            // Registration failed
            echo "Registration failed. Please try again later.";
            echo '<td><a href="signup.php">Back to Signup</a></td></br></br>';
        }
    } else {
        // If passwords do not match, show an error message
        echo "Passwords do not match.";
        exit();
    }
}

// Close the prepared statement
$stmtCheck->close();
// Close the database connection
$connection->close();
exit;
?>

<img src='twins.png' height='271' width='300'>
</div>
<?php require 'footer.php'; ?>