<?php
// admin/donate_record.php
require_once '../db.php';
$pdo = getPDO();

if($_SERVER['REQUEST_METHOD']=='POST'){
  $student_id = intval($_POST['student_id']);
  $amount = floatval($_POST['amount']);
  $date = $_POST['date'] ?: date('Y-m-d');

  $stmt = $pdo->prepare("INSERT INTO f_donation (student_id, amount, date) VALUES (:sid,:a,:d)");
  $stmt->execute([':sid'=>$student_id,':a'=>$amount,':d'=>$date]);
  header('Location: donate_record.php?ok=1');
  exit;
}

$students = $pdo->query("SELECT student_id, full_name FROM student ORDER BY full_name")->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><link rel="stylesheet" href="../css/style.css"></head><body>
<div class="container">
  <h3>Record Donation</h3>
  <form method="post" class="card" style="max-width:600px">
    <select name="student_id" class="input" required>
      <?php foreach($students as $s) echo "<option value='{$s['student_id']}'>".e($s['full_name'])."</option>"; ?>
    </select>
    <input class="input" name="amount" placeholder="Amount (e.g., 500.00)" required>
    <input class="input" name="date" type="date" value="<?php echo date('Y-m-d'); ?>">
    <div style="margin-top:8px"><button class="btn">Record</button></div>
  </form>
</div>
</body></html>
