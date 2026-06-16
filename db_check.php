<?php
$conn = mysqli_connect('localhost', 'root', '', 'criminalinfo');
$res = mysqli_query($conn, "SELECT * FROM users");
$users = [];
while($row = mysqli_fetch_assoc($res)) { $users[] = $row; }
$res = mysqli_query($conn, "SELECT * FROM officer");
$officers = [];
while($row = mysqli_fetch_assoc($res)) { $officers[] = $row; }
echo json_encode(['users' => $users, 'officers' => $officers], JSON_PRETTY_PRINT);
?>
