<?php
include 'db.php';
include 'header.php';

echo "<div class='main'>";

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
	// Redirect to the login page
	header("Location: login.php");
	exit();
  }
		
echo '<tr>';
	echo '<td class="leftpart">';
		echo '<h3><a href="category.php?id=">Category name</a></h3> Category description goes here';
	echo '</td>';
	echo '<td class="rightpart">';				
			echo '<a href="topic.php?id=">Topic subject</a> at 10-10';
	echo '</td>';
echo '</tr>';

echo "</div>";
?>




<?php include 'footer.php';?>