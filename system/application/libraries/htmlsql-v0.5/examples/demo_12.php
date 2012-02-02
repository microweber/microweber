<?php

    /*
    ** htmlSQL - Example 12
    **
    ** Shows how to replace the user agent and the referer with
    ** custom values
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // set a individual agent:
    $wsql->set_user_agent('MyAgentName/0.9');
    
    // set a new referer:
    $wsql->set_referer('http://www.jonasjohn.de/custom/referer/');
    
    
    // connect to a URL
    if (!$wsql->connect('url', 'http://codedump.jonasjohn.de/')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /* execute a query:
       
       This query returns all links:
    */
    if (!$wsql->query('SELECT * FROM a')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // fetch results as array
    foreach($wsql->fetch_array() as $row){
    
        print_r($row);
        
    }
    
?>