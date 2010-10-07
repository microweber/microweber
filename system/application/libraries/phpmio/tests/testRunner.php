<?php
require 'simpletest/unit_tester.php';
require 'simpletest/mock_objects.php';
require 'simpletest/reporter.php';
define( 'MIO_PATH', realpath( dirname(__FILE__) . '/../' ) . '/' );

require 'spikecoverage/CoverageRecorder.php';
require 'spikecoverage/reporter/HtmlCoverageReporter.php';

$coverage_reporter = new HtmlCoverageReporter( "Code Coverage Report", "", "reports" );
$include = array( MIO_PATH );
$exclude = array( MIO_PATH . "tests", MIO_PATH . "example" );
$coverage_recorder = new CoverageRecorder( $include, $exclude, $coverage_reporter );


$group = new GroupTest( 'All Muliplexing I/O Tests' );

$dir = opendir( MIO_PATH . '/tests' );
while( $file = readdir( $dir ) ) {
    if( is_file( $file ) && substr( $file, 0, 1 ) != '.' && $file != 'testRunner.php' ) {
        require MIO_PATH . 'tests/' . $file;
        $class = 'Mio'.substr( $file, 0, -4 );
        $group->addTestClass( new $class() );
    }
}

$coverage_recorder->startInstrumentation();
$group->run( new TextReporter );
$coverage_recorder->stopInstrumentation();
$coverage_recorder->generateReport();
$coverage_reporter->printTextSummary();
