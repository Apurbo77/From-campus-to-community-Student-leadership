<?php
require_once 'db.php';
$pdo = getPDO();

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name']);
  $phone = trim($_POST['phone']);
  $area = trim($_POST['area']);
  $city = trim($_POST['city']);
  $profile_url = trim($_POST['profile_url']);
  $bg = trim($_POST['blood_group']);
  $gsuit = trim($_POST['gsuit']);
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password_hash'] ?? '';
  $student_id = trim($_POST['student_id'] ?? '');

  try {
    // Check if student ID exists if provided
    if ($student_id !== '') {
      $check = $pdo->prepare("SELECT 1 FROM student WHERE student_id = ?");
      $check->execute([$student_id]);
      if ($check->fetchColumn()) {
        throw new Exception("Student ID already exists. Please choose a different ID.");
      }
    }

    // create student account
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    if ($student_id !== '') {
      $stmt = $pdo->prepare("INSERT INTO student (student_id, full_name, phone, area, city, profile_url, gsuit, blood_group, username, password) 
                            VALUES (:id, :name, :phone, :area, :city, :profile_url, :gsuit, :bg, :u, :ph)");
      $params = [
        ':id' => $student_id,
        ':name' => $name,
        ':phone' => $phone,
        ':area' => $area,
        ':city' => $city,
        ':profile_url' => $profile_url,
        ':gsuit' => $gsuit,
        ':bg' => $bg,
        ':u' => $username,
        ':ph' => $hash
      ];
    } else {
      $stmt = $pdo->prepare("INSERT INTO student (full_name, phone, area, city, profile_url, gsuit, blood_group, username, password) 
                            VALUES (:name, :phone, :area, :city, :profile_url, :gsuit, :bg, :u, :ph)");
      $params = [
        ':name' => $name,
        ':phone' => $phone,
        ':area' => $area,
        ':city' => $city,
        ':profile_url' => $profile_url,
        ':gsuit' => $gsuit,
        ':bg' => $bg,
        ':u' => $username,
        ':ph' => $hash
      ];
    }
    
    $stmt->execute($params);
    header('Location: index.php');
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

$error = $error ?? '';
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register - Training</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<div class="header"><div>Register for Training / Create Profile</div><div><a href="index.php" style="color:#fff">Home</a></div></div>
<div class="container">
  <form method="post" class="card" style="max-width:700px">
    <h3>Create / Sign up</h3>
    <?php if ($error): ?>
      <div style="background:#fee2e2; color:#991b1b; padding:10px; margin-bottom:15px; border-radius:4px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <div style="margin-bottom:15px">
      <label style="display:block; margin-bottom:5px;">Student ID</label>
      <input class="input" name="student_id" type="number" placeholder="Student ID" required>
    </div>

    <div><input class="input" name="name" placeholder="Full name" required></div>
    <div style="margin-top:8px"><input class="input" name="phone" placeholder="Phone" required></div>

    <div style="margin-top:8px"><input class="input" name="gsuit" placeholder="Email" required></div>

    <div style="margin-top:8px"><input class="input" name="area" placeholder="Area (e.g., Dhanmondi)" required></div>
    <div style="margin-top:8px"><input class="input" name="city" placeholder="City (e.g., Dhanmondi)" required></div>
    <div style="margin-top:8px"><input class="input" name="profile_url" placeholder="FB_ID" required></div>

    <div style="margin-top:8px"><select class="input" name="blood_group" required>
      <option value="">Select blood group</option>
      <option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
    </select></div>
    <div style="margin-top:8px"><input class="input" name="username" placeholder="username (for login)"></div>
    <div style="margin-top:8px"><input class="input" type="password" name="password_hash" placeholder="password"></div>

    <div style="margin-top:12px"><button class="btn">Create profile</button></div>
  </form>
</div>
</body></html>
