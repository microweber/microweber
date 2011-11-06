<?php

    /*
    ** htmlSQL - Example 2
    **
    ** Shows a simple query and the "href as url" usage
    */
    
    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to a file
    if (!$wsql->connect('file', 'demo_data.htm')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /* execute a query:
       
       This query extracts all links from the document
       and just returns href (as url) and text
    */
    if (!$wsql->query('SELECT href as url, text FROM a')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // show results:
    foreach($wsql->fetch_array() as $row){
    
        print "Link-URL: " . $row['url'] . "\n";
        print "Link-Text: " . trim($row['text']) . "\n\n";
        
    }
    
?>