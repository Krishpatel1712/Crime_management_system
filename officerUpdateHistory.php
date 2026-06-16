<?php
session_start();

$officer = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : '';
if ($officer === '') {
    echo "<script>alert('Please login first');</script>";
    header("Location: index.php");
    exit();
}

$servername="localhost";
$username="root";
$pass="";
$db="criminalinfo";
$conn=mysqli_connect($servername,$username,$pass,$db);
if (!$conn) {
    die("Database connection failed");
}

// Ensure audit table exists
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

$offEsc = mysqli_real_escape_string($conn, $officer);
$qCount = mysqli_query($conn, "SELECT COUNT(DISTINCT criminal_id) AS c FROM criminal_profile_updates WHERE officer_username='$offEsc'");
$rowCount = $qCount ? mysqli_fetch_assoc($qCount) : ['c' => 0];
$distinctCount = intval($rowCount['c'] ?? 0);

$qList = mysqli_query($conn, "SELECT criminal_id, action, updated_at FROM criminal_profile_updates WHERE officer_username='$offEsc' ORDER BY updated_at DESC LIMIT 200");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>My Update History</title>
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
                    <li><a href="search.php"><b>Search Records</b></a></li>
                    <li><a href="offList.php"><b>List of Officers</b></a></li>
                    <li><a href="analysis.php"><b>Analytics</b></a></li>
                    <li><a href="officerUpdateHistory.php" class="active"><b>My Update History</b></a></li>
                </ul>
            </div>

            <div style="margin-top: 140px; margin-left: 120px;">
                <h2>Officer: <span class="truncate" title="<?php echo htmlspecialchars($officer); ?>"><?php echo htmlspecialchars($officer); ?></span></h2>
                <h3>Total criminals updated: <?php echo $distinctCount; ?></h3>

                <div class="table-wrap" style="margin-top: 20px;">
                    <table class="cms-table">
                        <tr>
                            <th>Criminal ID</th>
                            <th>Action</th>
                            <th>Updated At</th>
                            <th>Open</th>
                        </tr>
                        <?php
                        if ($qList) {
                            while ($r = mysqli_fetch_assoc($qList)) {
                                $cid = intval($r['criminal_id']);
                                $act = htmlspecialchars($r['action']);
                                $ts = htmlspecialchars($r['updated_at']);
                                echo "<tr>
                                    <td>$cid</td>
                                    <td>$act</td>
                                    <td>$ts</td>
                                    <td><a href='editCriminal.php?id=$cid'><button type='button' class='submitBtn'>View</button></a></td>
                                </tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

