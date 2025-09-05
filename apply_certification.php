<?php
require_once 'db.php';
$pdo = getPDO();

$message = "";
$students = $pdo->query("SELECT student_id, full_name FROM student ORDER BY full_name")->fetchAll();
$trainings = $pdo->query("SELECT DISTINCT title FROM training_session ORDER BY title")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $training_type = $_POST['training_type'];
    
    // The badge name will be the same as training type for now
    $badge_name = $training_type;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO certification (student_id, badge_name, training_type) VALUES (?, ?, ?)");
        $stmt->execute([$student_id, $badge_name, $training_type]);
        $message = "Certification request has been received. You will be notified once approved.";
    } catch (PDOException $e) {
        $message = "Error submitting request. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Apply for Certification - BRACU Medical Students</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <div><strong>From Campus to Community</strong></div>
        <div>
            <a href="index.php" style="color:#fff;margin-right:12px;">Home</a>
            <a href="training.php" style="color:#fff;margin-right:12px;">Training & Donation</a>
            <a href="blood_matching.php" style="color:#fff;margin-right:12px;">Blood Matching</a>
            <a href="admin_login.php" style="color:#fff;margin-right:12px;">Admin Login</a>
            <a href="leaderboard.php" style="color:#fff;">Leaderboard</a>
        </div>
    </div>

    <div class="container">
        <h2>Apply for Certification</h2>

        <?php if ($message): ?>
            <div class="card" style="margin-top:12px;background:#e8f5e9;color:#2e7d32">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="card" style="margin-top:12px">
            <div style="margin-bottom:12px">
                <label>Select Student:</label><br>
                <select name="student_id" class="input" required>
                    <option value="">Choose a student...</option>
                    <?php foreach($students as $s): ?>
                        <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom:12px">
                <label>Training Type:</label><br>
                <select name="training_type" class="input" required>
                    <option value="">Choose training type...</option>
                    <?php foreach($trainings as $t): ?>
                        <option value="<?= htmlspecialchars($t['title']) ?>"><?= htmlspecialchars($t['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn">Submit Request</button>
        </form>
    </div>
</body>
</html>
