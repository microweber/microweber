<?php
// https://github.com/tarunlalwani/php-code-coverage-web
// https://xdebug.org/docs/code_coverage
// php -dauto_prepend_file=xdebug_auto_prepend_file.php vendor/bin/phpunit --coverage-clover tests/clover.xml  --coverage-html=coverage
// php -d xdebug.mode=coverage -r "require 'vendor/bin/phpunit';" -- --configuration phpunit.xml --do-not-cache-result --coverage-clover tests/clover.xml --coverage-html tests/coverage

// put in htaccess php_value auto_prepend_file "tests/xdebug_auto_prepend_file.php"


// 'REQUEST_URI' => string '/userfiles/

$skip_xdebug = false;
if (isset($_SERVER['REQUEST_URI']) and $_SERVER['REQUEST_URI'] == '/favicon.ico') {
    $skip_xdebug = true;
}
if (isset($_SERVER['REQUEST_URI']) and str_contains($_SERVER['REQUEST_URI'], '/userfiles/')) {
    $skip_xdebug = true;
}
if (!$skip_xdebug) {

//    xdebug_set_filter(
//        XDEBUG_FILTER_CODE_COVERAGE,
//        XDEBUG_PATH_INCLUDE,
//        [__DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR]
//    );

    xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);

    function end_coverage()
    {
        $current_dir = __DIR__;
        $test_name = (isset($_COOKIE['test_name']) && !empty($_COOKIE['test_name'])) ? $_COOKIE['test_name'] : 'unknown_test_' . time();
//        global $test_name;
//        global $current_dir;
        $coverageName = $current_dir . '/coverages/coverage-' . $test_name . '-' . microtime(true);

        try {
            xdebug_stop_code_coverage(false);
            $coverageName = $current_dir . '/coverages/coverage-' . $test_name . '-' . microtime(true);
            $codecoverageData = json_encode(xdebug_get_code_coverage());
            file_put_contents($coverageName . '.json', $codecoverageData);
        } catch (Exception $ex) {
            file_put_contents($coverageName . '.ex', $ex);
        }
    }

    class coverage_dumper
    {
        function __destruct()
        {
            try {
                end_coverage();
            } catch (Exception $ex) {
                echo str($ex);
            }
        }
    }

    $_coverage_dumper = new coverage_dumper();

}



