<?php

/*
htmlSQL - version 0.5
--------------------------------------------------------------------
htmlSQL is a experimental class to query websites or HTML code with 
an SQL-like language.

AUTHOR: Jonas John (http://www.jonasjohn.de/)

The latest version of htmlSQL can be obtained from:
http://www.jonasjohn.de/lab/htmlsql.htm

LICENSE:
--------------------------------------------------------------------
Copyright (c) 2006 Jonas John. All rights reserved.
--------------------------------------------------------------------
Redistribution and use in source and binary forms, with or without 
modification, are permitted provided that the following conditions 
are met:

- Redistributions of source code must retain the above copyright 
  notice, this list of conditions and the following disclaimer.
- Redistributions in binary form must reproduce the above copyright 
  notice, this list of conditions and the following disclaimer in 
  the documentation and/or other materials provided with the distribution.
- Neither the name of Jonas John nor the names of its contributors 
  may be used to endorse or promote products derived from this 
  software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS 
FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE 
COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS 
OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND 
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR 
TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE 
USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
    
--------------------------------------------------------------------

CHANGELOG:

0.4 -> 0.5 (May 07, 2006):
- Renamed the project from webSQL to htmlSQL, because webSQL
  is already existing... :-(
- Added some error checks and error messages 
- Added the convert_tagname_to_key function and
  fixed a few issues

0.1 -> 0.4 (April 2006):
- Created main parts of the class
    
*/

class htmlsql {

    // configuration:

    // htmlSQL version:
    var $version = '0.5';

    // referer and user agent:
    var $referer = '';
    var $user_agent = 'htmlSQL/0.5';
    
    
    
    // these are filled on runtime:
    // (don't touch them)
    
    // holds snoopy object:
    var $snoopy = NULL;
    
    // the results array is stored in here:
    var $results = array();
    
    // the results objects are stored in here:
    var $results_objects = NULL;

    // the error message gets stored in here:
    var $error = '';
    
    // the downloaded page is stored in here:
    var $page = '';
    
    
    /*
    ** init_snoopy
    **
    ** initializes the snoopy class
    */
    
    function init_snoopy(){
        $this->snoopy = new Snoopy();
        $this->snoopy->agent = $this->user_agent;
        $this->snoopy->referer = $this->referer;
    }
    
    
    /*
    ** set_user_agent
    **
    ** set a custom user agent
    */
    
    function set_user_agent($u){ 
        $this->user_agent = $u;
    }
    
    
    
    /*
    ** set_referer
    **
    ** sets the referer
    */
    
    function set_referer($r){ 
        $this->referer = $r;
    }
    
    
    /*
    ** _get_between
    **
    ** returns the content between $start and $end
    */
    
    function _get_between($content,$start,$end){
        $r = explode($start, $content);
        if (isset($r[1])){
            $r = explode($end, $r[1]);
            return $r[0];
        }
        return '';
    }
    
    
    /*
    ** connect
    **
    ** connects to a data source (url, file or string)
    */
    
    function connect($type, $resource){        
        if ($type == 'url'){ 
            return $this->_fetch_url($resource);
        }
        else if ($type == 'file') { 
            if (!file_exists($resource)){ 
                $this->error = 'The given file "'.$resource.' does not exist!';
                return false;
            }
            $this->page = file_get_contents($resource); return true;
        }
        else if ($type == 'string') { $this->page = $resource; return true; }
        
        return false;
    }
    
    
    /*
    ** _fetch_url
    **
    ** downloads the given URL with snoopy
    */
    
