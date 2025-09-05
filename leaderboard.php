<?php
require_once 'db.php';
$pdo = getPDO();

// fetch leaderboard sorted by finance_score
$stmt = $pdo->query("
    SELECT f.id, f.name, f.finance_score, f.flag, s.full_name 
    FROM d_financial f
    LEFT JOIN student s ON s.student_id = f.student_id
    ORDER BY f.finance_score DESC
    LIMIT 20
");
$leaders = $stmt->fetchAll();


// --- Fetch Blood Score  ---
$stmtBlood = $pdo->query("
    SELECT s.student_id, b.name, b.blood_score, b.blood_group,b.flag
    FROM student s
    LEFT JOIN d_blood b ON b.student_id = s.student_id
    ORDER BY b.blood_score DESC
    LIMIT 20
    
");
$blood = $stmtBlood->fetchAll();



?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Financial Leaderboard</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { font-family: Arial, sans-serif; background:#f9f9f9; }
    .container {
      max-width: 700px;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h2 { text-align:center; }
    table {
      width:100%;
      border-collapse: collapse;
      margin-top:20px;
    }
    th, td {
      padding:10px;
      border:1px solid #ddd;
      text-align:center;
    }
    th { background:#f4f4f4; }
    tr:nth-child(1) td { background:#ffd700; font-weight:bold; } /* gold */
    tr:nth-child(2) td { background:#c0c0c0; font-weight:bold; } /* silver */
    tr:nth-child(3) td { background:#cd7f32; font-weight:bold; } /* bronze */
  </style>
</head>
<body>
      <div class="header">
  <div><strong>Leaderboard</strong></div>
  <div>
    <a href="index.php" style="color:#fff;margin-right:12px;">Home</a>
    <a href="donation_camp.php" style="color:#fff;">If you want to climb up</a>

</div>
</div>

<div class="container">
  <h2>💰 Financial Leaderboard</h2>
  <table>
    <tr>
      <th>Rank</th>
      <th>Name</th>
      <th>Finance Score</th>
    </tr>
    <?php $rank=1; foreach ($leaders as $l): ?>
      <tr>
        <td><?= $rank++ ?></td>
        <td>
          <?php if ($l['flag']==0): ?>
            Anonymous
          <?php else: ?>
            <?= e($l['full_name'] ?? $l['name']) ?>
          <?php endif; ?>
        </td>
        <td><?= e($l['finance_score']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

</div>

<div class="container">
  <h2>💰 Blood Leaderboard</h2>
  <table>
    <tr>
      <th>Rank</th>
      <th>Name</th>
      <th>Blood Score</th>
    </tr>
    <?php $rank=1; foreach ($blood as $b): ?>
      <tr>
        <td><?= $rank++ ?></td>
        <td>
          <?php if ($b['flag']==0): ?>
            Anonymous
          <?php else: ?>
            <?= e($b['full_name'] ?? $b['name']) ?>
          <?php endif; ?>
        </td>
        <td><?= e($b['blood_score']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

</div>


</body>
</html>
