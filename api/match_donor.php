<?php
// api/match_donor.php
require_once '../db.php';
$pdo = getPDO();
$req_id = isset($_POST['req_id']) ? (int)$_POST['req_id'] : 0;
$req = $pdo->prepare("SELECT * FROM blood_request WHERE req_id=:id");
$req->execute([':id'=>$req_id]);
$req = $req->fetch();
if(!$req){ echo json_encode(['error'=>'Request not found']); exit; }

// rules: same blood group, area matches (contains), availability, prefer longest time since last donation
$sql = "SELECT s.*, DATEDIFF(CURDATE(), s.last_donation_date) AS days_since FROM student s 
        WHERE s.blood_group=:bg AND s.availability_status='available' AND s.area LIKE :area
        ORDER BY days_since DESC, s.full_name LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute([':bg'=>$req['blood_group'], ':area'=>'%'.$req['location'].'%']);
$candidates = $stmt->fetchAll();
header('Content-Type: application/json');
echo json_encode($candidates);
