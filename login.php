<?php
    include("config.php");
    session_start();
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
       $myusername = mysqli_real_escape_string($db,$_POST['uname']);
       $mypassword = mysqli_real_escape_string($db,$_POST['pass']); 
       
       // Priority 1: Check Admin (users table)
       $sql = "SELECT * FROM users WHERE uname = '$myusername' and pass = '$mypassword'";
       $result = mysqli_query($db, $sql);
       if(mysqli_num_rows($result) == 1) {
          $_SESSION['login_user'] = $myusername;
          $_SESSION['user_type'] = 'admin';
          header("location: index.php");
          exit();
       }
       
       // Priority 2: Check Officer (registration table)
       $sql2 = "SELECT * FROM registration WHERE offName = '$myusername' and pass = '$mypassword'";
       $result2 = mysqli_query($db, $sql2);
       if(mysqli_num_rows($result2) == 1) {
          $_SESSION['login_user'] = $myusername;
          $_SESSION['user_type'] = 'officer';
          header("location: index.php");
          exit();
       }
       
       echo "<script>alert('Invalid Username or Password');</script>";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Secure Login | CMS</title>
    <link rel='stylesheet' type='text/css' href='style.css?v=<?php echo time(); ?>'>
    <style>
        body { background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('login_bg.jpg'); background-size: cover; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; font-family: 'Segoe UI', sans-serif; }
        #login-box { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); width: 380px; text-align: center; }
        .logo-container img { width: 80px; border-radius: 50%; border: 3px solid #1a237e; padding: 5px; margin-bottom: 20px; }
        h2 { color: #1a237e; margin-bottom: 5px; text-transform: uppercase; }
        .form-group { text-align: left; margin-bottom: 15px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; }
        .input-field { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .login-btn { background: #1a237e; color: white; border: none; width: 100%; padding: 14px; border-radius: 8px; font-weight: bold; cursor: pointer; text-transform: uppercase; margin-top: 10px; }
        .login-btn:hover { background: #0d124a; }
        .reg-link { margin-top: 25px; font-size: 13px; color: #777; }
    </style>
</head>
<body>
    <div id="login-box">
        <div class="logo-container"><img src="logo.jpg" alt="Logo"></div>
        <h2>Secure Login</h2>
        <p>Admin & Officer Access</p>
        <form method="post">
            <div class="form-group"><label>Username / Name</label><input type="text" class="input-field" name="uname" required></div>
            <div class="form-group"><label>Password</label><input type="password" class="input-field" name="pass" required id="p-in"></div>
            <label style="cursor:pointer; font-size: 12px;"><input type="checkbox" onclick="document.getElementById('p-in').type = this.checked ? 'text' : 'password'"> Show Password</label>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="reg-link">New Officer? <a href="register.php" style="color:#1a237e; font-weight:bold; text-decoration:none;">Register Now</a></div>
    </div>
</body>
</html>
