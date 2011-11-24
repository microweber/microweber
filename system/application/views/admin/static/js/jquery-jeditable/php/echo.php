<?php

/* Does not save anything. Just echoes back for demonstration purposes. */

$renderer = isset($_GET['renderer']) ?  $_GET['renderer'] : $_POST['renderer'];
if ('textile' == $renderer) {
    require_once './Textile.php';
    $t = new Textile();
    print $t->TextileThis(stripslashes($_POST['value']));
} else {
    print $_POST['value']; 
}
