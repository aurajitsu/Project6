<?php
//PDO connection below:
$host = "localhost";
$user = "clemente_admin";
$pass = "Pass0520!";
$db = "clemente_lab6";
$port = 3306;

try {
    $connection = new PDO("mysql:host=$host;dbname=$db;port=$port", $user, $pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "<p class='border'><style>p.border{border-style: solid hidden solid hidden;border-width: thin;}</style>connection made</p>";
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

//mysqli connection below:
// Create connection
$connection = new mysqli($host, $user, $pass, $db);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// If no errors, you can proceed with your sql queries
?>