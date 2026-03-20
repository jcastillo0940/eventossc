<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$u = User::where('email', 'judge@example.com')->first();
if (!$u) {
    echo "USER NOT FOUND";
    exit;
}
echo "EMAIL: " . $u->email . "\n";
echo "HASH: " . $u->password . "\n";
echo "CHECK 'password': " . (Hash::check('password', $u->password) ? 'MATCH' : 'FAIL') . "\n";
