<?php

/**
 * Helper: outputs the admin-pages fixture as a flat JSON array of URIs,
 * skipping record_sources, auth, and {record} routes.
 */

$f = require __DIR__ . '/../tests/fixtures/admin-pages.php';
$urls = [];
foreach ($f as $area => $routes) {
    if ($area === 'record_sources' || $area === 'auth') {
        continue;
    }
    foreach ($routes as $uri) {
        if (str_contains($uri, '{record}')) {
            continue;
        }
        $urls[] = $uri;
    }
}
echo json_encode($urls);
