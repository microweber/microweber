<?php

    /*
    ** htmlSQL - Example 11
    **
    ** Shows how to query a simple XML file
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to the demo XML file:
    if (!$wsql->connect('file', 'demo_xml.xml')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }

    /* execute a query:
       
       This query returns the id, name and password of all active users
    */
    if (!$wsql->query('SELECT id, name, password FROM user WHERE $status == "active"')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // fetch results as array
    foreach($wsql->fetch_array() as $row){
    
        print_r($row);
        
    }
    
?>