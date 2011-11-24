<?php

    /*
    ** htmlSQL - Example 8
    **
    ** Shows how to parse a RSS/XML file with htmlSQL
    */

    include_once("../snoopy.class.php");
    include_once("../htmlsql.class.php");
    
    $wsql = new htmlsql();
    
    // connect to the RSS URL (this URL contains new snippets from my codedump project)
    if (!$wsql->connect('url', 'http://codedump.jonasjohn.de/rss/')){
        print 'Error while connecting: ' . $wsql->error;
        exit;
    }
    
    /* execute a query:
       
       select the text attribute (alias for the tag content) from the <item> tag
    */
    if (!$wsql->query('SELECT text FROM item')){
        print "Query error: " . $wsql->error; 
        exit;
    }

    // fetch all results as objects:
    foreach($wsql->fetch_objects() as $obj){
        
        // create a new htmlsql object:
        $sub_wsql = new htmlsql();
        
        // connect to the <item> content:
        $sub_wsql->connect('string', $obj->text);
            
        // fetch all attributes of all tags:
        if (!$sub_wsql->query('SELECT * FROM *')){
            print "Query error: " . $wsql->error; 
            exit;
        }
        
        // this "special" function converts tagnames to keys
        $sub_wsql->convert_tagname_to_key();
        
        /* this function converts an array that looks like this:
        
            $array[0]['tagname'] = 'title';
            $array[0]['text'] = 'example 1';
            
            $array[1]['tagname'] = 'link';
            $array[1]['text'] = 'http://www.example.org/';
            
            $array[2]['tagname'] = 'description';
            $array[2]['text'] = 'description bla';
            $array[2]['fulltext'] = '1'; // additional attribute
            
            -> to:
            
            $array['title']['text'] = 'example 1';
            
            $array[1]['link']['text'] = 'http://www.example.org/';
            
            $array[2]['description']['text'] = 'description bla';
            $array[2]['description']['fulltext'] = '1'; // additional attribute
            
            this makes the array easier to access
            
        */
        
        
        // fetch item as array:
        $item = $sub_wsql->fetch_array();
                
        // format the extracted links as HTML links and output them:
        print "<a href=\"" . $item['link']['text'] . "\">";
        print $item['title']['text'] . "</a><br/>\n";
        
        // also available:
        // description, pubDate
        
        
    }
    
?>