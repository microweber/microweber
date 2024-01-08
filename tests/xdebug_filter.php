<?php
// https://xdebug.org/docs/code_coverage
// php -dauto_prepend_file=xdebug_filter.php vendor/bin/phpunit --coverage-clover tests/clover.xml  --coverage-html=coverage
// php -d xdebug.mode=coverage -r "require 'vendor/bin/phpunit';" -- --configuration phpunit.xml --do-not-cache-result --coverage-clover tests/clover.xml --coverage-html tests/coverage



xdebug_set_filter(
    XDEBUG_FILTER_CODE_COVERAGE,
    XDEBUG_PATH_INCLUDE,
    [__DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR]
);
