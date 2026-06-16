<?php
    include("config.php");
    session_start();
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
       $myusername = mysqli_real_escape_string($db,$_POST['uname']);
       $mypassword = mysqli_real_escape_string($db,$_POST['pass']); 
       
       // Check Admin
       $sql = "SELECT * FROM users WHERE uname = '$myusername' and pass = '$mypassword'";
       $result = mysqli_query($db, $sql);
       if(mysqli_num_rows($result) == 1) {
          $_SESSION['login_user'] = $myusername;
          header("location: dashboard.php");
          exit();
       }
       
       // Check Officer
       $sql2 = "SELECT * FROM registration WHERE offName = '$myusername' and pass = '$mypassword'";
       $result2 = mysqli_query($db, $sql2);
       if(mysqli_num_rows($result2) == 1) {
          $_SESSION['login_user'] = $myusername;
          header("location: dashboard.php");
          exit();
       }
       
       echo "<script>alert('Invalid Username or Password');</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Access </title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Hero Background with Overlay */
        .bg-image {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 27, 75, 0.8)), url('login_bg.jpg');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        /* Abstract Glows */
        .glow {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            z-index: 0;
        }
        .glow-1 { top: -100px; left: -100px; }
        .glow-2 { bottom: -100px; right: -100px; }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 35px;
            padding: 45px 25px 35px 25px;
            width: 100%;
            max-width: 350px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            position: relative;
            z-index: 10;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-box {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 22px;
            margin: 0 auto 15px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .logo-box img { width: 45px; filter: drop-shadow(0 0 10px rgba(255,255,255,0.3)); }

        h2 { font-size: 24px; font-weight: 800; color: white; margin: 0 0 5px 0; letter-spacing: -0.5px; }
        p.subtitle { color: #94a3b8; font-size: 13px; margin-bottom: 25px; font-weight: 500; }

        .input-group { text-align: left; margin-bottom: 20px; position: relative; }
        .input-group label { display: block; color: #cbd5e1; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; margin-left: 5px; }
        
        .input-wrapper { position: relative; }
        .input-wrapper input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        .input-wrapper input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #6366f1;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        }

        .show-pass-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            font-size: 13px;
            margin: -5px 0 25px 5px;
            cursor: pointer;
            transition: 0.2s;
        }
        .show-pass-toggle:hover { color: #fff; }
        .show-pass-toggle input { cursor: pointer; }

        .login-btn {
            width: 100%;
            padding: 18px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            margin-top: 5px;
        }
        .login-btn:hover { background: #4f46e5; transform: translateY(-2px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.4); }
        .login-btn:active { transform: translateY(0); }

        .footer-links { margin-top: 35px; font-size: 14px; color: #94a3b8; font-weight: 500; }
        .footer-links a { color: #6366f1; text-decoration: none; font-weight: 700; margin-left: 5px; }
        .footer-links a:hover { text-decoration: underline; }

        .badge-secure {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="bg-image"></div>
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>

    <div class="login-card">
        <div class="badge-secure">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15.5L7.5 14l1.41-1.41L11 14.67l4.59-4.59L17 11.5 11 17.5z"/></svg> 
            Secure SSL Enforced
        </div>
        
        <div class="logo-box">
            <img src="logo.jpg" alt="CMS Logo" style="border-radius: 12px;">
        </div>
        
        <h2>System Access</h2>
        <p class="subtitle">Enter your credentials to enter the node</p>
        
        <form method="post">
            <div class="input-group">
                <label>Node Identity</label>
                <div class="input-wrapper">
                    <input type="text" name="uname" placeholder="Enter Username..." required autocomplete="off">
                </div>
            </div>
            
            <div class="input-group">
                <label>Access Key</label>
                <div class="input-wrapper">
                    <input type="password" name="pass" id="loginPass" placeholder="••••••••" required>
                </div>
            </div>

            <label class="show-pass-toggle">
                <input type="checkbox" onclick="document.getElementById('loginPass').type = this.checked ? 'text' : 'password'"> 
                Reveal Access Key
            </label>

            <button type="submit" class="login-btn">Login</button>
        </form>
        
        <div class="footer-links">
            New node request? <a href="register.php"> Registration Officer</a>
        </div>
    </div>
</body>
</html>
