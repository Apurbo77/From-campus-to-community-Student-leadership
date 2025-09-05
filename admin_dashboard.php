<?php
require_once 'db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
$pdo = getPDO();  // <-- Make sure this returns a valid PDO object

if (!$pdo) {
    die("Database connection failed.");
}


function flash($msg, $type='ok'){
    echo "<div class='card' style='margin:8px 0;".($type==='err'?"background:#fde8e8;color:#7a1e1e":"background:#e7f9ee;color:#065f46")."'>$msg</div>";
}
function get_students($pdo){
    return $pdo->query("SELECT student_id, full_name, blood_group FROM student ORDER BY full_name")->fetchAll();
}
function get_sessions($pdo){
    return $pdo->query("SELECT * FROM training_session ORDER BY date ASC, time ASC")->fetchAll();
}
function get_camps($pdo){
    return $pdo->query("SELECT * FROM blood_donation_camp ORDER BY date ASC, time ASC")->fetchAll();
}
function session_registered_count($pdo, $session_id){
    $st = $pdo->prepare("SELECT COUNT(*) FROM register_session WHERE session_id=:id");
    $st->execute([':id'=>$session_id]);
    return (int)$st->fetchColumn();
}

/**
 * Handle actions (POST)
 */
$selected_student_id = isset($_REQUEST['student_id']) ? (int)$_REQUEST['student_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Admin: Delete student
    if ($action === 'delete_student') {
        $student_id = (int)$_POST['student_id'];
        
        try {
            $pdo->beginTransaction();
            
            // Delete related records first
            $tables = [
                'blood_donation',
                'f_donation',
                'register_session',
                'feedback',
                'd_blood',
                'd_financial',
                'certification'
            ];
            
            foreach ($tables as $table) {
                $stmt = $pdo->prepare("DELETE FROM $table WHERE student_id = :sid");
                $stmt->execute([':sid' => $student_id]);
            }
            
            // Finally delete the student
            $stmt = $pdo->prepare("DELETE FROM student WHERE student_id = :sid");
            $stmt->execute([':sid' => $student_id]);
            
            $pdo->commit();
            flash("✅ Student and all related records deleted successfully.");
        } catch (Exception $e) {
            $pdo->rollBack();
            flash("❌ Error deleting student: " . $e->getMessage(), 'err');
        }
    }

    // Admin: create training session
    if ($action === 'create_session') {
        $title = trim($_POST['title']);
        $location = trim($_POST['location']);
        $date = $_POST['date'] ?: null;
        $time = $_POST['time'] ?: null;
        $capacity = (int)($_POST['capacity'] ?: 50);

        if ($title && $location && $date && $time) {
            $stmt = $pdo->prepare("INSERT INTO training_session (title,location,date,time,capacity,created_by) VALUES (:t,:l,:d,:tm,:c,1)");
            $stmt->execute([':t'=>$title, ':l'=>$location, ':d'=>$date, ':tm'=>$time, ':c'=>$capacity]);
            flash("Training session created.");
        } else {
            flash("Please fill all session fields.", 'err');
        }
    }

    // Admin: create blood donation camp
    if ($action === 'create_camp') {
        $title = trim($_POST['c_title']);
        $location = trim($_POST['c_location']);
        $date = $_POST['c_date'] ?: null;
        $time = $_POST['c_time'] ?: null;

        if ($title && $location && $date && $time) {
            $stmt = $pdo->prepare("INSERT INTO blood_donation_camp (title,location,date,time) VALUES (:t,:l,:d,:tm)");
            $stmt->execute([':t'=>$title, ':l'=>$location, ':d'=>$date, ':tm'=>$time]);
            flash("Blood donation camp created.");
        } else {
            flash("Please fill all camp fields.", 'err');
        }
    }

    // Student: register for training session
    if ($action === 'register_training') {
        $sid = (int)$_POST['student_id'];
        $sess = (int)$_POST['session_id'];
        if ($sid && $sess) {
            // capacity check + duplicate check
            $cap = $pdo->prepare("SELECT capacity FROM training_session WHERE session_id=:id");
            $cap->execute([':id'=>$sess]);
            $capacity = $cap->fetchColumn();

            $count = session_registered_count($pdo, $sess);

            $dup = $pdo->prepare("SELECT 1 FROM register_session WHERE student_id=:s AND session_id=:sess LIMIT 1");
            $dup->execute([':s'=>$sid, ':sess'=>$sess]);
            $already = $dup->fetchColumn();

            if ($already) {
                flash("You are already registered for this session.", 'err');
            } elseif ($capacity !== false && $count >= (int)$capacity) {
                flash("Sorry, this session is full.", 'err');
            } else {
                $ins = $pdo->prepare("INSERT INTO register_session (student_id, session_id, status) VALUES (:s,:sess,'pending')");
                $ins->execute([':s'=>$sid, ':sess'=>$sess]);
                flash("Registered for training (status: pending).");
            }
        } else {
            flash("Please select a student and session.", 'err');
        }
    }

    // Admin: update training status for a registration (e.g., mark confirmed/completed)
    if ($action === 'update_reg_status') {
        $reg_id = (int)$_POST['reg_id'];
        $new_status = $_POST['new_status'];
        if (in_array($new_status, ['pending','confirmed','completed']) && $reg_id) {
            $u = $pdo->prepare("UPDATE register_session SET status=:st WHERE id=:id");
            $u->execute([':st'=>$new_status, ':id'=>$reg_id]);
            flash("Registration status updated to: $new_status.");
        } else {
            flash("Invalid status update.", 'err');
        }
    }

    // Admin: approve certification request
    if ($action === 'approve_certification') {
        $cert_id = (int)$_POST['cert_id'];
        $badge_icon = $_POST['badge_icon'];
        
        // Map of badge icons
        $valid_icons = [
            'CPR' => 'img/CPR.png',
            'ECG' => 'img/ECG.png',
            'INJECTION' => 'img/INJECTION.png',
            'FIRST AID' => 'img/FIRST AID.png'
        ];

        if ($cert_id && isset($valid_icons[$badge_icon])) {
            $u = $pdo->prepare("UPDATE certification SET date_awarded = CURRENT_DATE(), badge_icon = :icon WHERE certification_id = :id");
            $u->execute([':icon' => $valid_icons[$badge_icon], ':id' => $cert_id]);
            flash("Certification approved with " . $badge_icon . " badge.");
        } else {
            flash("Invalid certification approval request.", 'err');
        }
    }

    // Student: book a blood donation camp (record into blood_donation with camp date)
    if ($action === 'register_camp') {
        $sid = (int)$_POST['student_id'];
        $camp_id = (int)$_POST['camp_id'];
        if ($sid && $camp_id) {
            // fetch camp date
            $c = $pdo->prepare("SELECT date FROM blood_donation_camp WHERE camp_id=:id");
            $c->execute([':id'=>$camp_id]);
            $camp_date = $c->fetchColumn();
            if ($camp_date) {
                // prevent duplicate booking for same camp
                $dup = $pdo->prepare("SELECT 1 FROM blood_donation WHERE student_id=:s AND camp_id=:c LIMIT 1");
                $dup->execute([':s'=>$sid, ':c'=>$camp_id]);
                if ($dup->fetchColumn()) {
                    flash("You already booked this camp.", 'err');
                } else {
                    $ins = $pdo->prepare("INSERT INTO blood_donation (student_id,camp_id,donation_date,bag_count) VALUES (:s,:c,:d,1)");
                    $ins->execute([':s'=>$sid, ':c'=>$camp_id, ':d'=>$camp_date]);
                    // update student's last_donation_date (booking date used here; you can update to actual donation later)
                    $u = $pdo->prepare("UPDATE student SET last_donation_date=:d WHERE student_id=:s");
                    $u->execute([':d'=>$camp_date, ':s'=>$sid]);
                    flash("Camp booking saved. Thank you!");
                }
            } else {
                flash("Invalid camp selected.", 'err');
            }
        } else {
            flash("Please select a student and camp.", 'err');
        }
    }
}

