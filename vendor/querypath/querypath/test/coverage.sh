#!/bin/bash

phpunit=/Applications/MAMP/bin/php5/bin/phpunit
$phpunit --coverage-html coverage Tests
rm "db/qpTest.db"
rm "db/qpTest2.db"