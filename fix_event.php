<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = App\Models\Event::where('id', 2)->first();
if ($e) {
    echo "Current Status: " . ($e->is_published ? "Published" : "HIDDEN") . PHP_EOL;
    $e->is_published = true;
    $e->is_active = true;
    $e->save();
    echo "New Status: " . ($e->is_published ? "Published" : "HIDDEN") . PHP_EOL;
} else {
    echo "Event ID 2 not found." . PHP_EOL;
}
