<?php

    /*
    ** htmlSQL - Example 1
    **
    ** Shows a simple query
    */
    
    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to a URL
    if (!$wsql->connect('url', 'http://codedump.jonasjohn.de/')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /* execute a query:
        
       This query extracts all links with the classname = nav_item   
    */
    if (!$wsql->query('SELECT * FROM a WHERE $class == "nav_item"')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // show results:
    foreach($wsql->fetch_array() as $row){
    
        print_r($row);
        
        /* 
        $row is an array and looks like this:
        Array (
            [href] => /feedback.htm
            [class] => nav_item
            [tagname] => a
            [text] => Feedback
        )
        */
        
    }
    
?>