<?php
include("config.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $offName = mysqli_real_escape_string($db, $_POST['offName']);
    $offID = mysqli_real_escape_string($db, $_POST['offID']);
    $password = mysqli_real_escape_string($db, $_POST['pass']);
    $contact = mysqli_real_escape_string($db, $_POST['contact']);
    $gender = mysqli_real_escape_string($db, $_POST['gender']);
    $role = mysqli_real_escape_string($db, $_POST['role']);
    
    $check = mysqli_query($db, "SELECT * FROM registration WHERE offID = '$offID' OR offName = '$offName'");
    if(mysqli_num_rows($check) > 0) {
        echo "<script>alert('Officer Name or Badge ID already exists!');</script>";
    } else {
        $sql = "INSERT INTO registration (offName, pass, offID, contact, gender, role) 
                VALUES ('$offName', '$password', '$offID', '$contact', '$gender', '$role')";
        if(mysqli_query($db, $sql)) {
            echo "<script>alert('Registration Successful!'); window.location.href = 'index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . mysqli_error($db) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Node Initialization | Assistant CMS</title>
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
            overflow-x: hidden;
            position: relative;
        }

        .bg-image {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 27, 75, 0.85)), url('login_bg.jpg');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .glow {
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
            z-index: 0;
        }
        .glow-1 { top: -200px; left: -200px; }
        .glow-2 { bottom: -2100px; right: -200px; }

        .reg-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px;
            padding: 40px 45px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 70px -15px rgba(0, 0, 0, 0.7);
            position: relative;
            z-index: 10;
            margin: 40px 0;
            animation: slideUp 0.7s cubic-bezier(0.19, 1, 0.22, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { font-size: 30px; font-weight: 800; color: white; margin: 0 0 10px 0; text-align: center; }
        p.subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 35px; text-align: center; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: left; }
        .full-width { grid-column: span 2; }

        .input-group label { display: block; color: #cbd5e1; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; margin-left: 5px; }
        .input-wrapper input, .input-wrapper select {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box;
        }
        .input-wrapper select { cursor: pointer; }
        .input-wrapper select option { background: #1e1b4b; color: white; }
        
        .input-wrapper input:focus, .input-wrapper select:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: #6366f1;
            box-shadow: 0 0 12px rgba(99, 102, 241, 0.15);
        }

        .reg-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: 0.3s;
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.25);
            margin-top: 30px;
        }
        .reg-btn:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(99, 102, 241, 0.35); filter: brightness(1.1); }

        .back-node { 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
            color: #94a3b8;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .back-node:hover { color: white; }
        .back-node svg { width: 18px; height: 18px; transition: 0.3s; }
        .back-node:hover svg { transform: translateX(-5px); }

        .badge-init {
            display: table;
            margin: 0 auto 20px auto;
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }
    </style>
</head>
<body>
    <div class="bg-image"></div>
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>

    <div class="reg-card">
        <div class="badge-init">System Personnel Registration</div>
        <h2>Join the Registration Officer</h2>
        <p class="subtitle">Securely initialize your officer credentials</p>
        
        <form method="post">
            <div class="form-grid">
                <div class="input-group full-width">
                    <label>Full Officer Identity</label>
                    <div class="input-wrapper"><input type="text" name="offName" placeholder="Full Name" required></div>
                </div>
                
                <div class="input-group">
                    <label>Badge ID (Node ID)</label>
                    <div class="input-wrapper"><input type="number" name="offID" placeholder="Ex: 8820" required></div>
                </div>

                <div class="input-group">
                    <label>Assigned Rank</label>
                    <div class="input-wrapper">
                        <select name="role" required>
                            <option value="">Select Rank</option>
                            <option value="Sr.PI">Sr.PI</option>
                            <option value="API">API</option>
                            <option value="PSI">PSI</option>
                            <option value="HC">Head Constable</option>
                            <option value="Constable">Constable</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label>Secure Contact</label>
                    <div class="input-wrapper"><input type="text" name="contact" placeholder="+91..." required></div>
                </div>

                <div class="input-group">
                    <label>Assigned Gender</label>
                    <div class="input-wrapper">
                        <select name="gender" required>
                            <option value="">Select</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label>Establish Access Key (Password)</label>
                    <div class="input-wrapper"><input type="password" name="pass" placeholder="Establish Secure Password" required></div>
                </div>
            </div>

            <button type="submit" class="reg-btn">Complete Initialization</button>
        </form>
        
        <a href="index.php" class="back-node">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Return to Authentication Node
        </a>
    </div>
</body>
</html>
