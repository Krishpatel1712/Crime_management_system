<?php
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
$res = mysqli_query($db, "SELECT * FROM officer");
while($row = mysqli_fetch_row($res)) {
    echo implode(" | ", $row) . "\n";
}
?>
