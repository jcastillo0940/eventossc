<?php
$files = [
    'resources/views/admin/users/index.blade.php',
    'resources/views/admin/participants/index.blade.php',
    'resources/views/admin/judges/index.blade.php',
    'resources/views/admin/events/index.blade.php',
    'resources/views/admin/events/categories/index.blade.php',
    'resources/views/admin/events/categories/criteria/index.blade.php',
    'resources/views/admin/brands/index.blade.php',
    'resources/views/admin/audit/index.blade.php',
];

foreach ($files as $f) {
    $content = file_get_contents($f);
    // Remove @if(session('success/error/warning/info')) ... @endif blocks
    $content = preg_replace('/@if\(session\([\'\"](success|error|warning|info)[\'\"]\)\).*?@endif\s*/s', '', $content);
    file_put_contents($f, $content);
    echo "Cleaned: $f\n";
}
echo "Done!\n";
