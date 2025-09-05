<?php
require_once 'db.php';
$pdo = getPDO();

// --- Fund Donations ---
$stmtFund = $pdo->query("
    SELECT f.f_id, f.amount, f.date, f.flag, s.full_name 
    FROM f_donation f
    LEFT JOIN student s ON s.student_id = f.student_id
    ORDER BY f.date DESC
");
$fundDonations = $stmtFund->fetchAll();

// --- Blood Donations ---
$stmtBlood = $pdo->query("
    SELECT b.id, b.donation_date, b.flag, s.full_name, s.blood_group, c.title AS camp_name, b.bag_count
    FROM blood_donation b
    LEFT JOIN student s ON s.student_id = b.student_id
    LEFT JOIN blood_donation_camp c ON c.camp_id = b.camp_id
    ORDER BY b.donation_date DESC
");
$bloodDonations = $stmtBlood->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>All Donations</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #f4f4f4;
    }
    .container {
      max-width: 1000px;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h2 {
      margin-top: 30px;
      color: #333;
    }
    .header {
      background: #0066ff;
      color: #fff;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="header">
  <div><strong>Donation History</strong></div>
  <div>
    <a href="index.php">Home</a>
  </div>
</div>

<div class="container">

  <!-- Fund Donations -->
  <h2>Fund Donations</h2>
  <?php if ($fundDonations): ?>
    <table>
      <tr>
        <th>#</th>
        <th>Donor Name</th>
        <th>Amount</th>
        <th>Date</th>
      </tr>
      <?php foreach ($fundDonations as $i => $d): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td>
            <?php if ($d['flag'] == 0): ?>
              Anonymous
            <?php else: ?>
              <?= $d['full_name'] ?? '—' ?>
            <?php endif; ?>
          </td>
          <td><?= number_format($d['amount'], 2) ?> ৳</td>
          <td><?= $d['date'] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No fund donations recorded yet.</p>
  <?php endif; ?>

  <!-- Blood Donations -->
  <h2>Blood Donations</h2>
  <?php if ($bloodDonations): ?>
    <table>
      <tr>
        <th>#</th>
        <th>Donor Name</th>
        <th>Blood Group</th>
        <th>Camp</th>
        <th>Date</th>
        <th>Bag Count</th>
      </tr>
      <?php foreach ($bloodDonations as $i => $d): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td>
            <?php if ($d['flag'] == 0): ?>
              Anonymous
            <?php else: ?>
              <?= $d['full_name'] ?? '—' ?>
            <?php endif; ?>
          </td>
          <td><?= $d['blood_group'] ?? '—' ?></td>
          <td><?= $d['camp_name'] ?? '—' ?></td>
          <td><?= $d['donation_date'] ?></td>
          <td><?= $d['bag_count'] ?>  ❤️</td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No blood donations recorded yet.</p>
  <?php endif; ?>

</div>
</body>
</html>
