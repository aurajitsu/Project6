<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Images</title>
	<script charset="utf-8" src="function.js"></script>
</head>


<body>
<p>Click on an image to view it in a separate window.</p>
<ul>

<?php # Script 11.6 - images.php
// This script lists the images in the uploads directory.
// This version now shows each image's file size and uploaded date and time.

require 'db.php';
// Set the default timezone:
date_default_timezone_set('America/Los_Angeles');

$dir = 'uploads'; // Define the directory to view.
$files = scandir($dir); // Read all the images into an array.

// Display each image caption as a link to the JavaScript function:
foreach ($files as $image) {

	if (substr($image, 0, 1) != '.') { // Ignore anything starting with a period.

		// Get the image's size in pixels:
		$image_size = getimagesize("$dir/$image");

		// Calculate the image's size in kilobytes:
		$file_size = round( (filesize("$dir/$image")) / 1024) . "kb";

		// Determine the image's upload date and time:
		$image_date = date("F d, Y H:i:s", filemtime("$dir/$image"));

		// Make the image's name URL-safe:
		$image_name = urlencode($image);

		// Generate a unique ID for the image container:
			$container_id = 'image_' . md5($image);

		// Print the information:
		// echo "<li><a href=\"javascript:create_window('$image_name',$image_size[0],$image_size[1])\">$image</a> $file_size ($image_date)</li>\n";

		// Print the information with a clickable file name:
			echo "<li><a href=\"javascript:void(0)\" onclick=\"show_image('$container_id', '$dir/$image', $image_size[0], $image_size[1])\">$image</a> $file_size ($image_date)</li>\n";
        	echo "<div id=\"$container_id\"></div>"; // Add image container

			
	} // End of the IF.

} // End of the foreach loop.

?>

<script>//displays img beneath link when link is clicked
    function show_image(containerId, imageUrl, width, height) {
        var imageContainer = document.getElementById(containerId);
        if (imageContainer) {
            imageContainer.innerHTML = "<img src=\"" + imageUrl + "\" alt=\"Image\" width=\"" + width + "\" height=\"" + height + "\">";
        }
    }
</script>


</ul>
<button onclick="window.location.href='upload_image.php'" class="signupbtn">Upload More Images</button>
</body>
</html>