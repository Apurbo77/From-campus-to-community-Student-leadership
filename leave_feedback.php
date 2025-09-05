<?php
require_once 'db.php';
$pdo = getPDO();
$student = isset($_GET['student']) ? (int)$_GET['student'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $sid      = intval($_POST['student_id']);
  $rating   = intval($_POST['rating']);
  $comment  = trim($_POST['comment']);
  $given_by = trim($_POST['given_by']);
  $phone    = trim($_POST['phone']);
  $flag=1; // default to anonymous


  $stmt = $pdo->prepare("INSERT INTO feedback (student_id, rating, comment, given_by, phone, flag) 
                         VALUES (:sid, :rating, :c, :g, :ph, :flag)");
  $stmt->execute([
    ':sid'=>$sid,
    ':rating'=>$rating,
    ':c'=>$comment, // store phone along with comment
    ':g'=>$given_by,
    ':ph'=>$phone,
    ':flag'=>$flag
  ]); 
}

$students = $pdo->query("SELECT student_id, full_name, blood_group FROM student ORDER BY full_name")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Leave Feedback</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>



  <div class="header">
  <div><strong>Feedback</strong></div>
  <div>
    <a href="index.php" style="color:#fff;">Home</a>
    
  </div>
</div>


<div class="container card">
  <h1>Leave feedback</h1>
  <form method="post">
   
    <label>Select Student</label>
    <select class="input" name="student_id" required>
          <?php foreach($students as $s): ?>
            <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['full_name']) ?> (<?= e($s['blood_group'])?>)</option>
          <?php endforeach; ?>
        </select>

    <hr>
    <label>Your Name</label>
    <input class="input" name="given_by" placeholder="Your name" required><hr>
    <label>Your Phone Number
    </label>
    <input class="input" name="phone" placeholder="Your phone number" required><hr>
    
    <label>Rating</label>
    <select name="rating" class="input">
      <option value="5">5 — Excellent</option>
      <option value="4">4 — Good</option>
      <option value="3">3 — Neutral</option>
      <option value="2">2 — Poor</option>
      <option value="1">1 — Bad</option>
    </select>

    <hr>

     <label>Anonymous?</label>
        <select name="flag" class="input" required>
        <option value="">Select your option</option>
        <option value="1">No</option>
        <option value="0">Yes</option>
        </select>


    <hr>

    <label>Comment</label><hr>
    <textarea class="input" name="comment" placeholder="Comment" style="height:120px"></textarea><hr>

    <div style="margin-top:8px">
      <button class="btn">Submit</button>
    </div>
  </form>

                <div class="footer">
              Created by Tasmim Ratri
    </div>



</div>
</body>
</html>