/**
 * Fetch data for render
 */
$students = get_students($pdo);
$sessions = get_sessions($pdo);
$camps    = get_camps($pdo);

// preselect first student for convenience (if none chosen)
if (!$selected_student_id && $students) {
    $selected_student_id = (int)$students[0]['student_id'];
}

// Selected student details
$sel_student = null;
if ($selected_student_id) {
    $st = $pdo->prepare("SELECT * FROM student WHERE student_id=:id");
    $st->execute([':id'=>$selected_student_id]);
    $sel_student = $st->fetch();
}

// Student's training registrations
$registrations = [];
if ($selected_student_id) {
    $q = $pdo->prepare("SELECT r.id, r.status, ts.title, ts.location, ts.date, ts.time
                        FROM register_session r
                        JOIN training_session ts ON ts.session_id = r.session_id
                        WHERE r.student_id=:sid
                        ORDER BY ts.date DESC, ts.time DESC");
    $q->execute([':sid'=>$selected_student_id]);
    $registrations = $q->fetchAll();
}

// Student's donation history
$don_history = [];
if ($selected_student_id) {
    $q2 = $pdo->prepare("SELECT b.donation_date, b.bag_count, c.title, c.location 
                         FROM blood_donation b 
                         LEFT JOIN blood_donation_camp c ON c.camp_id=b.camp_id
                         WHERE b.student_id=:sid
                         ORDER BY b.donation_date DESC");
    $q2->execute([':sid'=>$selected_student_id]);
    $don_history = $q2->fetchAll();
}

// For capacity display per session
$session_counts = [];
foreach ($sessions as $s) {
    $session_counts[$s['session_id']] = session_registered_count($pdo, $s['session_id']);
}




?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="header">
  <div><strong>Admin Dashboard</strong></div>
  <div>
    <span style="color:#fff">Welcome, <b><?= e($_SESSION['admin_name']) ?></b></span>
    <a href="admin_logout.php" style="color:#fff;margin-left:10px;">Logout</a>
    <a href="feedback_list.php" style="color:#fff;margin-left:10px;">Feedback</a>
  </div>
</div>

<!-- RIGHT: Simple Admin panels -->
      <div>
        <div class="card">
          <h4>Admin: Post Training Session</h4>
          <form method="post">
            <input type="hidden" name="action" value="create_session">
            <input class="input" name="title" placeholder="Title (e.g., CPR Basics)" required>
            <input class="input" name="location" placeholder="Location (e.g., BRACU Auditorium)" required>
            <input class="input" type="date" name="date" required>
            <input class="input" type="time" name="time" required>
            <input class="input" type="number" min="1" name="capacity" placeholder="Capacity (e.g., 50)" required>
            <button class="btn" style="margin-top:6px">Create Session</button>
          </form>
        </div>

        <div class="card" style="margin-top:12px">
          <h4>Admin: Post Blood Donation Camp</h4>
          <form method="post">
            <input type="hidden" name="action" value="create_camp">
            <input class="input" name="c_title" placeholder="Camp title" required>
            <input class="input" name="c_location" placeholder="Location" required>
            <input class="input" type="date" name="c_date" required>
            <input class="input" type="time" name="c_time" required>
            <button class="btn" style="margin-top:6px">Create Camp</button>
          </form>
        </div>
                <!-- Admin: Manage Students -->
        <div class="card" style="margin-top:12px">
          <h4>Admin: Manage Students</h4>
          <?php if ($students): ?>
            <table class="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Blood Group</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($students as $s): ?>
                  <tr>
                    <td><?= htmlspecialchars($s['full_name']) ?></td>
                    <td><?= htmlspecialchars($s['blood_group']) ?></td>
                    <td>
                      <form method="post" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this student? This will delete ALL related records including donations, training sessions, and certifications.');">
                        <input type="hidden" name="action" value="delete_student">
                        <input type="hidden" name="student_id" value="<?= $s['student_id'] ?>">
                        <button type="submit" class="btn" style="background: #dc3545;">Delete</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p>No students found.</p>
          <?php endif; ?>
        </div>

                <!-- Admin: update training progress (simple control) -->
        <div class="card" style="margin-top:12px">
          <h4>Admin: Update Registration Status</h4>
          <?php
          // list recent registrations (global)
          $recent = $pdo->query("SELECT r.id, r.status, s.full_name, t.title, t.date 
                                 FROM register_session r 
                                 JOIN student s ON s.student_id=r.student_id
                                 JOIN training_session t ON t.session_id=r.session_id
                                 ORDER BY r.registered_at DESC LIMIT 8")->fetchAll();
          if (!$recent) {
              echo "<div class='small'>No registrations yet.</div>";
          } else {
              echo "<table class='table'><thead><tr><th>Student</th><th>Session</th><th>Date</th><th>Status</th><th>Set</th></tr></thead><tbody>";
              foreach($recent as $row){
                  echo "<tr>";
                  echo "<td>".e($row['full_name'])."</td>";
                  echo "<td>".e($row['title'])."</td>";
                  echo "<td>".e($row['date'])."</td>";
                  echo "<td>".e($row['status'])."</td>";
                  echo "<td>
                          <form method='post' style='display:flex;gap:6px;align-items:center'>
                            <input type='hidden' name='action' value='update_reg_status'>
                            <input type='hidden' name='reg_id' value='".(int)$row['id']."'>
                            <select name='new_status' class='input' style='max-width:140px'>
                              <option ".($row['status']=='pending'?'selected':'')." value='pending'>pending</option>
                              <option ".($row['status']=='confirmed'?'selected':'')." value='confirmed'>confirmed</option>
                              <option ".($row['status']=='completed'?'selected':'')." value='completed'>completed</option>
                            </select>
                            <button class='btn'>Update</button>
                          </form>
                        </td>";
                  echo "</tr>";
              }
              echo "</tbody></table>";
          }
          ?>
          </div>

        <!-- Admin: Approve Certification Requests -->
        <div class="card" style="margin-top:12px">
          <h4>Admin: Pending Certification Requests</h4>
          <?php
          // list unapproved certification requests
          $pending = $pdo->query("SELECT c.certification_id, c.badge_name, s.full_name, c.training_type
                                FROM certification c
                                JOIN student s ON s.student_id = c.student_id
                                WHERE c.date_awarded IS NULL
                                ORDER BY c.certification_id DESC")->fetchAll();
          
          if (!$pending) {
              echo "<div class='small'>No pending certification requests.</div>";
          } else {
              echo "<table class='table'><thead><tr><th>Student</th><th>Training Type</th><th>Badge Icon</th><th>Action</th></tr></thead><tbody>";
              foreach($pending as $row){
                  echo "<tr>";
                  echo "<td>".e($row['full_name'])."</td>";
                  echo "<td>".e($row['badge_name'])."</td>";
                  echo "<td>
                          <form method='post' style='display:flex;gap:6px;align-items:center'>
                            <input type='hidden' name='action' value='approve_certification'>
                            <input type='hidden' name='cert_id' value='".(int)$row['certification_id']."'>
                            <select name='badge_icon' class='input' style='max-width:140px' required>
                              <option value=''>Select Badge</option>
                              <option value='CPR'>CPR</option>
                              <option value='ECG'>ECG</option>
                              <option value='INJECTION'>INJECTION</option>
                              <option value='FIRST AID'>FIRST AID</option>
                            </select>
                          </td>";
                  echo "<td>
                            <button class='btn'>Approve</button>
                          </form>
                        </td>";
                  echo "</tr>";
              }
              echo "</tbody></table>";
          }
          ?>
          </div>
        </div>
</body>
</html>
