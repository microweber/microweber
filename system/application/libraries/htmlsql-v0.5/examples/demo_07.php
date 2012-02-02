<?php

    /*
    ** htmlSQL - Example 7
    **
    ** Shows a complex query
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to a URL
    if (!$wsql->connect('url', 'http://codedump.jonasjohn.de/browse/lang/php/')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /* execute a query:
       
       This query searches all links where the URL starts with /snippets and the text starts with 
       "array_" => so all links to array functions will be returned
    */
    if (!$wsql->query('SELECT * FROM a WHERE preg_match("/^\/snippets/i", $href) and preg_match("/^array_/i", $text)')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // fetch results as array return them:
    foreach($wsql->fetch_array() as $row){
    
        print_r($row);
        
    }
    
?>