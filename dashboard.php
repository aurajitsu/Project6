<?php 
session_start();
require 'db.php';
require 'header.php';


// Check if the user is logged in
if (!isset($_SESSION['id'])) {
  // Redirect to the login page
  header("Location: login.php");
  exit();
}
?>

  <div class="main">
<!-- user dashboard -->
<?php 
// Get the user's information from the database
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM USER WHERE user_id = '$user_id'";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    // User exists, get their information
    $row = $result->fetch_assoc();
    $firstName = $row["first_name"];
    $lastName = $row["last_name"];
    $welcome_message = "<h1>Welcome " . $firstName ." ". $lastName . "!</h2></br> 
    <p>I speak for the entire company when I say we're excited to have you here.</br>
    As you know, our philosophy is to move fast and break things, and your contribution will be vital.
    So, you might be wondering: Why you? </br>Simply: Human test subjects get better scientific results than animals. 
    Why? For one, your tramps, lunatics, foundlings, what-have-you, can bring problem-solving ability to test 
    environments with a facility that shames lower ruminants. For another, you have thumbs.</br>
    So anyway. Welcome to Aperture.</br> You’re here because we want the best test subjects sixty dollars can buy, and you’re apparently it!</br>
    All the best!</br>-Cave Johnson</p></br>";

    $employeeDash = "<p><b>This is your new employee dashboard, please take a look around:</b></p>" .
    "<article><p>1. Contact HR for pending documentation, we need your signature for release forms!<br>" .
    "2. Please contact your Supervisor, it seems you still need an ID badge!<br>" .
    "3. Also! Oh nothing...just making sure you read this!</p></br></article>" .
    "<p>Forgot Password?<a href='#'>Contact IT</a></p>";

    $annoucements = "<p><b>Now for today's announcements!:</b></br>Those of you who volunteered to be injected with praying mantis DNA,</br> I've got some good news and some bad news.</br>
    Bad news is we're postponing those tests indefinitely.</br>Good news is we've got a much better test for you: fighting an army of mantis men. 
    </br>Pick up a rifle and follow the yellow line. You'll know when the test starts.</br>They say great science is built on the shoulders of giants. Not here. 
    </br>At Aperture, we do all our science from scratch. No hand holding!</br>-Cave Johnson</p></br>";

    $logoutBtn = "<form name='process_login' method='post' action='process_logout.php'><br>" .
    "<input type='submit' value='logout' class='signupbtn'>" .
    "<br> </form>";

    $html = $welcome_message . $employeeDash . $annoucements . $logoutBtn;

  echo $html;
} else {
    // User does not exist, show an error message
    echo "User does not exist.";
    exit();
}
?>
  </div>
<?php  
// Close the database connection
mysqli_close($connection);?> 

<?php require 'footer.php'; ?> 