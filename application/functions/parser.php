<?php

// include_once ('parser/phpQuery.php');

/**
 * Parses the microweber tags from the $layout back to html content.
 *
 * @param string $layout
 * @param array $options
 *        	$options['no_doctype_strip'] = false //if true will not remove the
 *              $options['parse_only_vars'] = true ; // will skip modules parsing only will parse vars like {SITE_URL}.
 *        	doctype
 * @return string $layout
 */

/**
 *
 *
 * parse_micrwober_tags()
 *
 * @param string $layout
 * @param array $options
 * @return
 *
 *
 *
 *
 *
 *
 *
 */
function parse_micrwober_tags($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false) {







    if (!isset($options['parse_only_vars'])) {


        require_once (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'phpQuery.php');

        $layout = str_replace('<mw ', '<module ', $layout);
        $layout = str_replace('<editable ', '<div class="edit" ', $layout);
        $layout = str_replace('</editable>', '</div>', $layout);

        $layout = str_replace('<microweber module=', '<module data-type=', $layout);
        $layout = str_replace('</microweber>', '', $layout);
        $layout = str_replace('></module>', '/>', $layout);

        // $layout = preg_match_all('#<script(.*?)>(.*?)</script>#is', '', $layout);
        $script_pattern = "/<script[^>]*>(.*)<\/script>/Uis";
        $replaced_scripts = array();
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = 'mw_replace_back_this_script_' . $v1;
                    $layout = str_replace($value, $v1, $layout);
                    // $layout = str_replace_count($value, $v1, $layout,1);
                    if (!isset($replaced_scripts [$v1])) {
                        $replaced_scripts [$v1] = $value;
                    }
                    // p($value);
                }
            }
        }
        $script_pattern = "/<module[^>]*>/Uis";

        //$script_pattern = "/<module.*.[^>]*>/is";
        $replaced_modules = array();
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            $matches1 = $mw_script_matches [0];
            foreach ($matches1 as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = 'mw_replace_back_this_module_' . $v1;
                    $layout = str_replace($value, $v1, $layout);
                    // $layout = str_replace_count($value, $v1, $layout,1);
                    if (!isset($replaced_modules [$v1])) {

                        $replaced_modules [$v1] = $value;
                    }
                    // p($value);
                }
            }
        }
        $layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");

        // $layout = str_replace('<script ', '<TEXTAREA ', $layout);
        // $layout = str_replace('</script', '</TEXTAREA', $layout);

        $pq = phpQuery::newDocument($layout);

        $els = $pq ['.edit'];
        // $els = pq('body')->find('.edit')->filter(':not(script)');

        foreach ($els as $elem) {
            // iteration returns PLAIN dom nodes, NOT phpQuery objects
            $tagName = $elem->tagName;
            $name = pq($elem)->attr('field');

            if (strval($name) == '') {
                $name = pq($elem)->attr('id');
            }

            if (strval($name) == '') {
                $name = pq($elem)->attr('data-field');
            }

            $rel = pq($elem)->attr('rel');
            if ($rel == false) {
                $rel = 'page';
            }

            $get_global = false;
            $rel = 'page';
            $field = $name;
            $use_id_as_field = $name;

            if ($rel == 'global') {
                $get_global = true;
            } else {
                $get_global = false;
            }

            if ($rel == 'page') {
                $data = get_page(PAGE_ID);
                $data ['custom_fields'] = get_custom_fields_for_content($data ['id'], 0);
            } else if ($attr ['post']) {
                $data = get_post($attr ['post']);
                if ($data == false) {
                    $data = get_page($attr ['post']);
                    $data ['custom_fields'] = get_custom_fields_for_content($data ['id'], 0);
                }
            } else if ($attr ['category']) {
                $data = get_category($attr ['category']);
            } else if ($attr ['global']) {
                $get_global = true;
            }
            $cf = false;
            $field_content = false;

            if ($get_global == true) {
                $field_content = get_option($field, $return_full = false, $orderby = false);
            } else {

                if ($use_id_as_field != false) {
                    if (isset($data [$use_id_as_field])) {
                        $field_content = $data [$use_id_as_field];
                    }
                    if ($field_content == false) {
                        if (isset($data ['custom_fields'] [$use_id_as_field])) {
                            $field_content = $data ['custom_fields'] [$use_id_as_field];
                        }
                        // d($field_content);
                    }
                }

                if ($field_content == false) {
                    if (isset($data [$field])) {

                        $field_content = $data [$field];
                    }
                }
            }

            if (isset($data ['custom_fields']) and !empty($data ['custom_fields'])) {
                foreach ($data ['custom_fields'] as $kf => $vf) {

                    if ($kf == $field) {

                        $field_content = ($vf);
                    }
                }
            }

            //  d($field);

            if ($field_content != false and $field_content != '') {
                $field_content = html_entity_decode($field_content, ENT_COMPAT, "UTF-8");

                //  d($field_content);
                $field_content = parse_micrwober_tags($field_content);
                pq($elem)->html($field_content);
            } else {
                
            }
        }
        $layout = $pq->htmlOuter();
        /*
         * foreach($pq['mw'] as $elem) { $name = pq($elem)->attr('module');
         * $attributes = array(); $ats = $elem->attributes; $module_html = "<module
         * "; if (!empty($ats)) { foreach($ats as $attribute_name =>
         * $attribute_node) { $v = $attribute_node->nodeValue; $module_html .= "
         * {$attribute_name}='{$v}' "; } } $module_html. ' />';
         * pq($elem)->replaceWith($module_html) ; } $els = $pq['footer']; //$els =
         * pq('module')->filter(':not(script)'); foreach ($els as $elem) { $name =
         * pq($elem) -> attr('module'); $attrs = $elem -> attributes; $z = 0;
         * foreach ($attrs as $attribute_node) { $nn = $attribute_node -> nodeName;
         * $v = $nv = $attribute_node -> nodeValue; if ($z == 0) { $module_name =
         * $nn; } else { } $mod_attributes[$nn] = $nv; if ($nn == 'module') {
         * $module_name = $nv; } if ($nn == 'type') { $module_name = $nv; } if ($nn
         * == 'data-type') { $module_name = $nv; } if ($nn == 'data-module') {
         * $module_name = $nv; } $z++; } // $mod_content = load_module($module_name,
         * $attrs); $mod_content = parse_micrwober_tags($mod_content); if
         * ($mod_content != false) { $module_html = "<div class='module' "; if
         * (!empty($attrs)) { foreach ($attrs as $attribute_name => $attribute_node)
         * { $v = $attribute_node -> nodeValue; $module_html .= "
         * {$attribute_name}='{$v}' "; } } $module_html .= '>' . $mod_content .
         * '</div>'; pq($elem) -> replaceWith($module_html); } } if
         * ($options['mw_embed']) { $em = trim($options['mw_embed']); if ($em != '')
         * { foreach ($pq['#'.$em] as $elem) { pq($elem) -> parents('body') ->
         * replaceWith($elem); } } }
         */
        // $layout = $pq;
        // $layout = str_replace('<mw-script ', '<script ', $layout);
        // $layout = str_replace('</mw-script', '</script', $layout);
        // .$layout = html_entity_decode($layout, ENT_NOQUOTES, "UTF-8");
        // if (!empty($scripts)) {
        // if(!empty($mw_script_matches)){
        // $mw_script_matches = $mw_script_matches[0];
        // }
        //
	//

	//
 
	if (!empty($replaced_scripts)) {
            foreach ($replaced_scripts as $key => $value) {
                if ($value != '') {

                    $layout = str_replace($key, $value, $layout);
                }
                unset($replaced_scripts [$key]);
            }
        }

        if (!empty($replaced_modules)) {

            $attribute_pattern = '@
  (?P<name>\w+)# attribute name
  \s*=\s*
  (
(?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote) # a quoted value
| # or
(?P<value_unquoted>[^\s"\']+?)(?:\s+|$)  # an unquoted value (terminated by whitespace or EOF)
  )
  @xsi';


            // (?P<name>\w+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))
            $attribute_pattern = '@(?P<name>[a-z-_A-Z]+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))@xsi';
//$attribute_pattern = '@([a-z-A-Z]+)=\"([^"]*)@xsi';
            $attrs = array();
            foreach ($replaced_modules as $key => $value) {
                if ($value != '') {

                    if (preg_match_all($attribute_pattern, $value, $attrs1, PREG_SET_ORDER)) {
                        foreach ($attrs1 as $item) {
//d($attrs1);
                            $m_tag = trim($item [0], "\x22\x27");
                            $m_tag = trim($m_tag, "\x27\x22");
                            $m_tag = explode('=', $m_tag);

                            $a = trim($m_tag [0], "''");
                            $a = trim($a, '""');

                            $b = trim($m_tag [1], "''");
                            $b = trim($b, '""');

                            $attrs [$a] = $b;

                            // $attrs[$item['name']] = $item['value_quoted'];
                        }
                    }

                    $m_tag = ltrim($value, "<module");

                    $m_tag = rtrim($m_tag, "/>");
                    $m_tag = rtrim($m_tag);

                    // $m_tag =
                    // preg_replace(array('/style=[\'\"].+?[\'\"]/','/style=/'), '',
                    // $m_tag);
                    // Find all tags
                    //
				//
				// $m_tag = explode(' ', $m_tag);
                    // // $m_tag2 = join('&', $m_tag);
                    // // $m_tag2 = parse_str($m_tag2);
                    // $attrs = array();
                    // if (isset($m_tag[0])) {
                    // foreach ($m_tag as $m_tag_param) {
                    //
				// if ($m_tag_param != '') {
                    //
				//
				//
				// $m_tag_param = explode('=', $m_tag_param);
                    // $a = trim($m_tag_param[0], "\x22\x27");
                    // if ($a != 'style') {
                    // $b = trim($m_tag_param[1], "\x22\x27");
                    // $attrs[$a] = $b;
                    // }
                    //
				// }
                    // }
                    //
				// }

                    $module_html = "<div class='__MODULE_CLASS__ __WRAP_NO_WRAP__' ";

                    // $module_html = "<div class='module __WRAP_NO_WRAP__' ";
                    $module_has_class = false;

                    if (!empty($attrs)) {

                        if (isset($attrs ['module']) and $attrs ['module']) {
                            $attrs ['data-type'] = $attrs ['module'];
                            unset($attrs ['module']);
                        }

                          if ($coming_from_parent == true) {
                                 $attrs ['data-parent-module'] = $coming_from_parent;
                            }
                         if ($coming_from_parent_id == true) {
                                 $attrs ['data-parent-module-id'] = $coming_from_parent_id;
                            }
                            
                            
                        
                        if (isset($attrs ['type']) and $attrs ['type']) {
                            $attrs ['data-type'] = $attrs ['type'];
                            unset($attrs ['type']);
                        }

                        $z = 0;
                        $mod_as_element = false;
                        foreach ($attrs as $nn => $nv) {





                            if ($nn == 'class') {
                                $module_has_class = $nv;

                                if (stristr($nv, 'module-as-element')) {
                                    $mod_as_element = true;
                                }
                            } else {
                                $module_html .= " {$nn}='{$nv}'  ";
                            }

                            if ($nn == 'module') {
                                $module_name = $nv;
                                $attrs ['data-type'] = $module_name;
                                unset($attrs [$nn]);
                            }


                            if ($nn == 'data-module-name') {
                                $module_name = $nv;
                                $attrs ['data-type'] = $module_name;
                                unset($attrs [$nn]);
                            }

                            if ($nn == 'data-module-name-enc') {

                                unset($attrs [$nn]);
                            }

                            if ($nn == 'type') {
                                $module_name = $nv;
                                $attrs ['data-type'] = $module_name;
                                unset($attrs [$nn]);
                            }

                            if ($nn == 'data-type') {
                                $module_name = $nv;
                            }

                            if ($nn == 'data-module') {
                                $attrs ['data-type'] = $module_name;
                                $module_name = $nv;
                            }




                            $z++;
                        }

                        if (!isset($module_name)) {
                            if (isset($_POST['module'])) {
                                $module_name = $_POST['module'];
                            }
                        }


                        if (isset($module_name)) {
                            if (strstr($module_name, 'admin')) {

                                $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                            } else {
                                // $module_html = str_replace('__WRAP_NO_WRAP__', 'element', $module_html);
                                $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                            }
                            if ($mod_as_element == false) {
                                if (strstr($module_name, 'text')) {

                                    $module_html = str_replace('__MODULE_CLASS__', 'layout-element', $module_html);
                                } else {

                                    $module_html = str_replace('__MODULE_CLASS__', 'module', $module_html);
                                }
                            } else {
                                $module_html = str_replace('__MODULE_CLASS__', 'element', $module_html);
                            }
                            $mod_content = load_module($module_name, $attrs);
                            $coming_from_parentz = $module_name;
                            $coming_from_parent_str = false;
                            $coming_from_parent_strz1 = false;
                            if ($coming_from_parent == true) {
                                $coming_from_parent_str = " data-parent-module='$coming_from_parent' ";
                            }
                              if (isset($attrs ['id']) == true) {
                                $coming_from_parent_strz1 = $attrs ['id'];
                            }
                             

                            $mod_content = parse_micrwober_tags($mod_content, $options, $coming_from_parentz,$coming_from_parent_strz1);
                            $module_html .=  $coming_from_parent_str . '>' . $mod_content . '</div>';

                            $layout = str_replace($key, $module_html, $layout);
                        }
                    }
                    //
                }
                unset($replaced_modules [$key]);
                // $layout = str_replace($key, $value, $layout);
            }
        }
    }
    $layout = str_replace('{SITE_URL}', site_url(), $layout);
    $layout = str_replace('{SITEURL}', site_url(), $layout);

    return $layout;
    exit();
}

