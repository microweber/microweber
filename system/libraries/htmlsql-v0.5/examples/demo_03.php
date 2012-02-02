<?php

    /*
    ** htmlSQL - Example 3
    **
    ** Shows how to connect to a file and a simple query
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
       
       This query searches in all tags for the id == header and returns
       the tag
    */
    if (!$wsql->query('SELECT * FROM * WHERE $id == "header"')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // show results:
    foreach($wsql->fetch_array() as $row){
    
        print_r($row);
        
    }
    
?>