<?php
require_once 'db.php';
$pdo = getPDO();

$students = [];
$area = $group = "";

// Handle search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $area  = trim($_POST['area']);
    $group = trim($_POST['blood_group']);

    $sql = "SELECT student_id, full_name, area, city, blood_group , phone, available
            FROM student WHERE 1=1";
    $params = [];

    if ($area !== '') {
        $sql .= " AND area LIKE :area";
        $params[':area'] = "%$area%";
    }
    if ($group !== '') {
        $sql .= " AND blood_group = :bg";
        $params[':bg'] = $group;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $students = $stmt->fetchAll();
}

// Fundraising summary
$total = $pdo->query("SELECT COALESCE(SUM(amount),0) AS total FROM f_donation")->fetchColumn();
$latest = $pdo->query("SELECT s.full_name FROM f_donation f LEFT JOIN student s ON s.student_id=f.student_id ORDER BY f.date DESC LIMIT 1")->fetchColumn();

// Recent sessions
$sessions = $pdo->query("SELECT * FROM training_session ORDER BY date DESC LIMIT 5")->fetchAll();

// Top rated volunteers
$top = $pdo->query("SELECT s.full_name, AVG(f.rating) AS avg_rating 
                    FROM student s 
                    LEFT JOIN feedback f ON s.student_id=f.student_id
                    GROUP BY s.student_id 
                    ORDER BY avg_rating DESC LIMIT 5")->fetchAll();

//Apurbo
$camps = $pdo->query("SELECT * FROM blood_donation_camp  ORDER BY date ASC LIMIT 5")->fetchAll();
$totalBags = $pdo->query("SELECT IFNULL(SUM(bag_count),0) FROM blood_donation")->fetchColumn();
$latestDonation = $pdo->query("
    SELECT s.full_name AS student_name, d.bag_count, c.title AS camp_name, d.donation_date
    FROM blood_donation d
    JOIN student s ON d.student_id = s.student_id
    JOIN blood_donation_camp c ON d.camp_id = c.camp_id
    ORDER BY d.donation_date DESC, d.camp_id DESC
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);


?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>BRACU Medical Students</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="header">
  <div><strong>From Campus to Community</strong></div>
  <div>
    <a href="training.php" style="color:#fff;margin-right:12px;">Training & Donation</a>
    <a href="blood_matching.php" style="color:#fff;margin-right:12px;">Blood Matching</a>
    <a href="admin_login.php" style="color:#fff;margin-right:12px;">Admin Login</a>
    <a href="leaderboard.php" style="color:#fff;">Leaderboard</a>
    


  </div>
</div>


  <div class="container">
    <h2>Find medically trained students near you</h2>

    <form method="post" class="card" style="margin-top:12px">
      <div class="searchBar">
        <input name="area" value="<?= e($area) ?>" placeholder="Enter area (e.g., Dhanmondi)" class="input">
        <select name="blood_group" class="input" style="max-width:160px">
          <option value="">Any blood group</option>
          <?php
          foreach(["A+","A-","B+","B-","O+","O-","AB+","AB-"] as $g){
            $sel = ($group==$g)?'selected':'';
            echo "<option $sel>$g</option>";
          }
          ?>
        </select>
        <button class="btn">Search</button>
      </div>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <div class="card" style="margin-top:12px">
        <h3>Search Results</h3>
        <?php if (!$students): ?>
          <div class="small">No students found.</div>
        <?php else: ?>
          <ul class="list">
            <?php foreach($students as $s): ?>
              <li class="profile">
                <div style="flex:1">
                  <strong><?= e($s['full_name']) ?> </strong>
                  <div class="small">
                    Phone=> <?= e($s['phone']) ?><br>
                    <?= e($s['available']) ?><br>
                    <?= e($s['area']) ?><?= $s['city'] ? ', '.e($s['city']) : '' ?> • <?= e($s['blood_group']) ?>
                  </div>
                </div>
                <div><a href="profile.php?id=<?= $s['student_id'] ?>" class="btn">Profile</a></div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div style="margin-top:18px" class="grid">
      <div>
        <div class="card">
          <h3>Live Fundraising</h3>
          <div>Total donations: <?= number_format($total,2) ?><br>
          Latest: <?= e($latest ?: '—') ?></div>
          <hr>
          <a href="donations.php" class="btn">View All Donations</a>
        </div>
        



        <div class="card">
          <h2>🩸 Live Blood Donation Camps</h2>
          <?php if(!$camps): ?>
            <p class="small">No blood donation camps available right now.</p>
          <?php else: ?>
            <ul>
              <?php foreach($camps as $c): ?>
                <li>
                  <strong><?= htmlspecialchars($c['title']) ?></strong><br>
                  📍 <?= htmlspecialchars($c['location']) ?><br>
                  📅 <?= htmlspecialchars($c['date']) ?> — 🕒 <?= htmlspecialchars($c['time']) ?><br>
          
                </li>
                <hr>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>

        <div class="card">
            <h3>🩸 Live Blood Donation</h3>
            <div>
              Total blood bags: <?= number_format($totalBags) ?><br>
              Latest: 
              <?php if ($latestDonation): ?>
                <?= htmlspecialchars($latestDonation['student_name']) ?> donated 
                <?= $latestDonation['bag_count'] ?> bag(s) at 
                <?= htmlspecialchars($latestDonation['camp_name']) ?> (<?= $latestDonation['donation_date'] ?>)
              <?php else: ?>
                —
              <?php endif; ?>
            </div>
          </div>




        <div class="card" style="margin-top:12px">
          <h3>Recent Training Sessions</h3>
          <ul class="list">
            <?php foreach($sessions as $s): ?>
              <li><strong><?= e($s['title']) ?></strong> — <?= e($s['location']) ?> 
                <div class="small"><?= e($s['date']) ?> <?= e($s['time']) ?></div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <div>
        <div class="card">
          <h4>Quick Actions</h4>
          <a href="register.php" class="btn" style="display:inline-block;margin-top:6px">Sign up for Training</a>
          <hr>
          <a href="donation_camp.php" style="display:inline-block;margin-left:6px;padding:10px 12px;background:#eee;color:#222;border-radius:8px;text-decoration:none">Donate Blood / Fund</a>
          <hr>
          <a href="apply_certification.php" class="btn" style="display:inline-block;margin-top:6px">Apply for Certification</a>
          <hr>
          <a href="leave_feedback.php" class="btn" style="display:inline-block;margin-top:6px">FeedBack</a>
        </div>

        <div class="card" style="margin-top:12px">
          <h4>Top Rated Volunteers</h4>
          <ul class="list">
            <?php foreach($top as $t): ?>
              <li><?= e($t['full_name']) ?> <span class="small"><?= $t['avg_rating'] ? round($t['avg_rating'],1)."/5" : "—" ?></span></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="footer">
              This is our first PHP project. This project created by Apurbo, Ratri, Shishir.
    </div>
  </div>
</body>
</html>