function make_microweber_tags($layout) {
    if ($layout == '') {
        return $layout;
    }

    require_once (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'phpQuery.php');

    $pq = phpQuery::newDocument($layout);
    // print first list outer HTML
    // $edit_fields = $pq['.edit'];
    foreach ($pq ['.module'] as $elem) {
        $name = pq($elem)->attr('module');

        $attrs = $elem->attributes;

        $module_html = "<module ";
        if (!empty($attrs)) {
            foreach ($attrs as $attribute_name => $attribute_node) {
                $v = $attribute_node->nodeValue;
                $module_html .= " {$attribute_name}='{$v}'  ";
            }
        }
        $module_html .= ' />';
        pq($elem)->replaceWith($module_html);
    }

    return $pq->htmlOuter();

    return $layout;
}

/**
 *
 * @author Peter Ivanov
 *
 *         function groupsSave($data) {
 *         $table = $table = TABLE_PREFIX . 'groups';
 *         $criteria = $this->input->xss_clean ( $data );
 *         $criteria = $this->core_model->mapArrayToDatabaseTable ( $table,
 *         $data );
 *         $save = $this->core_model->saveData ( $table, $criteria );
 *         return $save;
 *         }
 */
function replace_in_long_text($sRegExpPattern, $sRegExpReplacement, $sVeryLongText, $normal_replace = false) {
    $function_cache_id = false;

    $test_for_long = strlen($sVeryLongText);
    if ($test_for_long > 1000) {
        $args = func_get_args();
        $i = 0;
        foreach ($args as $k => $v) {
            if ($i != 2) {
                $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
            } else {
                
            }
            $i++;
        }

        $function_cache_id = __FUNCTION__ . crc32($sVeryLongText) . crc32($function_cache_id);

        $cache_group = 'extract_tags';
        // $cache_content = $this->cacheGetContent ( $function_cache_id,
        // $cache_group );
        if (($cache_content) != false) {
            // return $cache_content;
        }
    }

    if ($normal_replace == false) {
        $iSet = 0;
        // Count how many times we increase the limit
        while ($iSet < 10) { // If the default limit is 100'000 characters
            // the highest new limit will be 250'000
            // characters
            $sNewText = preg_replace($sRegExpPattern, $sRegExpReplacement, $sVeryLongText);
            // Try
            // to
            // use
            // PREG
            if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) { // Only
                // check on
                // backtrack
                // limit
                // failure
                ini_set('pcre.backtrack_limit', (int) ini_get('pcre.backtrack_limit') + 15000);
                // Get
                // current
                // limit
                // and
                // increase
                $iSet++;
                // Do not overkill the server
            } else { // No fail
                $sVeryLongText = $sNewText;
                // On failure $sNewText would be NULL
                break;
                // Exit loop
            }
        }
    } else {
        $sNewText = str_replace($sRegExpPattern, $sRegExpReplacement, $sVeryLongText);
        // $sNewText = preg_replace($sRegExpPattern,$sRegExpReplacement,
        // $sVeryLongText);
    }

    return $sNewText;
}

