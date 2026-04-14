<?php
$models = [
    'Student' => ['table' => 'student', 'pk' => 'student_id', 'ts' => false, 'auth' => true],
    'BloodDonation' => ['table' => 'blood_donation', 'pk' => 'id', 'ts' => false],
    'BloodDonationCamp' => ['table' => 'blood_donation_camp', 'pk' => 'camp_id', 'ts' => false],
    'BloodRequest' => ['table' => 'blood_request', 'pk' => 'req_id', 'ts' => false],
    'Certification' => ['table' => 'certification', 'pk' => 'certification_id', 'ts' => false],
    'DBlood' => ['table' => 'd_blood', 'pk' => 'student_id', 'ts' => false],
    'DFinancial' => ['table' => 'd_financial', 'pk' => 'id', 'ts' => false],
    'Feedback' => ['table' => 'feedback', 'pk' => null, 'ts' => false],
    'FDonation' => ['table' => 'f_donation', 'pk' => 'f_id', 'ts' => false],
    'RegisterSession' => ['table' => 'register_session', 'pk' => 'id', 'ts' => false],
    'TrainingSession' => ['table' => 'training_session', 'pk' => 'session_id', 'ts' => false],
];

foreach ($models as $name => $meta) {
    $path = __DIR__ . "/laravel_app/app/Models/{$name}.php";
    if (!file_exists($path)) { echo "Skipping $name\n"; continue; }
    $content = file_get_contents($path);
    $addition = "";
    if ($meta['table']) {
        $addition .= "    protected \$table = '{$meta['table']}';\n";
    }
    if ($meta['pk']) {
        $addition .= "    protected \$primaryKey = '{$meta['pk']}';\n";
    }
    if ($meta['ts'] === false) {
        $addition .= "    public \$timestamps = false;\n";
    }
    $addition .= "    protected \$guarded = [];\n";

    if (isset($meta['auth']) && $meta['auth']) {
        $content = str_replace("use Illuminate\Foundation\Auth\User as Authenticatable;", "", $content); // In case it's there
        $content = str_replace("namespace App\Models;", "namespace App\Models;\n\nuse Illuminate\Foundation\Auth\User as Authenticatable;", $content);
        $content = str_replace("class {$name} extends Model", "class {$name} extends Authenticatable", $content);
    }
    
    // find { \n // \n } and replace
    $content = preg_replace('/\{\s*\/\/\s*\}/', "{\n{$addition}\n}", $content);
    
    file_put_contents($path, $content);
    echo "Patched {$name}\n";
}
