<?php
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
$res = mysqli_query($db, "DESCRIBE officer");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
echo "\nDATA:\n";
$res = mysqli_query($db, "SELECT * FROM officer LIMIT 2");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
