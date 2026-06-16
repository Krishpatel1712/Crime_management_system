<?php
    include("config.php");
    session_start();
    
    if(!isset($_SESSION['login_user'])){
       header("location: index.php");
       die();
    }

    // Fetch Global Statistics for the Dashboard
    $res_crim = mysqli_query($db, "SELECT COUNT(*) FROM info");
    $total_criminals = mysqli_fetch_array($res_crim)[0];

    $res_off = mysqli_query($db, "SELECT COUNT(*) FROM officer");
    $total_officers = mysqli_fetch_array($res_off)[0];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Master Command Center | CMS</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: white;
            overflow: hidden;
        }

        /* Abstract Background Elements */
        .bg-glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26,35,126,0.3) 0%, rgba(0,0,0,0) 70%);
            z-index: -1;
            filter: blur(50px);
        }
        .bg-glow.one { top: -10%; left: -10%; }
        .bg-glow.two { bottom: -10%; right: -10%; }

        .header-section { text-align: center; margin-bottom: 50px; }
        .header-section h1 { 
            font-size: 45px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 4px; 
            margin: 0;
            background: linear-gradient(to right, #ffffff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header-section p { color: #94a3b8; font-size: 14px; margin-top: 10px; font-weight: 600; letter-spacing: 2px; }

        .stats-strip {
            display: flex;
            gap: 40px;
            margin-bottom: 60px;
        }
        .stat-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            padding: 15px 40px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .stat-value { font-size: 30px; font-weight: 900; color: #fff; display: block; }
        .stat-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; font-weight: 700; }

        .portal-grid {
            display: flex;
            gap: 50px;
            max-width: 1200px;
            width: 90%;
            justify-content: center;
        }
        
        .portal-card {
            background: rgba(30, 41, 59, 0.7);
            width: 380px;
            padding: 50px 30px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }
        
        .portal-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.05), transparent);
            transform: translateX(-100%);
            transition: 0.6s;
        }
        
        .portal-card:hover {
            transform: translateY(-15px);
            border-color: rgba(255,255,255,0.3);
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        }
        .portal-card:hover::before { transform: translateX(100%); }

        .icon-box {
            width: 80px;
            height: 80px;
            background: #1e293b;
            border-radius: 20px;
            margin: 0 auto 30px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .portal-card.primary .icon-box { background: linear-gradient(135deg, #1d4ed8, #1e40af); }
        .portal-card.secondary .icon-box { background: linear-gradient(135deg, #4338ca, #3730a3); }

        .portal-card h3 { font-size: 24px; margin-bottom: 15px; font-weight: 800; }
        .portal-card p { color: #94a3b8; font-size: 14px; line-height: 1.6; margin-bottom: 35px; }
        
        .action-btn {
            background: white;
            color: #1a237e;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .portal-card:hover .action-btn { background: #fff; transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

        .logout-pill {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 25px;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }
        .logout-pill:hover { background: #d32f2f; border-color: #d32f2f; }

        .footer-tag { position: fixed; bottom: 20px; color: rgba(255,255,255,0.2); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 4px; }
    </style>
</head>
<body>
    <div class="bg-glow one"></div>
    <div class="bg-glow two"></div>

    <a href="logout.php" class="logout-pill">System Logout</a>

    <div class="header-section">
        <h1>Admin Control Panel</h1>
        <p>CENTRALIZED MANAGEMENT INTERFACE</p>
    </div>

    <div class="stats-strip">
        <div class="stat-item">
            <span class="stat-value"><?php echo $total_criminals; ?></span>
            <span class="stat-label">Criminal Records</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo $total_officers; ?></span>
            <span class="stat-label">Active Officers</span>
        </div>
    </div>

    <div class="portal-grid">
        <div class="portal-card primary" onclick="window.location.href='home.php'">
            <div class="icon-box">
                <svg viewBox="0 0 24 24" width="40" height="40" fill="white">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.12v4.7c0 4.67-3.13 8.75-7 9.81-3.87-1.06-7-5.14-7-9.81v-4.7l7-3.12zM12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zm0 2c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3z"/>
                </svg>
            </div>
            <h3>Criminal Cases</h3>
            <p>Access the core investigation database, manage criminal profiles, and analyze case statistics.</p>
            <button class="action-btn">Open Hub</button>
        </div>

        <div class="portal-card secondary" onclick="window.location.href='Officers/addOfficer.php'">
            <div class="icon-box">
                <svg viewBox="0 0 24 24" width="40" height="40" fill="white"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <h3>Officer Records</h3>
            <p>Manage personnel information, weapon assignments, and duty rosters across the department.</p>
            <button class="action-btn">Enter Portal</button>
        </div>
    </div>

    <div class="footer-tag">Department of Justice | Secure Access Node</div>
</body>
</html>
