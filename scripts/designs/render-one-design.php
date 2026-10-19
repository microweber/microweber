<?php
/**
 * Render ONE Bootstrap/JSON design to full HTML via the sample renderer.
 *
 * The JSON designs live outside the repo (a folder of
 *   <samples>/index.php             — shared PHP renderer (?template=<slug>)
 *   <samples>/template/<slug>/content.json + css/style.css + images/*
 * ). This wrapper renders a single slug in its OWN process so the renderer's
 * global function definitions never collide when the importer loops all slugs.
 *
 * Usage:  SAMPLES_DIR=/path/to/samples php render-one-design.php <slug>
 */
$slug = $argv[1] ?? '';
$samples = getenv('SAMPLES_DIR') ?: '/tmp/bt';
if ($slug === '') { fwrite(STDERR, "usage: SAMPLES_DIR=... render-one-design.php <slug>\n"); exit(2); }
if (!is_file($samples . '/index.php')) { fwrite(STDERR, "no renderer at {$samples}/index.php\n"); exit(2); }
$_GET['template'] = $slug;
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['REQUEST_METHOD'] = 'GET';
chdir($samples);
include $samples . '/index.php';
