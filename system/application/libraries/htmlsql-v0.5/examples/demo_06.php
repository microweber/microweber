<?php

    /*
    ** htmlSQL - Example 6
    **
    ** Show how to connect to a string
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    
    $some_html = '<a href="link1.htm">link1</a> <b>foobar</b> ';
    $some_html .= '<a href="link2.htm">link2</a> <hr/>';
    
    $wsql = new htmlsql();
    
    // connect to a string
    if (!$wsql->connect('string', $some_html)){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /* execute a query:
       
       This query returns all links of the given HTML
    */
    if (!$wsql->query('SELECT * FROM a')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // fetch results as array and output them:
    foreach($wsql->fetch_array() as $row){
    
        print_r($row);
        
    }
    
?>