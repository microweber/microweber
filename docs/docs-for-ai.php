<?php


// Script to combine all module README.md files into one markdown document

$modulesPath = __DIR__ . '/../Modules';
$outputFile = __DIR__ . '/docs-for-ai.md';

// Initialize output content
$combinedContent = "# Microweber Modules Documentation\n\n";

// Get all module directories
$modules = array_filter(glob($modulesPath . '/*'), 'is_dir');

foreach ($modules as $modulePath) {
    $readmePath = $modulePath . '/README.md';

    if (file_exists($readmePath)) {
        // Get module name from directory
        $moduleName = basename($modulePath);
        $combinedContent .= "<chunk>\n\n";
        // Add module header
        $combinedContent .= "## {$moduleName} Module\n\n";

        // Read and append README content
        $readmeContent = file_get_contents($readmePath);

        // Remove the first heading if it exists (since we added our own)
        $readmeContent = preg_replace('/^#\s.*?\n/m', '', $readmeContent, 1);

        $combinedContent .= $readmeContent . "\n\n---\n\n";
        $combinedContent .= "</chunk>\n\n";

    }
}

// Write combined content to output file
file_put_contents($outputFile, $combinedContent);

echo "Documentation has been generated at: " . $outputFile . "\n";
