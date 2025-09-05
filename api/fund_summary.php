<?php
// api/fund_summary.php
require_once '../db.php';
$pdo = getPDO();
$r = $pdo->query("SELECT COALESCE(SUM(amount),0) AS total FROM f_donation")->fetch();
$latest = $pdo->query("SELECT s.full_name, f.amount, f.date FROM f_donation f LEFT JOIN student s ON s.student_id = f.student_id ORDER BY f.date DESC LIMIT 1")->fetch();
header('Content-Type: application/json');
echo json_encode(['total'=>number_format((float)$r['total'],2),'latest_name'=>$latest ? $latest['full_name'] : null]);
