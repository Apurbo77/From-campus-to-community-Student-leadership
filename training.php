<?php
require_once 'db.php';
$pdo = getPDO();

/** Helpers */
function flash($msg, $type='ok'){
    echo "<div class='card' style='margin:8px 0;".($type==='err'
        ?"background:#fde8e8;color:#7a1e1e"
        :"background:#e7f9ee;color:#065f46")."'>$msg</div>";
}
function get_sessions($pdo){
    return $pdo->query("SELECT * FROM training_session ORDER BY date ASC, time ASC")->fetchAll();
}

function session_registered_count($pdo, $session_id){
    $st = $pdo->prepare("SELECT COUNT(*) FROM register_session WHERE session_id=:id");
    $st->execute([':id'=>$session_id]);
    return (int)$st->fetchColumn();
}

if (!isset($_SESSION['student_id'])) {
    // redirect if not logged in
    header("Location: t_login.php");
    exit;
}
$student_id = $_SESSION['student_id'];

/** --- Handle updates --- */
// Availability update
if (isset($_POST['update_status'])) {
    $available = $_POST['available'];
    $stmt = $pdo->prepare("UPDATE student SET available = :a WHERE student_id = :id");
    $stmt->execute([':a' => $available, ':id' => $student_id]);
    flash("✅ Availability updated.");
}

// Last donation date update
if (isset($_POST['update_last_donation'])) {
    $last_date = $_POST['last_donation_date'];
    if ($last_date) {
        $stmt = $pdo->prepare("UPDATE student SET last_donation_date = :d WHERE student_id = :id");
        $stmt->execute([':d' => $last_date, ':id' => $student_id]);
        flash("✅ Last donation date updated.");
    } else {
        flash("⚠️ Please select a date.", "err");
    }
}

/** --- Handle training & camp registration --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'register_training') {
        $sess = (int)$_POST['session_id'];
        if ($sess) {
            $cap = $pdo->prepare("SELECT capacity FROM training_session WHERE session_id=:id");
            $cap->execute([':id'=>$sess]);
            $capacity = $cap->fetchColumn();
            $count = session_registered_count($pdo, $sess);

            // Check if this combination of student and session already exists
            $checkReg = $pdo->prepare("SELECT status FROM register_session WHERE student_id = :sid AND session_id = :sessid");
            $checkReg->execute([':sid' => $student_id, ':sessid' => $sess]);
            $existingReg = $checkReg->fetch();

            if ($existingReg) {
                flash("⚠️ You are already registered for this session with status: " . htmlspecialchars($existingReg['status']), 'err');
            } else if ($capacity !== false && $count >= (int)$capacity) {
                flash("❌ This session is full.", 'err');
            } else {
                $ins = $pdo->prepare("INSERT INTO register_session (student_id, session_id, status) VALUES (:s,:sess,'pending')");
                $ins->execute([':s'=>$student_id, ':sess'=>$sess]);
                flash("✅ Registered for training (pending).");
            }
            }
        }
    }


/** --- Fetch data for display --- */
$stmt = $pdo->prepare("SELECT full_name, available, last_donation_date FROM student WHERE student_id = :id");
$stmt->execute([':id' => $student_id]);
$student = $stmt->fetch();

$sessions = get_sessions($pdo);

