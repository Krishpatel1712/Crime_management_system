<?php
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
$res = mysqli_query($db, "DESCRIBE officer");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
