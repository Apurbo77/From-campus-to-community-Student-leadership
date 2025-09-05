<?php
require_once 'db.php';
$pdo = getPDO();

if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit;
}

$student_id = $_SESSION['student_id'];

// --- Fetch student profile ---
$stmt = $pdo->prepare("SELECT * FROM student WHERE student_id = :id");
$stmt->execute([':id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student profile not found.");
}

// Handle blood request status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_request'])) {
    $request_id = intval($_POST['req_id']);
    try {
        $updateStmt = $pdo->prepare("UPDATE blood_request SET status = 'closed' WHERE req_id = :id");
        $updateStmt->execute([':id' => $request_id]);
        $success_message = "Blood request status updated successfully.";
    } catch (Exception $e) {
        $error_message = "Error updating blood request status.";
    }
}

// Fetch matching blood requests
$blood_group = $student['blood_group'];
$stmt = $pdo->prepare("
    SELECT req_id, name, phone_no, location, urgency_level, created_at, status
    FROM blood_request 
    WHERE blood_group = :blood_group 
    AND status = 'Open'
    ORDER BY 
        CASE urgency_level
            WHEN 'Critical' THEN 1
            WHEN 'Urgent' THEN 2
            WHEN 'Normal' THEN 3
        END,
        created_at DESC
");
$stmt->execute([':blood_group' => $blood_group]);
$matching_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle availability status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    if (in_array($new_status, ['Available', 'Not Available'])) {
        $updateStmt = $pdo->prepare("UPDATE student SET available = :status WHERE student_id = :id");
        $updateStmt->execute([':status' => $new_status, ':id' => $student_id]);
        // Refresh student data after update
        $stmt->execute([':id' => $student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// --- Fetch fund donations ---
$stmtFund = $pdo->prepare("
    SELECT amount, date, flag 
    FROM f_donation 
    WHERE student_id = :id
    ORDER BY date DESC
");
$stmtFund->execute([':id' => $student_id]);
$funds = $stmtFund->fetchAll();

// --- Fetch blood donations ---
$stmtBlood = $pdo->prepare("
    SELECT b.donation_date, b.flag, c.title AS camp_name, s.blood_group
    FROM blood_donation b
    LEFT JOIN blood_donation_camp c ON c.camp_id = b.camp_id
    LEFT JOIN student s ON s.student_id = b.student_id
    WHERE b.student_id = :id
    ORDER BY b.donation_date DESC
");
$stmtBlood->execute([':id' => $student_id]);
$bloods = $stmtBlood->fetchAll();

// --- Fetch certification status ---
$stmtCert = $pdo->prepare("
    SELECT c.*, s.full_name, s.blood_group, s.gsuit
    FROM certification c
    LEFT JOIN student s ON s.student_id = c.student_id
    WHERE c.student_id = :id
    ORDER BY c.date_awarded DESC
    LIMIT 1
");
$stmtCert->execute([':id' => $student_id]);
$certification = $stmtCert->fetch();

// --- Fetch feedbacks ---
$stmtFeedback = $pdo->prepare("
    SELECT rating, comment, given_by, phone, created_at, flag 
    FROM feedback 
    WHERE student_id = :id 
    ORDER BY created_at DESC
");
$stmtFeedback->execute([':id' => $student_id]);
$feedbacks = $stmtFeedback->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($student['full_name']) ?> - Profile</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { background:#f4f6f9; font-family: Arial, sans-serif; margin:0; }
    .header {
      background:#007bff;
      color:#fff;
      padding:15px 30px;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .header a { color:#fff; margin-left:15px; text-decoration:none; }
    .container {
      max-width: 1100px;
      margin: 30px auto;
      padding: 20px;
    }
    .card {
      background:#fff;
      border-radius:8px;
      padding:20px;
      margin-bottom:20px;
      box-shadow:0 2px 6px rgba(0,0,0,0.1);
    }
    h1 { margin:0 0 10px; }
    h2 { margin-top:0; color:#333; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th, td { padding:10px; border-bottom:1px solid #eee; text-align:left; }
    th { background:#f9f9f9; }
    .info { font-size:15px; line-height:1.6; }
    .tag {
      display:inline-block; background:#e7f1ff; color:#0056b3;
      padding:3px 8px; border-radius:12px; font-size:12px; margin-left:8px;
    }
    .table-responsive {
      overflow-x: auto;
    }
    .urgency-critical {
      background: #dc3545;
      color: white;
    }
    .urgency-urgent {
      background: #ffc107;
      color: #000;
    }
    .urgency-normal {
      background: #28a745;
      color: white;
    }
    .alert {
      padding: 10px 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    .alert-success {
      background-color: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
    }
    .alert-error {
      background-color: #f8d7da;
      border-color: #f5c6cb;
      color: #721c24;
    }
    .inline-form {
      display: inline-block;
      margin: 0;
    }
    .close-btn {
      background: #6c757d;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
    }
    .close-btn:hover {
      background: #5a6268;
    }
  </style>
</head>
<body>
<div class="header">
  <div><strong>Student Profile</strong></div>
  <div>
    <a href="index.php">Home</a>
    <a href="student_logout.php">Logout</a>
  </div>
</div>

<div class="container">

  <!-- Blood Request Matches -->
  <?php if (!empty($matching_requests)): ?>
  <div class="card">
    <h2>🩸 Matching Blood Requests</h2>
    
    <?php if (isset($success_message)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Location</th>
            <th>Urgency</th>
            <th>Requested Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($matching_requests as $request): ?>
            <tr>
              <td><?= htmlspecialchars($request['name']) ?></td>
              <td><?= htmlspecialchars($request['phone_no']) ?></td>
              <td><?= htmlspecialchars($request['location']) ?></td>
              <td>
                <span class="tag urgency-<?= strtolower($request['urgency_level']) ?>">
                  <?= htmlspecialchars($request['urgency_level']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars(date('M j, Y', strtotime($request['created_at']))) ?></td>
              <td>
                <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to mark this request as closed?');">
                  <input type="hidden" name="req_id" value="<?= $request['req_id'] ?>">
                  <button type="submit" name="update_request" class="close-btn">Mark as Closed</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- Student Info -->
  <div class="card">
    <h1><?= htmlspecialchars($student['full_name']) ?></h1>
    <p class="info">
      <strong>G-Suite:</strong> <?= htmlspecialchars($student['gsuit'] ?? '—') ?><br>
      <strong>Phone:</strong> <?= htmlspecialchars($student['phone'] ?? '—') ?><br>
      <strong>Blood Group:</strong> <?= htmlspecialchars($student['blood_group'] ?? '—') ?><br>
      <strong>Last Donation:</strong> <?= htmlspecialchars($student['last_donation_date'] ?? '—') ?><br>
      <strong>Status:</strong> 
      <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; 
            background: <?= $student['available'] === 'Available' ? '#e7f9ee' : '#fee2e2' ?>; 
            color: <?= $student['available'] === 'Available' ? '#065f46' : '#991b1b' ?>">
        <?= htmlspecialchars($student['available'] ?? '—') ?>
      </span>
    </p>

    <!-- Availability Update Form -->
    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
      <h3 style="font-size: 16px; margin: 0 0 10px;">Update Availability Status</h3>
      <form method="post" style="display: flex; gap: 10px; align-items: center;">
        <select name="status" class="input" style="margin: 0;">
          <option value="Available" <?= $student['available'] === 'Available' ? 'selected' : '' ?>>Available</option>
          <option value="Not Available" <?= $student['available'] === 'Not Available' ? 'selected' : '' ?>>Not Available</option>
        </select>
        <button type="submit" name="update_status" class="btn" style="margin: 0;">Update Status</button>
      </form>
    </div>
  </div>


  <!-- Fund Donations -->
  <div class="card">
    <h2>💰 Fund Donations</h2>
    <?php if ($funds): ?>
      <table>
        <tr><th>Amount</th><th>Date</th></tr>
        <?php foreach ($funds as $f): ?>
          <tr>
            <td>
              <?php if ($f['flag'] == 0): ?>
                Anonymous
              <?php else: ?>
                <?= number_format($f['amount'], 2) ?> ৳
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($f['date']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p>No fund donations found.</p>
    <?php endif; ?>
  </div>

  <!-- Blood Donations -->
  <div class="card">
    <h2>🩸 Blood Donations</h2>
    <?php if ($bloods): ?>
      <table>
        <tr><th>Camp</th><th>Blood Group</th><th>Date</th></tr>
        <?php foreach ($bloods as $b): ?>
          <tr>
            <td><?= $b['flag']==0 ? 'Anonymous' : htmlspecialchars($b['camp_name']) ?></td>
            <td><?= htmlspecialchars($b['blood_group'] ?? '—') ?></td>
            <td><?= htmlspecialchars($b['donation_date']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p>No blood donations found.</p>
    <?php endif; ?>
  </div>

  <!-- Certification -->
  <div class="card">
    <h2>🎖️ Certification</h2>
    <?php if ($certification): ?>
      <?php if ($certification['date_awarded']): ?>
        <div style="text-align: center; padding: 20px; border: 2px solid #007bff; border-radius: 8px; margin: 10px 0;">
          <img src="<?= htmlspecialchars($certification['badge_icon']) ?>" style="width: 80px; height: 80px; margin-bottom: 15px;">
          <h3 style="color: #007bff; margin: 0 0 15px;">Certificate of Recognition</h3>
          <p style="font-size: 18px; margin-bottom: 10px;">This is to certify that</p>
          <p style="font-size: 24px; font-weight: bold; margin-bottom: 15px;"><?= htmlspecialchars($student['full_name']) ?></p>
          <p style="margin-bottom: 15px;">has been recognized as an active blood donor at BuracU medical</p>
          <p style="color: #666;">Awarded on: <?= date('F j, Y', strtotime($certification['date_awarded'])) ?></p>
        </div>
      <?php else: ?>
        <div style="padding: 15px; background: #fff9e6; border-radius: 6px; margin-top: 10px;">
          <p><strong>Status:</strong> Waiting for Approval</p>
          <p><strong>Request Date:</strong> <?= date('F j, Y', strtotime($certification['date_awarded'])) ?></p>
          <!-- <p><strong>Description:</strong> <?= htmlspecialchars($certification['description']) ?></p> -->
        </div>
      <?php endif; ?>
    <?php else: ?>
      <p>You haven't applied for certification yet.</p>
      <a href="apply_certification.php" class="button" style="display: inline-block; background: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-top: 10px;">Request Certification</a>
    <?php endif; ?>
  </div>

  <!-- Feedbacks -->
  <div class="card">
    <h2>💬 Feedbacks</h2>
    <?php if ($feedbacks): ?>
      <table>
        <thead>
          <tr>
            <th>Given By</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Contact</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($feedbacks as $f): ?>
            <tr>
              <td>
                <?php if ($f['flag'] == 0): ?>
                  Anonymous
                <?php else: ?>
                  <?= htmlspecialchars($f['given_by']) ?>
                <?php endif; ?>
              </td>
              <td>
                <?php 
                  $stars = str_repeat('⭐', $f['rating']);
                  echo $stars;
                ?>
              </td>
              <td><?= htmlspecialchars($f['comment']) ?></td>
              <td>
                <?php if ($f['flag'] == 0): ?>
                  —
                <?php else: ?>
                  <?= htmlspecialchars($f['phone']) ?>
                <?php endif; ?>
              </td>
              <td><?= date('M j, Y', strtotime($f['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No feedbacks received yet.</p>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
