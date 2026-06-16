<?php
session_start();
$_SESSION['login_user'] = 'raj';
$db = mysqli_connect('localhost', 'root', '', 'criminalinfo');
$user_check = $_SESSION['login_user'];
$ses_sql = mysqli_query($db,"SELECT offName, contact, role FROM officer WHERE offName = '$user_check'");
$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
echo "CHECKING FOR: $user_check\n";
print_r($row);
?>