function parse_memory_storage($id = false, $content = false) {
    static $parse_mem = array();
    $path_md = ($id);
    // p($parse_mem);
    if ($parse_mem [$path_md] != false) {
        return $parse_mem [$path_md];
    }

    if ($content != false) {
        $parse_mem [$path_md] = $content;
        return $content;
    }
}

function parseTextForEmail($text) {
    $email = array();
    $invalid_email = array();

    $text = ereg_replace("[^A-Za-z._0-9@ ]", " ", $text);

    $token = trim(strtok($text, " "));

    while ($token !== "") {

        if (strpos($token, "@") !== false) {

            $token = ereg_replace("[^A-Za-z._0-9@]", "", $token);

            // checking to see if this is a valid email address
            if (is_valid_email($email) !== true) {
                $email [] = strtolower($token);
            } else {
                $invalid_email [] = strtolower($token);
            }
        }

        $token = trim(strtok(" "));
    }

    $email = array_unique($email);
    $invalid_email = array_unique($invalid_email);

    return array(
        "valid_email" => $email,
        "invalid_email" => $invalid_email
    );
}

function is_valid_email($email) {
    if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$", $email))
        return true;
    else
        return false;
}

/**
 * Decode Unicode Characters from \u0000 ASCII syntax.
 *
 * This algorithm was originally developed for the
 * Solar Framework by Paul M. Jones
 *
 * @link http://solarphp.com/
 * @link http://svn.solarphp.com/core/trunk/Solar/Json.php
 * @param string $value
 * @return string
 */
