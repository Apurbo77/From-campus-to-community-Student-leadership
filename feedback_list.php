<?php
require_once 'db.php';
$pdo = getPDO();

$stmt = $pdo->query("
    SELECT f.admin_id, f.student_id, f.rating, f.comment, f.given_by, f.phone, f.flag, f.created_at,
           s.full_name
    FROM feedback f
    JOIN student s ON s.student_id = f.student_id
    ORDER BY s.full_name ASC, f.created_at DESC
");
$feedbacks = $stmt->fetchAll();

// group by student
$grouped = [];
foreach ($feedbacks as $fb) {
    $grouped[$fb['full_name']][] = $fb;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>All Feedback by Student</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { font-family: Arial, sans-serif; background:#f0f2f5; margin:0; }
    .container { max-width: 1000px; margin:30px auto; padding:20px; }
    h2 { text-align:center; margin-bottom:30px; }
    .student-block { margin-bottom:40px; }
    .header {
      background:#007bff;
      color:#fff;
      padding:15px 30px;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .header a {
      color:#fff;
      text-decoration:none;
      padding:5px 15px;
    }
    .student-block h3 {
      background:#007bff;
      color:#fff;
      padding:10px 15px;
      border-radius:6px;
    }
    .feedback-box {
      background:#fff;
      margin:15px 0;
      padding:15px;
      border-radius:8px;
      box-shadow:0 2px 5px rgba(0,0,0,0.1);
    }
    .feedback-header {
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:10px;
    }
    .given-by {
      font-weight:bold;
      color:#333;
    }
    .rating {
      color:#ff9800;
      font-weight:bold;
    }
    .comment {
      font-size:15px;
      margin-bottom:8px;
    }
    .meta {
      font-size:13px;
      color:#666;
    }
  </style>
</head>
<body>
<div class="header">
  <div><strong>Student Feedback List</strong></div>
  <div>
    <a href="index.php">Home</a>
  </div>
</div>
    

<div class="container">
  <h2>📝 Feedback Sorted by Student</h2>

      
    

    

  <?php if ($grouped): ?>
    <?php foreach ($grouped as $studentName => $items): ?>
      <div class="student-block">
        <h3>👤 <?= e($studentName) ?></h3>
        <?php foreach ($items as $fb): ?>
          <div class="feedback-box">
            <div class="feedback-header">
              <span class="given-by">
                <?php if ($fb['flag'] == 0): ?>
                  Anonymous
                <?php else: ?>
                  <?= e($fb['given_by']) ?> (📞 <?= e($fb['phone']) ?>)
                <?php endif; ?>
              </span>
              <span class="rating">⭐ <?= e($fb['rating']) ?>/5</span>
            </div>
            <p class="comment"><?= nl2br(e($fb['comment'])) ?></p>
            <p class="meta">📅 <?= e($fb['created_at']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No feedback found.</p>
  <?php endif; ?>
</div>
</body>
</html>
