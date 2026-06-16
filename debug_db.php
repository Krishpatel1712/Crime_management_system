<?php
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "OFFICER TABLE DATA:\n";
$res = mysqli_query($db, "SELECT * FROM officer");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}

echo "\nUSERS TABLE DATA:\n";
$res2 = mysqli_query($db, "SELECT * FROM users");
while($row = mysqli_fetch_assoc($res2)) {
    print_r($row);
}
?>
