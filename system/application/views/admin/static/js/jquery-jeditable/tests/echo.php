<?php

/* $Id: echo.php 117 2007-03-02 16:16:08Z tuupola $ */

$renderer = isset($_GET['renderer']) ?  $_GET['renderer'] : $_POST['renderer'];
if ('textile' == $renderer) {
    require_once './Textile.php';
    $t = new Textile();
    print $t->TextileThis(stripslashes($_POST['value']));
} else {
    print $_POST['value']; 
}

