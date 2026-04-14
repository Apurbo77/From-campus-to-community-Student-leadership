<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
\Illuminate\Support\Facades\DB::unprepared(file_get_contents('../bracu_medical.sql'));
echo "Import DB successful!";