    function _fetch_url($url){
    
        $parsed_url = parse_url($url);
        
        if (!isset($parsed_url['scheme']) or $parsed_url['scheme'] != 'http'){ 
            $this->error = 'Unsupported URL sheme given, please just use "HTTP".';
            return false;
        }
        if (!isset($parsed_url['host']) or $parsed_url['host'] == ''){ 
            $this->error = 'Invalid URL given!';
            return false;
        }
        
        $host = $parsed_url['host'];
        $host .= (isset($parsed_url['port']) and  !empty($parsed_url['port'])) ? ':'.$parsed_url['port'] : '';
        $path = (isset($parsed_url['path']) and  !empty($parsed_url['path'])) ? $parsed_url['path'] : '/';
        $path .= (isset($parsed_url['query']) and  !empty($parsed_url['query'])) ? '?'.$parsed_url['query'] : '';
        
        $url = 'http://' . $host . $path;
    
        $this->init_snoopy();
        
        if($this->snoopy->fetch($url)){
        
            $this->page = $this->snoopy->results;
            
            // empty buffer:
            $this->snoopy->results = '';                
        }
        else {
            $this->error = 'Could not establish a connection to the given URL!';
            return false;
        }            
        return true;        
    }
    
    
    /*
    ** _extract_all_tags
    **
    ** 
    */
    
    function _extract_all_tags($html, &$tag_names, &$tag_attributes, &$tag_values, $depth=0){
        
        // stop endless loops:
        if ($depth > 99999){ return; }
        
        preg_match_all('/<([a-z0-9\-]+)(.*?)>((.*?)<\/\1>)?/is', $html, $m);
        if (count($m[0]) != 0){
            for ($t=0; $t < count($m[0]); $t++){
            
                $tag_names[] = trim($m[1][$t]);
                $tag_attributes[] = trim($m[2][$t]);
                $tag_values[] = trim($m[4][$t]);
                
                // go deeper:
                if (trim($m[4][$t]) != '' and preg_match('/<[a-z0-9\-]+.*?>/is', $m[4][$t])){
                    $this->_extract_all_tags($m[4][$t], $tag_names, $tag_attributes, $tag_values, $depth+1);
                }
                
            }
        }
        
    }
    
    
    /*
    ** isolate_content
    **
    ** isolates the content to a specific part
    */
    
    function isolate_content($start,$end){
    
        $this->page = $this->_get_between($this->page, $start, $end);
    
    }
    

    /*
    ** select
    **
    ** restricts the content of a specific tag
    */
    
    function select($tagname, $num=0){        
        $num++;
    
        if ($tagname != ''){
        
            preg_match('/<'.$tagname.'.*?>(.*?)<\/'.$tagname.'>/is', $this->page, $m);
        
            if (isset($m[$num]) and !empty($m[$num])){ 
                $this->page = $m[$num];
            } 
            else {
                $this->error = 'Could not select tag: "'.$tagname.'('.$num.')"!';
                return false;
            }                
        }
        return true;        
    }
    
    
    /*
    ** get_content
    **
    ** returns the content of an request
    */
    
    function get_content(){ 
        return $this->page;
    }
    
    
    /*
    ** _clean_array
    **
    ** 
    */
    
    function _clean_array($arr){
        $new = array();
        for ($x=0; $x < count($arr); $x++){
            $arr[$x] = trim($arr[$x]);
            if ($arr[$x] != ''){ $new[] = $arr[$x]; }
        }
        return $new;
    }
    
    
     /*
    ** _test_tag
    **
    ** 
    */
    
    function _test_tag($tag_attributes, $if_term){
    
        preg_match_all('/\$([a-z0-9_\-]+)/i', $if_term, $m);
        if (isset($m[1])){
            for ($x=0; $x < count($m[1]); $x++){
                $varname = $m[1][$x];
                $$varname = '';
            }
        }
        
        $new_list = array();
        while (list($k,$v) = each($tag_attributes)){
            $k = preg_replace('/[^a-z0-9_\-]/i', '', $k);
            if ($k != ''){ $new_list[$k] = $v; }
        }
        unset($tag_attributes);
        
        extract($new_list);    
        
        $r = false;            
        if (@eval('$r = ('.$if_term.');') === false){
            $this->error = 'The WHERE statement is invalid (eval() failed)!';
            return false;
        }
        
        return $r;
    
    }
    
    
    /*
    ** _match_tags
    **
    ** 
    */
    
