<?php
require_once 'db.php';
$pdo = getPDO();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gsuit = trim($_POST['gsuit'] ?? '');
    $password = $_POST['password'] ?? '';


    // check student by gsuit column
    $stmt = $pdo->prepare("SELECT * FROM student WHERE gsuit = :gsuit LIMIT 1");
    $stmt->execute([':gsuit' => $gsuit]);
    $student = $stmt->fetch();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id']   = $student['student_id'];
        $_SESSION['student_name'] = $student['full_name'];
        header("Location: training.php");
        exit;
    } else {
        $error = "Invalid G-Suite or password.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Student Login</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .header {
      background: #007bff;
      color: #fff;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header a {
      color: #fff;
      text-decoration: none;
    }
    .login-box {
      max-width: 400px;
      margin: 60px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h2 { margin-bottom: 15px; }
  </style>
</head>
<body>
<div class="header">
  <div><strong>Training Login</strong></div>
  <div>
    <a href="index.php">Home</a>
  </div>
</div>
<div class="login-box">
  <h2>Student Login</h2>
  <?php if ($error): ?>
    <p style="color:red"><?= e($error) ?></p>
  <?php endif; ?>
  <form method="post">
    <input type="email" class="input" name="gsuit" placeholder="Enter your G-Suite email" required>
    <input type="password" class="input" name="password" placeholder="Password" required>
    <button type="submit" class="btn" style="margin-top:10px">Login</button>
  </form>
</div>
</body>
</html>
