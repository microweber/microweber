<?php
/**
 * Regenerate the Browser-NN <testsuite> buckets printed to stdout.
 * tests/Browser holds ~240 files / ~400 Dusk methods; a single process
 * degrades Chrome even with recycleEvery=10, so we split into balanced
 * buckets (<= TARGET methods each, sorted filename order) that run-dusk.sh
 * runs in separate processes. Paste the output into phpunit.dusk.xml between
 * the package suites and </testsuites>, then mirror the names in run-dusk.sh.
 * Usage: php scripts/gen-dusk-suites.php [TARGET]
 */
$TARGET = (int) ($argv[1] ?? 40);
$files = glob('tests/Browser/*.php');
sort($files);
$count = function (string $f): int {
    $s = file_get_contents($f);
    return max(1, preg_match_all('/#\[Test\]/', $s) + preg_match_all('/public function test_/', $s));
};
$buckets = []; $cur = []; $curN = 0;
foreach ($files as $f) {
    $n = $count($f);
    if ($curN > 0 && $curN + $n > $TARGET) { $buckets[] = $cur; $cur = []; $curN = 0; }
    $cur[] = $f; $curN += $n;
}
if ($cur) $buckets[] = $cur;
foreach ($buckets as $i => $bucket) {
    printf("        <testsuite name=\"Browser-%02d\">\n", $i + 1);
    foreach ($bucket as $f) echo "            <file>./$f</file>\n";
    echo "        </testsuite>\n";
}
fwrite(STDERR, sprintf("Generated %d buckets (target %d methods each)\n", count($buckets), $TARGET));