function decodeUnicodeString($chrs) {
    $delim = substr($chrs, 0, 1);
    $utf8 = '';
    $strlen_chrs = strlen($chrs);

    for ($i = 0; $i < $strlen_chrs; $i++) {

        $substr_chrs_c_2 = substr($chrs, $i, 2);
        $ord_chrs_c = ord($chrs [$i]);

        switch (true) {
            case preg_match('/\\\u[0-9A-F]{4}/i', substr($chrs, $i, 6)) :
                // single, escaped unicode character
                $utf16 = chr(hexdec(substr($chrs, ($i + 2), 2))) . chr(hexdec(substr($chrs, ($i + 4), 2)));
                $utf8 .= self::_utf162utf8($utf16);
                $i += 5;
                break;
            case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F) :
                $utf8 .= $chrs {$i};
                break;
            case ($ord_chrs_c & 0xE0) == 0xC0 :
                // characters U-00000080 - U-000007FF, mask 110XXXXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 2);
                ++$i;
                break;
            case ($ord_chrs_c & 0xF0) == 0xE0 :
                // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 3);
                $i += 2;
                break;
            case ($ord_chrs_c & 0xF8) == 0xF0 :
                // characters U-00010000 - U-001FFFFF, mask 11110XXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 4);
                $i += 3;
                break;
            case ($ord_chrs_c & 0xFC) == 0xF8 :
                // characters U-00200000 - U-03FFFFFF, mask 111110XX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 5);
                $i += 4;
                break;
            case ($ord_chrs_c & 0xFE) == 0xFC :
                // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 6);
                $i += 5;
                break;
        }
    }

    return $utf8;
}

