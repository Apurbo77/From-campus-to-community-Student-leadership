<?php
// config.php
define('DB_HOST','localhost');
define('DB_NAME','bracu_medical');
define('DB_USER','root');
define('DB_PASS',''); // empty for XAMPP default

session_start();

function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
