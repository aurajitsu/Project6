<?php require 'header.php'; ?>

<?php
require 'db.php';
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

echo "<div class='main'>";
echo "<img src='sleeping.png' height='248.5' width='262.5'>";
echo "<h1>All Little Stars</h1></br>";

// create a query- store it in a variable
$query = "SELECT * FROM USERPROFILE";

// open the DB connection and run the query
$result = mysqli_query($connection, $query);

$headers = array("Profile Image", "User ID", "Username", "Name", "City, State", "Email"); // password is not displayed
$sortColumns = array("profile_img", "user_id","username", "Name", "City, State", "email"); // columns with sort option
$arrayLength = count($headers);

// sorting the columns by ascending
echo "<table id='sortCol' style='width:950px;'>";
echo "<tr>";
$i = 0;
while ($i < $arrayLength) {
    if (in_array($sortColumns[$i], $sortColumns)) {
        // add JS and link to id to <th> tag, add headers, add arrow symbol
        echo "<th onclick='sortTable($i)'><a href=\"" . $_SERVER['PHP_SELF'] . "?sort=" . $sortColumns[$i] . "&order=ASC\">" . $headers[$i] . "  &#9650;</a></th>";
    } else {
        echo "<th>" . $headers[$i] . "</th>";
    }
    $i++;
}
echo "</tr>"; // close header row

// check if sort and order are set in URL
if (isset($_GET['sort']) && isset($_GET['order'])) {
    $sort = $_GET['sort'];
    $order = $_GET['order'];
    $query = "SELECT * FROM USERPROFILE ORDER BY $sort $order";
} else {
    $query = "SELECT * FROM USERPROFILE";
}

// display table rows
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>";
    $dir = 'Lab6/uploads';  //pull profile img to display
    if (!empty($row['profile_img'])) {
        $imagePath = $dir . '/' . $row['profile_img'];
        echo "<img src='" . $imagePath . "' alt='Profile Image' class='thumbnail'></tr>";
    } else {
        echo "No Image.";
    }
    //echo out each table row from query/database
    echo "<tr><td><a href='profile_img.php?id=" . $row['user_id'] . "&username=" . $row['username'] . "' class='editLink'>upload</a></td>" .
        "<td>" . $row['user_id'] .
        "</td><td>" . $row['username'] .
        "</td><td>" . $row['first_name'] ." ". $row['last_name'] .
        "</td><td>" . $row['city'] ." ". $row['state'] .
        "</td><td>" . $row['email']."</td></tr>";
}
echo "</table></div>";


?>
<!-- JS in order to sort -->
<script>
    function sortTable(column) {
        var table = document.getElementById("sortCol");
        var rows = table.rows;
        var arrow = "&#9650;"; // up arrow
        var asc = true; // sort in ascending order

        // toggle arrow and sort order
        if (rows[0].cells[column].innerHTML.includes("9660")) {
            arrow = "&#9650;"; // up arrow
            asc = true;
        } else {
            arrow = "&#9660;"; // down arrow
            asc = false;
        }
        rows[0].cells[column].innerHTML = "<?php echo $headers[$column] . ' '; ?>" + arrow; // update header arrow

        // sort rows based on selected column
        var sortedRows = [];
        for (var i = 1; i < rows.length; i++) {
            sortedRows.push(rows[i]);
        }
        sortedRows.sort(function(a, b) {
            var aVal = a.cells[column].innerHTML;
            var bVal = b.cells[column].innerHTML;
            return asc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
        });
        for (var i = 0; i < sortedRows.length; i++) {
            table.appendChild(sortedRows[i]);
        }
    }
</script>

<?php require 'footer.php'; ?>

