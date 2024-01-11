<?php

// Include the Composer autoloader
include_once("../vendor/autoload.php");

// Create a filter instance
$filter = new \SebastianBergmann\CodeCoverage\Filter();
$filter->includeDirectory(dirname(__DIR__) . '/src');
// Create a driver instance
$driver = new \SebastianBergmann\CodeCoverage\Driver\XdebugDriver($filter);

// Create the CodeCoverage instance with the driver and filter
$cov_obj = new \SebastianBergmann\CodeCoverage\CodeCoverage($driver, $filter);

// Set the directory to whitelist for code coverage
//$include_dir = dirname(__DIR__) . '/src';
//$cov_obj->filter()->addDirectoryToWhitelist($include_dir);

// Directory containing coverage files
$cov_dir = __DIR__ . '/coverages';

// Scan the directory for coverage files
$files = scandir($cov_dir);
// get only .json
$files = array_filter($files, function ($file) {
    return strpos($file, '.json') !== false;
});

$file_count = 0;

foreach ($files as $file) {
    // Skip current and parent directory
    if ($file === '.' || $file === '..') {
        continue;
    }

    // Skip invalid coverage files
    if (strpos($file, 'coverage-') !== 0) {
        echo "\n - Invalid coverage file: " . $file;
        continue;
    }

    // Process valid coverage files
    echo "\n - Processing coverage file: " . $file;

    $file_path = $cov_dir . '/' . $file;


    $data = json_decode(file_get_contents($file_path), true);
    $rawDataArray = $data;
//    $rawData = \SebastianBergmann\CodeCoverage\Data\RawCodeCoverageData::fromXdebugWithPathCoverage($rawDataArray);
    //$rawData = \SebastianBergmann\CodeCoverage\CodeCoverage::fromXdebugWithPathCoverage($rawDataArray);
    $rawData = \SebastianBergmann\CodeCoverage\Data\RawCodeCoverageData::fromXdebugWithoutPathCoverage($rawDataArray);
    echo "\n - Processing coverage file: " . $file;
    $cov_obj->append($rawData, $file_path);

    @unlink($file_path);

   // exit($cov_obj);
    $file_count++;
}

// Output combined coverage files count
echo "\n - " . $file_count . ' coverage files combined in ' . $cov_dir;
echo "\n";

// Generate Clover report
$cov_file = $cov_dir . '/dusk-clover.xml';
$writer = new \SebastianBergmann\CodeCoverage\Report\Clover();
$writer->process($cov_obj, $cov_file);

echo " - Clover report generated successfully: " . $cov_file . PHP_EOL;