/**
 * Convert a string from one UTF-16 char to one UTF-8 char.
 *
 * Normally should be handled by mb_convert_encoding, but
 * provides a slower PHP-only method for installations
 * that lack the multibye string extension.
 *
 * This method is from the Solar Framework by Paul M. Jones
 *
 * @link http://solarphp.com
 * @param string $utf16
 *        	UTF-16 character
 * @return string UTF-8 character
 */
function utf162utf8($utf16) {
    // Check for mb extension otherwise do by hand.
    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
    }

    $bytes = (ord($utf16 {0}) << 8) | ord($utf16 {1});

    switch (true) {
        case ((0x7F & $bytes) == $bytes) :
            // this case should never be reached, because we are in ASCII range
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0x7F & $bytes);

        case (0x07FF & $bytes) == $bytes :
            // return a 2-byte UTF-8 character
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0xC0 | (($bytes >> 6) & 0x1F)) . chr(0x80 | ($bytes & 0x3F));

        case (0xFFFF & $bytes) == $bytes :
            // return a 3-byte UTF-8 character
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0xE0 | (($bytes >> 12) & 0x0F)) . chr(0x80 | (($bytes >> 6) & 0x3F)) . chr(0x80 | ($bytes & 0x3F));
    }

    // ignoring UTF-32 for now, sorry
    return '';
}

