<?php
   include("config.php");
   session_start();
   
   if(!isset($_SESSION['login_user'])){
      header("location: login.php");
      die();
   }

   $user_check = $_SESSION['login_user'];
   $ses_sql = mysqli_query($db,"SELECT offName, contact, role FROM registration WHERE offName = '$user_check'");
   $row_p = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
   
   $display_name = $row_p ? $row_p['offName'] : $user_check;
   $display_number = $row_p ? $row_p['contact'] : "9313086006";
   $display_role = $row_p ? $row_p['role'] : "SYSTEM ADMIN";
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset='utf-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <title>Criminal Management System - Home</title>
      <meta name='viewport' content='width=device-width, initial-scale=1'>
      <link rel='stylesheet' type='text/css' media='screen' href='style_1.css?v=<?php echo time(); ?>'>
      <style>
         body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; text-align: center; background: #f4f7f6; }
         
         .profile-container { position: absolute; top: 20px; left: 20px; z-index: 10000; }
         .profile-icon { width: 55px; height: 55px; border-radius: 50%; background: #fff; display: flex; justify-content: center; align-items: center; cursor: pointer; border: 3px solid #1a237e; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: 0.3s; }
         .profile-card { position: absolute; top: 65px; left: 0; background: white; width: 280px; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: none; flex-direction: column; border: 1px solid #e0e0e0; overflow: hidden; }
         .profile-card.active { display: flex; }
         .card-header { background: #1a237e; color: white; padding: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; text-align: center; }
         .card-body { padding: 20px; text-align: left; }
         .info-item { margin-bottom: 12px; }
         .info-label { font-size: 10px; color: #999; font-weight: 800; text-transform: uppercase; display: block; }
         .info-value { font-size: 15px; color: #1a237e; font-weight: 700; }
         .role-badge { display: inline-block; background: #ffebee; color: #d32f2f; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; margin-top: 5px; }

         .dashboard-btn-top {
             position: absolute;
             top: 20px;
             right: 20px;
             background: #1a237e;
             color: white;
             padding: 10px 25px;
             border-radius: 30px;
             text-decoration: none;
             font-weight: 700;
             font-size: 14px;
             transition: 0.3s;
             z-index: 1000;
             box-shadow: 0 4px 12px rgba(26,35,126,0.3);
         }
         .dashboard-btn-top:hover { background: #000; transform: translateY(-2px); }

         .header-main { display: flex; align-items: center; justify-content: center; padding: 20px; gap: 30px; }
         .header-main img { width: 100px; }
         .header-main h1 { font-family: 'Arial Black', sans-serif; font-size: 40px; margin: 0; font-weight: 900; color: #1a237e; }

         .navbar-custom { background: yellow; padding: 12px 0; border-bottom: 2px solid #333; margin-bottom: 30px; }
         .navbar-custom ul { list-style: none; padding: 0; margin: 0; display: flex; justify-content: center; gap: 60px; }
         .navbar-custom ul li a { text-decoration: none; color: #000; font-weight: 800; font-size: 20px; }
         .navbar-custom ul li a.active { color: #d32f2f; border-bottom: 3px solid #d32f2f; }

         .form-wrapper { max-width: 700px; margin: 0 auto 40px auto; background: white; padding: 40px; border-radius: 25px; box-shadow: 0 15px 50px rgba(0,0,0,0.1); text-align: left; }
         .form-wrapper h2 { text-align: center; margin-bottom: 30px; color: #1a237e; letter-spacing: 1px; font-weight: 900; }
         .data-entry-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
         .data-entry-table td:first-child { width: 35%; font-weight: 800; color: #555; text-transform: uppercase; font-size: 12px; text-align: left; }
         .data-entry-table input, .data-entry-table select, .data-entry-table textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; outline: none; box-sizing: border-box; transition: 0.3s; }
         .custom-file-upload {
             display: inline-flex;
             align-items: center;
             gap: 8px;
             padding: 10px 20px;
             cursor: pointer;
             background: #f0f2ff;
             color: #1a237e;
             border: 1px dashed #1a237e;
             border-radius: 8px;
             font-weight: 800;
             font-size: 12px;
             transition: 0.3s;
         }
         .custom-file-upload:hover { background: #1a237e; color: white; }
         .data-entry-table input:focus { border-color: #1a237e; box-shadow: 0 0 10px rgba(26,35,126,0.1); }
         .submit-btn-v3 { width: 100%; padding: 18px; margin-top: 15px; background: #1a237e; color: white; border: none; border-radius: 12px; font-size: 18px; font-weight: 900; cursor: pointer; transition: 0.3s; box-shadow: 0 8px 20px rgba(26,35,126,0.2); }
         .submit-btn-v3:hover { background: #000; }

         .list-section { width: 95%; max-width: 1100px; margin: 40px auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); text-align: left; }
         .list-section h3 { color: #1a237e; border-bottom: 3px solid #1a237e; padding-bottom: 10px; margin-bottom: 25px; font-weight: 900; }
         .criminal-table { width: 100%; border-collapse: collapse; background: white; }
         .criminal-table th { background: #1a237e; color: white; padding: 18px; text-align: center; border: 1px solid #ddd; font-size: 13px; font-weight: 900; text-transform: uppercase; }
         .criminal-table td { padding: 15px; border: 1px solid #eee; text-align: center; font-size: 14px; color: #333; font-weight: 600; }
         .criminal-table tr:nth-child(even) { background: #f9f9fb; }
         .criminal-table tr:hover { background: #f1f4ff; }
         .crime-badge { background: #ffebee; color: #d32f2f; padding: 5px 12px; border-radius: 6px; font-weight: 800; font-size: 11px; display: inline-block; }

         /* v3 AI Box - Neural Squircle Design */
         .ai-box-widget { position: fixed; bottom: 25px; right: 25px; z-index: 10005; }
         .ai-bubble { 
             width: 52px; 
             height: 52px; 
             background: rgba(16, 163, 127, 0.85);
             backdrop-filter: blur(10px);
             border-radius: 16px; 
             display: flex; 
             align-items: center; 
             justify-content: center; 
             cursor: pointer; 
             box-shadow: 0 0 15px rgba(16,163,127,0.3); 
             transition: 0.4s; 
             border: 1px solid rgba(255,255,255,0.3);
             animation: ai-pulse 2s infinite;
         }
         @keyframes ai-pulse {
             0% { box-shadow: 0 0 0 0 rgba(16, 163, 127, 0.7); }
             70% { box-shadow: 0 0 0 15px rgba(16, 163, 127, 0); }
             100% { box-shadow: 0 0 0 0 rgba(16, 163, 127, 0); }
         }
         .ai-bubble:hover { transform: scale(1.1); background: #10a37f; }
         .ai-window { 
             position: absolute; 
             bottom: 70px; 
             right: 0; 
             width: 380px; 
             height: 520px; 
             background: #fff; 
             border-radius: 24px; 
             box-shadow: 0 15px 50px rgba(0,0,0,0.2); 
             display: none; 
             flex-direction: column; 
             overflow: hidden; 
             border: 1px solid rgba(0,0,0,0.08); 
             animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
         }
         @keyframes popIn { from { opacity: 0; transform: translateY(20px) scale(0.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
         .ai-window.active { display: flex; }
         .ai-chat-body { flex: 1; overflow-y: auto; padding: 20px; background: #f7f7f8; display: flex; flex-direction: column; gap: 15px; }

         .shield-bg { position: fixed; top: 60%; left: 50%; transform: translate(-50%, -50%); width: 600px; opacity: 0.02; z-index: 1; pointer-events: none; }
      </style>
      <script>
         function toggleProfile() { document.getElementById('profileCard').classList.toggle('active'); }
         function toggleAI() { document.getElementById('aiWindow').classList.toggle('active'); }
         function updateFileName(input) {
             var fileName = input.files[0] ? input.files[0].name : "No file selected";
             document.getElementById('fileName').textContent = fileName;
         }
      </script>
   </head>
   <body>
      <div class="profile-container">
          <div class="profile-icon" onclick="toggleProfile()">
              <svg viewBox="0 0 24 24" fill="none" stroke="#1a237e" stroke-width="2.5" style="width: 32px; height: 32px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          </div>
          <div class="profile-card" id="profileCard">
              <div class="card-header">Officer Profile</div>
              <div class="card-body">
                  <div class="info-item"><span class="info-label">Full Name</span><div class="info-value"><?php echo $display_name; ?></div></div>
                  <div class="info-item"><span class="info-label">Contact Number</span><div class="info-value"><?php echo $display_number; ?></div></div>
                  <div class="info-item"><span class="info-label">Current Role</span><div class="role-badge"><?php echo $display_role; ?></div></div>
              </div>
          </div>
      </div>

      <a href="dashboard.php" class="dashboard-btn-top">Dashboard</a>

      <div class="header-main">
          <img src="police_logo.png">
          <h1>Criminal Management System</h1>
          <img src="police_logo.png">
      </div>

      <div class="navbar-custom">
          <ul>
             <li><a href="home.php" class="active">Criminal Information</a></li>
             <li><a href="search.php">Search Records</a></li>
             <li><a href="offList.php">List of Officers</a></li>
             <li><a href="analysis.php">Analytics</a></li>
          </ul>
      </div>

      <img src="police_logo_1.png" class="shield-bg">

      <div class="form-wrapper">
          <h2>CRIMINAL DATA ENTRY CENTER</h2>
          <form id="crimeInfo" method="post" enctype="multipart/form-data">
              <table class="data-entry-table">
                  <tr>
                      <td>Criminal's Photo</td>
                      <td style="text-align: center;">
                        <label for="imgUpload" class="custom-file-upload">
                            <svg viewBox="0 0 24 24" width="18" fill="currentColor"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/></svg> Choose Photo
                        </label>
                        <input id="imgUpload" type="file" name="my_img" required style="display:none;" onchange="updateFileName(this)">
                        <div id="fileName" style="font-size:11px; color:#666; margin-top:8px; font-weight: 700;">No file selected</div>
                      </td>
                  </tr>
                  <tr><td>Criminal ID</td><td><input type="text" name="ID" placeholder="e.g. C-101" required></td></tr>
                  <tr><td>Criminal Name</td><td><input type="text" name="name" placeholder="Full Name" required></td></tr>
                  <tr><td>Assigned Officer</td><td><input type="text" name="offName" value="<?php echo $display_name; ?>" required></td></tr>
                  <tr>
                      <td>Crime Type</td>
                      <td>
                          <select name="crime" required>
                              <option value="Murder">Murder</option>
                              <option value="Robbery">Robbery</option>
                              <option value="Kidnapping">Kidnapping</option>
                              <option value="Fraud">Fraud</option>
                              <option value="Ragging">Ragging</option>
                          </select>
                      </td>
                  </tr>
                  <tr><td>Law Section</td><td><input name="more" placeholder="e.g. IPC 302" required></td></tr>
                  <tr><td>Date of Birth</td><td><input type="date" name="dob" required></td></tr>
                  <tr><td>Arrest Date</td><td><input type="date" name="arrDate" required></td></tr>
                  <tr><td>Date of Incident</td><td><input type="date" name="crimeDate" required></td></tr>
                  <tr>
                      <td>Gender</td>
                      <td>
                        <input type="radio" name="sex" value="M" checked style="width:auto;"> Male &nbsp;&nbsp;
                        <input type="radio" name="sex" value="F" style="width:auto;"> Female
                      </td>
                  </tr>
                  <tr><td>Residential Address</td><td><textarea name="address" rows="3" placeholder="Full Address..." required></textarea></td></tr>
              </table>
              <button type="submit" name="submit" class="submit-btn-v3">SUBMIT CRIMINAL RECORD</button>
          </form>
      </div>

      <div class="list-section">
          <h3>RECENTLY REGISTERED CRIMINALS</h3>
          <table class="criminal-table">
              <thead>
                  <tr>
                      <th>Image</th>
                      <th>Case ID</th>
                      <th>Name</th>
                      <th>Crime</th>
                      <th>Arrest Date</th>
                      <th>Officer</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                      $conn_l = mysqli_connect("localhost", "root", "", "criminalinfo");
                      $res = mysqli_query($conn_l, "SELECT * FROM `info` ORDER BY id DESC LIMIT 5");
                      while($r = mysqli_fetch_assoc($res)){
                          echo "<tr>
                                  <td><img src='".$r['img']."' style='width:65px; height:65px; border-radius:10px; border:2px solid #ddd; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'></td>
                                  <td>#".$r['id']."</td>
                                  <td><b>".$r['name']."</b></td>
                                  <td><span class='crime-badge'>".$r['crime']."</span></td>
                                  <td>".$r['arrDate']."</td>
                                  <td>".$r['offname']."</td>
                                </tr>";
                      }
                  ?>
              </tbody>
          </table>
      </div>

      <!-- AI Box -->
      <div class="ai-box-widget">
          <div class="ai-window" id="aiWindow">
              <div class="ai-chat-body" id="aiChatBody">
                  <div style="background:white; padding:15px; border-radius:10px; border:1px solid #e5e5e5; font-size:14px;">Hello Officer! I'm your Neural Assistant. Ask me anything.</div>
              </div>
              <div style="padding:15px; border-top:1px solid #eee; display:flex; gap:10px;">
                  <input type="text" id="gptInput" placeholder="Ask AI..." style="flex:1; padding:10px; border-radius:10px; border:1px solid #ddd;">
                  <button onclick="sendGPTCommand()" style="background:#10a37f; color:white; border:none; padding:10px 15px; border-radius:10px; cursor:pointer;">Send</button>
              </div>
          </div>
          <div class="ai-bubble" onclick="toggleAI()">
              <span style="color:white; font-weight:900; font-family:'Segoe UI', sans-serif; font-size:16px; letter-spacing:1px;">AI</span>
          </div>
      </div>

      <script>
         function sendGPTCommand() {
             var input = document.getElementById('gptInput');
             var cmd = input.value.trim();
             if (cmd !== "") {
                 var body = document.getElementById('aiChatBody');
                 body.innerHTML += '<div style="background:#ececf1; padding:10px; border-radius:10px; align-self:flex-end; font-size:14px;">'+cmd+'</div>';
                 input.value = "";
                 fetch('chat_ai.php', { method: 'POST', body: JSON.stringify({ message: cmd }) })
                 .then(r => r.json()).then(data => {
                     body.innerHTML += '<div style="background:white; border:1px solid #e5e5e5; padding:10px; border-radius:10px; font-size:14px;">'+data.response+'</div>';
                     body.scrollTop = body.scrollHeight;
                 });
             }
         }
      </script>
   </body>
</html>
<?php
   if(isset($_POST['submit'])){
       $fname=$_FILES['my_img']['name'];
       $tmpname=$_FILES['my_img']['tmp_name'];
       $folder="images/".$fname;
       move_uploaded_file($tmpname,$folder);
       $id=$_POST['ID']; $name=$_POST['name']; $offName=$_POST['offName']; $crime=$_POST['crime']; $dob=$_POST['dob']; $arrDate=$_POST['arrDate']; $crimeDate=$_POST['crimeDate']; $sex=$_POST['sex']; $address=$_POST['address']; $more=$_POST['more'];
       $q1="INSERT INTO `info`(`id`, `name`, `offname`, `crime`, `dob`, `arrDate`, `crimeDate`, `sex`, `address`,`img`,`more`) VALUES ('$id','$name','$offName','$crime','$dob','$arrDate','$crimeDate','$sex','$address','$folder','$more')";
       if(mysqli_query($conn_l,$q1)) { echo "<script>alert('Criminal Record Added!'); window.location='home.php';</script>"; }
   }
?>