<?php require 'header.php'; ?> 

<form name='process_login' method="post" action="process_login.php">
<div class="main">
    <!-- log in form -->
    <div id='name'> 
    <?php echo '<h1>Login Page</h1>'; ?> 
        <label for="username">Username:</label></br>
        <input type='text'name='username' id="username" required> 
</br>
        <label for="password">Password:</label></br>
        <input type="password" name="password" id="password" required>
</br></br>
        <input type="submit" value="Login" class='signupbtn'>
</br>
</div>
    <div> 
      <p>Forgot Password?<a href='#'>Reset Password</a></p></br>
      <p>New to our site?<a href='signup.php'>Sign up!</a></p></br></br>
    </div>
</div>
</form>


<?php require 'footer.php'; ?> 