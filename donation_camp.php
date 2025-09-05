<?php
require_once 'db.php';
$pdo = getPDO();

// Handle blood camp registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donate_blood'])) {
    $student_id = intval($_POST['student_id']);
    $camp_id = intval($_POST['camp_id']);
    $bag_count = intval($_POST['bag_count']);
    $date = date('Y-m-d');
    $flag = intval($_POST['flag']);

    $stmt = $pdo->prepare("INSERT INTO blood_donation (student_id, camp_id, donation_date, bag_count, flag) 
                           VALUES (:sid, :cid, :d, :bags, :flag)");
    $stmt->execute([
        ':sid' => $student_id,
        ':cid' => $camp_id,
        ':d'   => $date,
        ':bags'=> $bag_count,
        ':flag'=> $flag
    ]);

    // Update last donation date in student table
    $updateStudent = $pdo->prepare("UPDATE student SET last_donation_date = :date WHERE student_id = :sid");
    $updateStudent->execute([':date' => $date, ':sid' => $student_id]);
    
    $message = "✅ Blood donation recorded successfully.";


    $score_b = floatval($bag_count*10);

    // Check if student already exists in d_blood table
    $check = $pdo->prepare("SELECT student_id FROM d_blood WHERE student_id = :sid LIMIT 1");
    $check->execute([':sid' => $student_id]);
    $row = $check->fetch();

    if ($row) {
        // update score
        $update = $pdo->prepare("UPDATE d_blood 
                                 SET blood_score = blood_score + :sc 
                                 WHERE student_id = :sid");
        $update->execute([':sc' => $score_b, ':sid' => $student_id]);
    } else {
        // insert new record in financial
        $student = $pdo->prepare("SELECT full_name FROM student WHERE student_id = :sid");
        $student->execute([':sid' => $student_id]);
        $stu = $student->fetch();

        $insert = $pdo->prepare("INSERT INTO d_blood (name, blood_score, flag, student_id, camp_id) 
                                 VALUES (:n, :sc, :f, :sid, :cid)");
        $insert->execute([
            ':n'   => $stu['full_name'],
            ':sc'  => $score_b,
            ':f'   => $flag,
            ':sid' => $student_id,
            ':cid' => $camp_id
        ]);
    }



}



// Handle financial donation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fund_donate'])) {
    $student_id = intval($_POST['student_id']);
    $amount = floatval($_POST['amount']);
    $date = date('Y-m-d');
    $flag = intval($_POST['flag']);

    $stmt = $pdo->prepare("INSERT INTO f_donation (student_id, amount, date, flag) VALUES (:sid,:a,:d, :flag)");
    $stmt->execute([':sid'=>$student_id, ':a'=>$amount, ':d'=>$date, ':flag' => $flag]);
    $message = "💰 Thank you! Your donation has been recorded.";




    $score = floatval($amount / 100);

    // Check if student already exists in financial table
    $check = $pdo->prepare("SELECT id FROM d_financial WHERE student_id = :sid LIMIT 1");
    $check->execute([':sid' => $student_id]);
    $row = $check->fetch();

    if ($row) {
        // update score
        $update = $pdo->prepare("UPDATE d_financial 
                                 SET finance_score = finance_score + :sc 
                                 WHERE student_id = :sid");
        $update->execute([':sc' => $score, ':sid' => $student_id]);
    } else {
        // insert new record in financial
        $student = $pdo->prepare("SELECT full_name FROM student WHERE student_id = :sid");
        $student->execute([':sid' => $student_id]);
        $stu = $student->fetch();

        $insert = $pdo->prepare("INSERT INTO d_financial (name, finance_score, flag, student_id) 
                                 VALUES (:n, :sc, :f, :sid)");
        $insert->execute([
            ':n'   => $stu['full_name'],
            ':sc'  => $score,
            ':f'   => $flag,
            ':sid' => $student_id
        ]);
    }


}




// Fetch camps
$camps = $pdo->query("SELECT * FROM blood_donation_camp ORDER BY date ASC")->fetchAll();
// Fetch students for dropdown
$students = $pdo->query("SELECT student_id, full_name, blood_group FROM student ORDER BY full_name")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Donate Blood / Fund</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="header">
    <div>Donate Blood / Fund</div>
    <div><a href="index.php" style="color:#fff;margin-right:12px;">Home</a>
    <a href="leaderboard.php" style="color:#fff;">Leaderboard</a>
  </div>
  </div>

  <div class="container">
    <?php if(!empty($message)) echo "<div class='card' style='background:#e7f9ee;color:#066'>$message</div>"; ?>

  <div class="card">
      <h3>Record Blood Donation</h3>
      <form method="post">
        <label>Select Student</label>
        <select class="input" name="student_id" required>
          <?php foreach($students as $s): ?>
            <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['full_name']) ?> (<?= e($s['blood_group'])?>)</option>
          <?php endforeach; ?>
        </select>
<hr>
        <label>Select Camp</label>
        <select class="input" name="camp_id" required>
          <?php foreach($camps as $c): ?>
            <option value="<?= $c['camp_id'] ?>"><?= htmlspecialchars($c['title']) ?> — <?= htmlspecialchars($c['location']) ?> (<?= $c['date'] ?>)
            </option>
          <?php endforeach; ?>
        </select>

        <hr>
        <label>Bag Count</label>
    
        <input class="input" type="number" name="bag_count" min="1" required>

        <hr>
        

        <label>Anonymous?</label>
        <select name="flag" class="input" required>
        <option value="">Select your option</option>
        <option value="1">No</option>
        <option value="0">Yes</option>
        </select>

            <hr>

        <button class="btn" name="donate_blood">Submit Donation</button>
      </form>
    </div>

      <!-- Financial Donation -->
      <div class="card">
        <h3>Make a Financial Donation</h3>
        <form method="post">
          <label>Select Student</label>
          <select class="input" name="student_id" required>
            <?php foreach($students as $s): ?>
              <option value="<?= $s['student_id'] ?>"> (<?= $s['student_id'] ?>) <?= e($s['full_name']) ?> (<?= e($s['blood_group'])?>) </option>
            <?php endforeach; ?>
          </select>
          <hr>
    


          <label>Select Amount</label>
          <input class="input" name="amount" type="number" step="10" placeholder="Enter amount (e.g., )" required>

          <hr>

          <label>Anonymous?</label>
          <select name="flag" class="input" required>
            <option value="">Select your option</option>
            <option value="1">No</option>
          <option value="0">Yes</option>
          </select>

              <hr>

          <button class="btn" name="fund_donate">Donate Now</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
