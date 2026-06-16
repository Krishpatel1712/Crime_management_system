<?php
session_start();

$servername="localhost";
$username="root";
$pass="";
$db="criminalinfo";
$conn=mysqli_connect($servername,$username,$pass,$db);

if (!$conn) {
    die("Database connection failed");
}

// Ensure audit table exists (safe to run every time)
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `criminal_profile_updates` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `criminal_id` INT NOT NULL,
    `officer_username` VARCHAR(50) NOT NULL,
    `action` VARCHAR(10) NOT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX (`criminal_id`),
    INDEX (`officer_username`),
    INDEX (`updated_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$officer = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : 'unknown';
$criminalId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($criminalId <= 0) {
    die("Invalid criminal id");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $offname = mysqli_real_escape_string($conn, $_POST['offname'] ?? '');
    $crime = mysqli_real_escape_string($conn, $_POST['crime'] ?? '');
    $more = mysqli_real_escape_string($conn, $_POST['more'] ?? '');
    $dob = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
    $arrDate = mysqli_real_escape_string($conn, $_POST['arrDate'] ?? '');
    $crimeDate = mysqli_real_escape_string($conn, $_POST['crimeDate'] ?? '');
    $sex = mysqli_real_escape_string($conn, $_POST['sex'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');

    $q = "UPDATE `info` SET
        `name`='$name',
        `offname`='$offname',
        `crime`='$crime',
        `more`='$more',
        `dob`='$dob',
        `arrDate`='$arrDate',
        `crimeDate`='$crimeDate',
        `sex`='$sex',
        `address`='$address'
    WHERE `id`=$criminalId";

    if (mysqli_query($conn, $q)) {
        $auditOfficer = mysqli_real_escape_string($conn, $officer);
        mysqli_query($conn, "INSERT INTO `criminal_profile_updates` (`criminal_id`,`officer_username`,`action`) VALUES ($criminalId,'$auditOfficer','UPDATE')");
        header("Location: search.php");
        exit();
    } else {
        $err = mysqli_error($conn);
        echo "<script>alert('Update failed: ".addslashes($err)."')</script>";
    }
}

$result = mysqli_query($conn, "SELECT * FROM `info` WHERE `id`=$criminalId");
$row = $result ? mysqli_fetch_assoc($result) : null;
if (!$row) {
    die("Record not found");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Edit Criminal</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='style_1.css'>
</head>
<body>
    <button name="logout" style="margin-left: 1424px;"><img src="logout.png" style="width:10px"><a href="logout.php">Log out</a></button>
    <div class="container" style="height:980px;">
        <div class="finaldiv">
            <span class="head1"><img src="police_logo.png" width="16.2%"></span>
            <span class="head_txt">Criminal Management System</span>
            <span class="head2"><img src="police_logo.png" width="38%"></span>
            <br>
            <div class="navbar">
                <ul style="margin-left:20px">
                    <li><a href="home.php"><b>Criminal Information</b></a></li>
                    <li><a href="search.php" class="active"><b>Search Records</b></a></li>
                    <li><a href="offList.php"><b>List of Officers</b></a></li>
                    <li><a href="analysis.php"><b>Analytics</b></a></li>
                    <li><a href="officerUpdateHistory.php"><b>My Update History</b></a></li>
                </ul>
            </div>

            <div id="crimeInfo" style="margin-top: 140px;">
                <h2 style="margin-left: 270px;">Edit Criminal (ID: <?php echo $criminalId; ?>)</h2>
                <form method="post" style="margin-left: 270px; width: 650px;">
                    <table>
                        <tr><td>Criminal Name</td><td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required></td></tr>
                        <tr><td><br></td></tr>
                        <tr><td>Assigned Officer</td><td><input type="text" name="offname" value="<?php echo htmlspecialchars($row['offname']); ?>" required></td></tr>
                        <tr><td><br></td></tr>
                        <tr><td>Crime Type</td>
                            <td>
                                <select name="crime" required>
                                    <option value="<?php echo htmlspecialchars($row['crime']); ?>"><?php echo htmlspecialchars($row['crime']); ?></option>
                                    <option value="Ragging">Ragging</option>
                                    <option value="Robbery">Robbery</option>
                                    <option value="Kidnapping">Kidnapping</option>
                                    <option value="Rape">Rape</option>
                                    <option value="Murder">Murder</option>
                                    <option value="Fraud">Fraud</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td><br></td></tr>
                        <tr><td>Section</td><td><input type="text" name="more" value="<?php echo htmlspecialchars($row['more']); ?>" required></td></tr>
                        <tr><td><br></td></tr>
                        <tr><td>Criminal DOB</td><td><input type="date" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>" required></td></tr>
                        <tr><td><br></td></tr>
                        <tr><td>Arrest Date</td><td><input type="date" name="arrDate" value="<?php echo htmlspecialchars($row['arrDate']); ?>" required></td></tr>
                        <tr><td><br></td></tr>
                        <tr><td>Date of Crime</td><td><input type="date" name="crimeDate" value="<?php echo htmlspecialchars($row['crimeDate']); ?>" required></td></tr>
                        <tr><td><br></td></tr>
                        <tr><td>Gender</td><td><input type="text" name="sex" value="<?php echo htmlspecialchars($row['sex']); ?>" required></td></tr>
                        <tr><td><br></td></tr>
                        <tr><td>Address</td><td><textarea rows="2" name="address" required><?php echo htmlspecialchars($row['address']); ?></textarea></td></tr>
                    </table>
                    <button type="submit" class="submitBtn"><b>Save Update</b></button>
                    <a href="search.php"><button type="button" class="submitBtn" style="margin-left: 10px;"><b>Cancel</b></button></a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

