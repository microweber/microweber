#!/bin/bash

phpunit=/Applications/MAMP/bin/php5/bin/phpunit
cd ..
$phpunit test/Tests/
cd -
rm "db/qpTest.db"
rm "db/qpTest2.db"
