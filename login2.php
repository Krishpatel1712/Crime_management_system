<?php
   include("config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($db,$_POST['uname']);
      $mypassword = mysqli_real_escape_string($db,$_POST['pass']); 
      
      $sql = "SELECT * FROM officer WHERE offName = '$myusername' and offID = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      
      $count = mysqli_num_rows($result);
      
		
      if($count == 1) {
         $_SESSION['login_user'] = $myusername;
         
         header("location: Officers/addOfficer.php");
      }else {
         echo "<script>alert('".'Invalid Username or Password'."')</script>";
      }
   }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Officer Portal | Login</title>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css?v=<?php echo time(); ?>'>
</head>
<body>
    <div id="box1">
        <span id="sp1"><img src="logo.jpg" width="80px"></span>
        <h2 style="color: #1a237e;">Officer Login</h2>
        <p style="color: #666; font-size: 14px;">Officer Management System</p>
        
        <form method="post" style="width: 100%;">
            <label>Username</label>
            <input type="text" class="inp" name="uname" placeholder="Enter your Username" required>
            
            <label>Password</label>
            <input type="password" class="inp" name="pass" id="pass-inp" placeholder="Enter your password" required>

            <div style="margin-bottom: 25px; font-size: 14px; display: flex; align-items: center; width: 100%; justify-content: flex-start;">
                <input type="checkbox" id="show-pass" onclick="togglePassword()" style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;"> 
                <label for="show-pass" style="cursor: pointer; color: #555; margin-bottom: 0; width: auto;">Show Password</label>
            </div>
            
            <button class="btn">Login to Portal</button>
        </form>
        <a href="index.php" style="margin-top: 25px; font-size: 14px; color: #1a237e; text-decoration: none; font-weight: 600; display: inline-block;">← Back to Portal</a>
    </div>
    
    <script>
        function togglePassword() {
            var passInput = document.getElementById("pass-inp");
            if (passInput.type === "password") {
                passInput.type = "text";
            } else {
                passInput.type = "password";
            }
        }
    </script>
</body>
</html>