    function _match_tags(&$results, &$return_values, &$where_term, &$tag_attributes, &$tag_values, &$tag_names){
    
        $search_mode = ''; $search_attribute = ''; $search_term = '';
        
        /*
        ** parse:
        ** 
        ** href LIKE ".htm"
        ** class = "foo"
        */
        
        $where_term = trim($where_term);

        $search_mode = ($where_term == '') ? 'match_all' : 'eval';

        for ($x=0; $x < count($tag_attributes); $x++){
        
            $tag_attributes[$x] = $this->parse_attributes($tag_attributes[$x]);
            
            if (is_array($tag_names)){ 
                $tag_attributes[$x]['tagname'] = isset($tag_names[$x]) ? $tag_names[$x] : '';
            } 
            else { $tag_attributes[$x]['tagname'] = $tag_names; } // string
            
            $tag_attributes[$x]['text'] = isset($tag_values[$x]) ? $tag_values[$x] : '';

            if ($search_mode == 'eval'){
            
                if ($this->_test_tag($tag_attributes[$x], $where_term)){
                    $this->_add_result($results, $return_values, $tag_attributes[$x]);
                }                   
            
            }
            else if ($search_mode == 'match_all'){
                $this->_add_result($results, $return_values, $tag_attributes[$x]);
            }
        }
    }
    
    
    /*
    ** query
    **
    ** performs a query
    */
    
    function query($term){
    
        // query results are stored in here:
        $results = array();
        $this->results = NULL;
        $this->results_objects = NULL;
    
        $term = trim($term);
        if ($term == ''){
            $this->error = 'Empty query given!';
            return false;
        }
        
        // match query:
        preg_match('/^SELECT (.*?) FROM (.*)$/i', $term, $m);
        
        // parse returns values
        // SELECT * FROM ...
        // SELECT foo,bar FROM ...
        $return_values = isset($m[1]) ? trim($m[1]) : '*';
        if ($return_values != '*'){ 
            $return_values = explode(',', strtolower($return_values));
            $return_values = $this->_clean_array($return_values);                
        }
        
        // match from and where part:
        //
        // ... FROM * WHERE $id=="one"
        // ... FROM a WHERE $class=="red"
        // ... FROM a 
        // ... FROM *
        $last = isset($m[2]) ? trim($m[2]) : '';
        
        $search_term = '';
        $where_term = '';
        
        if (preg_match('/^(.*?) WHERE (.*?)$/i', $last, $m)){
            $search_term = trim($m[1]);
            $where_term = trim($m[2]);
        }
        else {
            $search_term = $last;
        }
        
        /*
        ** find tags:
        */

        if ($search_term == '*'){
            // search all

            $tag_names = array();
            $tag_attributes = array();
            $tag_values = array();

            $html = $this->page;
            
            $this->_extract_all_tags($html, $tag_names, $tag_attributes, $tag_values);
            
            $this->_match_tags($results, $return_values, $where_term, $tag_attributes, $tag_values, $tag_names);
            
        }
        else {
        
            // search term is a tag
                        
            $tagname = trim($search_term);
        
            $tag_attributes = array();
            $tag_values = array();

            $regexp = '<'.$tagname.'([ \t].*?|)>((.*?)<\/'.$tagname.'>)?';
            preg_match_all('/'.$regexp.'/is', $this->page, $m);
            
            if (count($m[0]) != 0){
                $tag_attributes = $m[1];
                $tag_values = $m[3];
            }
            
            $this->_match_tags($results, $return_values, $where_term, $tag_attributes, $tag_values, $tagname);
        }
       
        $this->results = $results;
        
        // was there a error during the search process?
        if ($this->error != ''){
            return false;
        }
                    
        return true;
    
    }
    
    /*
    ** convert_tagname_to_key
    **
    ** converts the tagname to the array key
    */
    
