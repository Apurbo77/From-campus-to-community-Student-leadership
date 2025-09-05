<?php
require_once 'db.php';
$pdo = getPDO();

$students = [];
$area = $group = "";
$message = "";

// Handle search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $area = trim($_POST['area']);
        $group = trim($_POST['blood_group']);

        $sql = "SELECT s.student_id, s.full_name, s.area, s.phone, s.blood_group 
                FROM student s WHERE 1=1";
        $params = [];

        if ($area !== '') {
            $sql .= " AND s.area LIKE :area";
            $params[':area'] = "%$area%";
        }
        if ($group !== '') {
            $sql .= " AND s.blood_group = :bg";
            $params[':bg'] = $group;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll();
    }
    // Handle blood request
    else if (isset($_POST['request'])) {
        $stmt = $pdo->prepare("INSERT INTO blood_request (name, phone_no, location, blood_group, urgency_level) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([
                $_POST['name'],
                $_POST['phone'],
                $_POST['location'],
                $_POST['blood_group'],
                $_POST['urgency_level']
            ]);
            $message = "Your blood request has been received. We will contact matching donors soon.";
        } catch (PDOException $e) {
            $message = "Error submitting request. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Blood Matching - BRACU Medical Students</title>
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
        <h2>Find Blood Donors</h2>

        <?php if ($message): ?>
            <div class="card" style="margin-top:12px;background:#e8f5e9;color:#2e7d32">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="card" style="margin-top:12px">
            <div class="searchBar">
                <input name="area" value="<?= htmlspecialchars($area) ?>" placeholder="Enter area" class="input">
                <select name="blood_group" class="input" style="max-width:160px">
                    <option value="">Any blood group</option>
                    <?php
                    foreach(["A+","A-","B+","B-","O+","O-","AB+","AB-"] as $g){
                        $sel = ($group==$g)?'selected':'';
                        echo "<option $sel>$g</option>";
                    }
                    ?>
                </select>
                <button name="search" class="btn">Search</button>
            </div>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])): ?>
            <div class="card" style="margin-top:12px">
                <h3>Search Results</h3>
                <?php if (!$students): ?>
                    <div class="small">No donors found in this area.</div>
                <?php else: ?>
                    <ul class="list">
                        <?php foreach($students as $s): ?>
                            <li class="profile" style="display:flex;align-items:center;padding:12px">
                                <div style="flex:1">
                                    <strong><?= htmlspecialchars($s['full_name']) ?></strong><br>
                                    <span class="small">
                                        Blood Group: <?= htmlspecialchars($s['blood_group']) ?> | 
                                        Area: <?= htmlspecialchars($s['area']) ?> |
                                        Phone: <?= htmlspecialchars($s['phone']) ?>
                                    </span>
                                </div>
                                <button onclick="showRequestForm('<?= htmlspecialchars($s['full_name']) ?>', '<?= htmlspecialchars($s['blood_group']) ?>', '<?= htmlspecialchars($s['area']) ?>')" class="btn">Request Blood</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Blood Request Form Modal -->
        <div id="requestModal" class="modal" style="display:none">
            <div class="modal-content card">
                <h3>Request Blood</h3>
                <form method="post">
                    <div style="margin-bottom:12px">
                        <label>Your Name:</label><br>
                        <input type="text" name="name" class="input" required>
                    </div>
                    <div style="margin-bottom:12px">
                        <label>Your Phone:</label><br>
                        <input type="text" name="phone" class="input" required>
                    </div>
                    <div style="margin-bottom:12px">
                        <label>Your Location:</label><br>
                        <input type="text" name="location" class="input" required>
                    </div>
                    <div style="margin-bottom:12px">
                        <label>Blood Group Needed:</label><br>
                        <input type="text" name="blood_group" class="input" readonly>
                    </div>
                    <div style="margin-bottom:12px">
                        <label>Urgency Level:</label><br>
                        <select name="urgency_level" class="input" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <button type="submit" name="request" class="btn">Submit Request</button>
                    <button type="button" onclick="hideRequestForm()" class="btn" style="background:#f44336">Cancel</button>
                </form>
            </div>
        </div>

        <style>
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 1000;
            }
            .modal-content {
                position: relative;
                margin: 15% auto;
                padding: 20px;
                width: 80%;
                max-width: 500px;
            }
        </style>

        <script>
            function showRequestForm(name, bloodGroup, area) {
                document.getElementById('requestModal').style.display = 'block';
                document.querySelector('input[name="blood_group"]').value = bloodGroup;
            }

            function hideRequestForm() {
                document.getElementById('requestModal').style.display = 'none';
            }
        </script>
    </div>
</body>
</html>