$q = $pdo->prepare("SELECT r.status, ts.title, ts.location, ts.date, ts.time
                    FROM register_session r
                    JOIN training_session ts ON ts.session_id = r.session_id
                    WHERE r.student_id=:sid
                    ORDER BY ts.date DESC, ts.time DESC");
$q->execute([':sid'=>$student_id]);
$registrations = $q->fetchAll();

$q2 = $pdo->prepare("SELECT b.donation_date, b.bag_count, c.title, c.location 
                     FROM blood_donation b 
                     LEFT JOIN blood_donation_camp c ON c.camp_id=b.camp_id
                     WHERE b.student_id=:sid
                     ORDER BY b.donation_date DESC");
$q2->execute([':sid'=>$student_id]);
$don_history = $q2->fetchAll();

// For capacity display
$session_counts = [];
foreach ($sessions as $s) {
    $session_counts[$s['session_id']] = session_registered_count($pdo, $s['session_id']);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Training & Blood Donation Portal</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .status-box { margin:10px 0; padding:10px; background:#f9f9f9; border:1px solid #ddd; border-radius:6px;}
  </style>
</head>
<body>
  <div class="header">
    <div><strong>Training & Blood Donation Portal</strong></div>
    <div>
      <a href="index.php" style="color:#fff; margin-right: 15px;">Home</a>
      <a href="t_logout.php" style="color:#fff">Logout</a>
    </div>
  </div>

  <div class="container">

    <!-- Availability + Last Donation -->
    <div class="card">
      <h2>👤 <?= htmlspecialchars($student['full_name']) ?> — Status</h2>

      <h3>Availability</h3>
      <div class="status-box">
        Current: <strong><?= htmlspecialchars($student['available']) ?></strong>
      </div>
      <form method="post">
        <select name="available" required>
          <option value="Available" <?= $student['available']=="Available"?"selected":"" ?>>Available</option>
          <option value="Not Available" <?= $student['available']=="Not Available"?"selected":"" ?>>Not Available</option>
        </select>
        <button type="submit" name="update_status" class="btn">Update</button>
      </form>

      <h3>Last Donation Date</h3>
      <div class="status-box">
        Current: <strong><?= $student['last_donation_date'] ?: '—' ?></strong>
      </div>
      <form method="post">
        <input type="date" name="last_donation_date" value="<?= $student['last_donation_date'] ?>">
        <button type="submit" name="update_last_donation" class="btn">Save</button>
      </form>
    </div>

    <!-- Training Sessions -->
    <div class="card">
      <h3>Upcoming Training Sessions</h3>
      <?php if (!$sessions): ?>
        <div>No sessions posted yet.</div>
      <?php else: ?>
        <form method="post">
          <input type="hidden" name="action" value="register_training">
          <table class="table">
            <thead><tr><th>Title</th><th>When</th><th>Where</th><th>Capacity</th><th>Action</th></tr></thead>
            <tbody>
              <?php foreach($sessions as $s): 
                  $count = $session_counts[$s['session_id']] ?? 0;
                  $full = ($count >= (int)$s['capacity']);
              ?>
              <tr>
                <td><?= htmlspecialchars($s['title']) ?></td>
                <td><?= $s['date'] ?> <?= $s['time'] ?></td>
                <td><?= htmlspecialchars($s['location']) ?></td>
                <td><?= $count ?>/<?= (int)$s['capacity'] ?><?= $full?" (Full)":"" ?></td>
                <td>
                  <?php if (!$full): ?>
                    <button class="btn" name="session_id" value="<?= $s['session_id'] ?>">Register</button>
                  <?php else: ?>
                    <span>Closed</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </form>
      <?php endif; ?>
    </div>

    <!-- Training Progress -->
    <div class="card">
      <h3>My Training Progress</h3>
      <?php if (!$registrations): ?>
        <p>No registrations yet.</p>
      <?php else: ?>
        <table class="table">
          <thead><tr><th>Session</th><th>When</th><th>Location</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach ($registrations as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['title']) ?></td>
              <td><?= $r['date'] ?> <?= $r['time'] ?></td>
              <td><?= htmlspecialchars($r['location']) ?></td>
              <td><?= ucfirst($r['status']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>


    <!-- Donation History -->
    <div class="card">
      <h3>My Blood Donation History</h3>
      <?php if (!$don_history): ?>
        <p>No donations recorded yet.</p>
      <?php else: ?>
        <ul>
          <?php foreach($don_history as $d): ?>
            <li><?= $d['donation_date'] ?> — <?= $d['bag_count'] ?> bag(s) 
                (<?= $d['title'] ?>, <?= $d['location'] ?>)
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