    function convert_tagname_to_key(){
            
        $new_array = array();
    
        while(list($key,$val) = each($this->results)){
            
            if (isset($val['tagname'])){
                $tag_name = $val['tagname'];
                unset($val['tagname']);
            } 
            else { $tag_name = '(empty)'; }
           
            $new_array[$tag_name] = $val;
                
        }
    
        $this->results = $new_array;
    }
    
    
    /*
    ** fetch_array
    **
    ** returns the results as an array
    */
    
    function fetch_array(){
        return $this->results;
    }
    
    
    /*
    ** _array2object
    **
    ** converts an array to an object
    */
    
    function _array2object($array) {

        if (is_array($array)) {
        
            $obj = new StdClass();
        
            foreach ($array as $key => $val){        
                $obj->$key = $val;
            }
        
        }
        else { $obj = $array; }
        
        return $obj;
    }
    
    
    /*
    ** fetch_objects
    **
    ** returns the results as objects
    */
    
    function fetch_objects(){
        
        if ($this->results_objects == NULL){
        
            $results = array();
            
            reset($this->results);
            while(list($key,$val) = each($this->results)){
                $results[$key] = $this->_array2object($val);
            }
        
            $this->results_objects = $results;
            
            return $this->results_objects;
        }
        else {
            return $this->results_objects;
        }
    }
    
    /*
    ** get_result_count
    **
    ** returns the number of results
    */
    
    function get_result_count(){
        return count($this->results);
    }
    
    
    /*
    ** _add_result
    **
    ** 
    */
    
    function _add_result(&$results, $return_values, $tag_attributes){

        if ($return_values == '*'){
            $results[] = $tag_attributes;
        }
        else if (is_array($return_values)){
        
            $new_result = array(); 
            
            reset($return_values);
            for ($t=0; $t < count($return_values); $t++){
            
                $_tagname = explode(' as ', $return_values[$t]);
                $_caption = $return_values[$t];
                
                if (count($_tagname) != 1){ 
                    $_caption = trim($_tagname[1]);
                    $_tagname = trim($_tagname[0]);
                }
                else { $_tagname = $_caption; }

                $new_result[$_caption] = isset($tag_attributes[$_tagname]) ? $tag_attributes[$_tagname] : '';
            }
            $results[] = $new_result;
        }
    }
    
    
    /*
    ** parse_attributes
    **
    ** parses HTML attributes and returns an array
    */
    
    function parse_attributes($attrib){
        
        $attrib .= '>';
        
        $mode = 'search_key';
        $tmp = ''; 
        $current_key = '';
        
        $attributes = array();
        
        for ($x=0; $x < strlen($attrib); $x++){
        
            $char = $attrib[$x];
            
            if ($char == '=' and $mode == 'search_key'){
                $current_key = trim($tmp);
                $tmp = '';
                $mode = 'value';
            }
            else if ($mode == 'search_key' and preg_match('/[ \t\s\r\n>]/', $char)){ 
                $current_key = strtolower(trim($tmp));
                if ($current_key != ''){ $attributes[$current_key] = ''; }
                $tmp = ''; $current_key = '';
            }
            else if ($mode == 'value' and $char == '"'){ $mode = 'find_value_ending_a'; }
            else if ($mode == 'value' and $char == '\''){ $mode = 'find_value_ending_b'; }
            else if ($mode == 'value'){ $tmp .= $char; $mode = 'find_value_ending_c'; }
            else if (
                ($mode == 'find_value_ending_a' and $char == '"') or 
                ($mode == 'find_value_ending_b' and $char == '\'') or 
                ($mode == 'find_value_ending_c' and preg_match('/[ \t\s\r\n>]/', $char))
            ){ 
                
                $mode = 'search_key';
                
                if ($current_key != ''){
                    $current_key = strtolower($current_key);
                    $attributes[$current_key] = $tmp;
                }
                $tmp = '';
            }
            else { $tmp .= $char; }                
        }
        
        if ($mode != 'search_key' and $current_key != ''){ 
            $current_key = strtolower($current_key);
            $attributes[$current_key] = trim(preg_replace('/>+$/', '', $tmp));
        }
    
        return $attributes;
    
    }

}
    
?>