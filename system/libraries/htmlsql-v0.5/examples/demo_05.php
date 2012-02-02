<?php

    /*
    ** htmlSQL - Example 5
    **
    ** Shows a advanced query (with substr)
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to a URL
    if (!$wsql->connect('url', 'http://codedump.jonasjohn.de/links.htm')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /* execute a query:
       
       This query returns all links of an document that not start with / 
       ( / = internal links)
    */
    if (!$wsql->query('SELECT * FROM a WHERE substr($href,0,1) != "/"')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // fetch results as object and format as HTML links:
    foreach($wsql->fetch_objects() as $obj){
    
        print '<a href="'.$obj->href.'">'.$obj->text.'</a><br/>';
        print "\n";
        
    }
    
?>