function modify_html($layout, $selector, $content = "") {

    $layout = str_replace($selector, $selector . $content, $layout);


    return $layout;
}

function modify_html_slow($layout, $selector, $action = 'append', $content = "") {



    $pq = phpQuery::newDocument($layout);

    $els = $pq [$selector];
    foreach ($els as $elem) {
//  pq($elem)->html($field_content);
        pq($elem)->$action($content);
//
    }
    $layout = $pq->htmlOuter();

    return $layout;
}

function clean_word($html_to_save) {
    if (strstr($html_to_save, '<!--[if gte mso')) {
// word mess up tags
        $tags = extract_tags($html_to_save, 'xml', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8');

        $matches = $tags;
        if (!empty($matches)) {
            foreach ($matches as $m) {
                $html_to_save = str_replace($m ['full_tag'], '', $html_to_save);
            }

            $html_to_save = str_replace('<!--[if gte mso 8]><![endif]-->', '', $html_to_save);

            $html_to_save = str_replace('<!--[if gte mso 9]><![endif]-->', '', $html_to_save);
            $html_to_save = str_replace('<!--[if gte mso 10]><![endif]-->', '', $html_to_save);
            $html_to_save = str_replace('<!--[if gte mso 11]><![endif]-->', '', $html_to_save);
            $html_to_save = str_replace('class="MsoNormal"', '', $html_to_save);
        }

// $tags = extract_tags ( $html_to_save, 'style', $selfclosing = false,
// $return_the_entire_tag = true, $charset = 'UTF-8' );
//
        // $matches = $tags;
// if (! empty ( $matches )) {
// foreach ( $matches as $m ) {
// $html_to_save = str_replace ( $m ['full_tag'], '', $html_to_save );
// }
// }
    }
    $html_to_save = str_replace('class="exec"', '', $html_to_save);
    $html_to_save = str_replace('style=""', '', $html_to_save);

    $html_to_save = str_replace('ui-draggable', '', $html_to_save);
    $html_to_save = str_replace('class="ui-droppable"', '', $html_to_save);
    $html_to_save = str_replace('ui-droppable', '', $html_to_save);
    $html_to_save = str_replace('mw_edited', '', $html_to_save);
    $html_to_save = str_replace('_moz_dirty=""', '', $html_to_save);
    $html_to_save = str_replace('ui-droppable', '', $html_to_save);
    $html_to_save = str_replace('<br >', '<br />', $html_to_save);
    $html_to_save = str_replace('<br>', '<br />', $html_to_save);
    $html_to_save = str_replace(' class=""', '', $html_to_save);
    $html_to_save = str_replace(' class=" "', '', $html_to_save);
// $html_to_save = str_replace ( '<br><br>', '<div><br><br></div>',
// $html_to_save );
// $html_to_save = str_replace ( '<br /><br />', '<div><br /><br /></div>',
// $html_to_save );
    $html_to_save = preg_replace('/<!--(.*)-->/Uis', '', $html_to_save);
// $html_to_save = '<p>' . str_replace("<br />","<br />", str_replace("<br
// /><br />", "</p><p>", $html_to_save)) . '</p>';
// $html_to_save = str_replace(array("<p></p>", "<p><h2>", "<p><h1>",
// "<p><div", "</pre></p>", "<p><pre>", "</p></p>", "<p></td>", "<p><p",
// "<p><table", "<p><p", "<p><table"), array("<p>&nbsp;</p>", "<h2>",
// "<h1>", "<div", "</pre>", "<pre>", "</p>", "</td>", "<p", "<table", "<p",
// "<table"), $html_to_save);
// p($html_to_save);
    return $html_to_save;
}
