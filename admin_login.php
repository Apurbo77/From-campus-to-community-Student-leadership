<?php

require_once 'db.php';
$pdo = getPDO();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email=:email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials.";
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container card" style="max-width:400px;margin-top:50px">
    <div>
    <a href="index.php" class="btn" style="display:inline-block;margin-top:6px">Home</a>
    </div>
  <h3>Admin Login</h3>
  <!-- <?= password_hash("admin123", PASSWORD_DEFAULT); ?> -->
  <?php if($error): ?>
    <div style="color:red;margin-bottom:8px"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="post">
    <input class="input" type="email" name="email" placeholder="Email" required>
    <input class="input" type="password" name="password" placeholder="Password" required>
    <button class="btn" style="margin-top:10px">Login</button>
  </form>
</div>
</body>
</html>
