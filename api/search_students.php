<?php
// api/search_students.php
require_once '../db.php';
$pdo = getPDO();

$area = isset($_GET['area']) ? trim($_GET['area']) : '';
$group = isset($_GET['group']) ? trim($_GET['group']) : '';

$sql = "SELECT student_id, full_name, area, city, blood_group, availability_status FROM student WHERE 1=1";
$params = [];
if($area !== ''){
  $sql .= " AND area LIKE :area";
  $params[':area'] = '%'.$area.'%';
}
if($group !== ''){
  $sql .= " AND blood_group = :bg";
  $params[':bg'] = $group;
}
$sql .= " ORDER BY availability_status='available' DESC, full_name LIMIT 50";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
header('Content-Type: application/json');
echo json_encode($rows);
