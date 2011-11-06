<?php

    /*
    ** htmlSQL - Example 9
    **
    ** Shows how to use the "select" function
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to a URL
    if (!$wsql->connect('url', 'http://codedump.jonasjohn.de/')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    // restricts the search process to the content between
    // <body> and </body>
    // this also works with other tags like: head or html, or table
    $wsql->select('body');
    
    /*
        other examples:
    
        $wsql->select('div',3); <-- selects the third <div>
        
        $wsql->select('table',0); <-- selects the first table        
                            ^ default is also = 0
    */
    
    
    /* execute a query:
       
       This query returns all <h1> headers
    */
    if (!$wsql->query('SELECT * FROM h1')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // fetch results as array
    foreach($wsql->fetch_array() as $row){
    
        print_r($row);
        
    }
    
?>