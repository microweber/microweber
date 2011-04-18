<?php

require_once 'config.php';

$query = sprintf("INSERT INTO config (token, value)
                  VALUES ('%s', '%s')",
                  $_POST['id'], stripslashes($_POST['value']));

$dbh->exec($query);

/* sleep for a while so we can see the indicator in demo */
usleep(2000);

$renderer = $_GET['renderer'] ?  $_GET['renderer'] : $_POST['renderer'];
if ('textile' == $renderer) {
    require_once './Textile.php';
    $t = new Textile();
    /* What is echoed back will be shown in webpage after editing.*/
    print $t->TextileThis(stripslashes($_POST['value']));
} else {
    /* What is echoed back will be shown in webpage after editing.*/
    print $_POST['value']; 
}
