<?php

    /*
    ** htmlSQL - Example 10
    **
    ** Shows how to use the "isolate_content" function
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to a URL
    if (!$wsql->connect('url', 'http://codedump.jonasjohn.de/')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /*
    ** The isolate_content functions works like the select function,
    ** but you can specify custom HTML parts, the content between
    ** these two strings will be used for the query process
    **
    ** In this case we select all content between "<h1>New snippets</h1>"
    ** and "<p id="rss">" this returns all snippet links, and no other links
    ** (like header or navigation links)
    */

    $wsql->isolate_content('<h1>New snippets</h1>', '<p id="rss">');
    
    /*
        other examples:
    
        $wsql->isolate_content('<body>', '</body>');
        $wsql->isolate_content('<!--content:start-->', '<!--end-->');
    */
    
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