<?php
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
$res = mysqli_query($db, "SELECT * FROM officer");
while($row = mysqli_fetch_assoc($res)) {
    foreach($row as $k => $v) {
        echo "$k: [" . $v . "]\n";
    }
    echo "---\n";
}
?>
