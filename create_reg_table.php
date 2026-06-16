<?php
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS `registration` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `offName` varchar(25) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `offID` int(5) NOT NULL,
  `contact` bigint(20) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($db, $sql)) {
    echo "Table 'registration' created successfully!";
} else {
    echo "Error creating table: " . mysqli_error($db);
}
?>
