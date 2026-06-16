<?php
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "ALTER TABLE officer ADD COLUMN pass VARCHAR(255) AFTER offID";
if (mysqli_query($db, $sql)) {
    echo "Column 'pass' added successfully!";
} else {
    echo "Error adding column: " . mysqli_error($db);
}
?>
