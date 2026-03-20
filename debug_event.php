<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = App\Models\Event::where('id', 2)->first();
if ($e) {
    echo "ID: " . $e->id . PHP_EOL;
    echo "Name: " . $e->name . PHP_EOL;
    echo "Slug: " . $e->slug . PHP_EOL;
    echo "Published: " . ($e->is_published ? "YES" : "NO") . PHP_EOL;
    echo "Active: " . ($e->is_active ? "YES" : "NO") . PHP_EOL;
} else {
    echo "Event ID 2 not found." . PHP_EOL;
}
