<!DOCTYPE html>
<html>
  <head>
    <title>Little Twin Stars</title>
    <link rel='stylesheet' href='style.css'>
  </head>
  
  <body>
    <div>
    <?php echo '<h1>Little Twin Stars</h1>'; ?> 
    
    <div class="stars">
<img src="topstars.png" width="480" height="215">
</div><div class="stars2">
<img src="topstars.png" width="480" height="215">
</div>

<div class="nav">
    <a class="dropdown" href='home.php'>Home</a>
      <div class="dropdown">
    <button class="dropbtn">Meet Other Stars
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
    <a href='list_users.php'>Stars List</a>
      <a href='signup.php'>New Star</a>
    </div>
  </div>

      <div class="dropdown">
    <button class="dropbtn">Forums
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
    <!-- <a href='list_departments.php'>Wallpapers</a>
    <a href='new_department.php'>Your Images</a> -->
    <a href="forums.php">All Forums</a>
		<a href="create_topic.php">Create a Topic</a>
		<a href="create_cat.php">Create a Category</a>
    </div>
  </div>

      <div class="topnav-right">
      <?php// Start the session
            // session_start();
            // require 'db.php';
            // // Check if the user is logged in
            // if (isset($_SESSION['user_id'])) {
            //     // User is logged in, display the user name
            //     $username = $_SESSION['username'];
            //     echo '<span>Welcome, ' . $username . '!</span>';
            // } else {
            //     // User is not logged in, display the login link
            //     echo '<a href="dashboard.php">Login</a>';
            // }
            ?>
          <a href="login.php">Login</a>
        </div>
    </div>
    
</div>

</br></br>