<?php
/**
 * Subsys_JsHttpRequest_Php: PHP backend for JavaScript DHTML loader.
 * (C) 2005 Dmitry Koterov, http://forum.dklab.ru/users/DmitryKoterov/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html
 *
 * Do not remove this comment if you want to use the script!
 * Не удаляйте данный комментарий, если вы хотите использовать скрипт!
 *
 * This backend library also supports POST requests additionally to GET.
 *
 * @author Dmitry Koterov 
 * @version 3.25
 */

class Subsys_JsHttpRequest_Php
{
    var $SCRIPT_ENCODING = "windows-1251";
    var $SCRIPT_DECODE_MODE = '';
    var $UNIQ_HASH;
    var $SCRIPT_ID;
    var $LOADER = null;
    var $QUOTING = null;

    
    // Constructor.
    function Subsys_JsHttpRequest_Php($enc)
    {
        // QUERY_STRING is in form: PHPSESSID=<sid>&a=aaa&b=bbb&<id>
        // where <id> is request ID, <sid> - session ID (if present), 
        // PHPSESSID - session parameter name (by default = "PHPSESSID").

        // Parse QUERY_STRING wrapper format.
        $this->LOADER = "SCRIPT";
        if (preg_match('/(\d+)((?:-\w+)?)$/s', $_SERVER['QUERY_STRING'], $m)) {
            $this->SCRIPT_ID = $m[1];
            // XMLHttpRequest is used if URI ends with "&".
            if ($m[2] == '-xml') $this->LOADER = "XMLHttpRequest";
        } else {
            $this->SCRIPT_ID = 0;
        }

        // Start OB handling early.
        $this->UNIQ_HASH = md5(microtime().getmypid());
        ini_set('error_prepend_string', ini_get('error_prepend_string').$this->UNIQ_HASH);
        ini_set('error_append_string',  ini_get('error_append_string') .$this->UNIQ_HASH);
        ob_start(array(&$this, "_obHandler"));

        // Set up encoding.
        $this->setEncoding($enc);
    }


    // Set active script encoding & correct QUERY_STRING according to it.
    // Examples:
    //   "windows-1251"          - set plain encoding (non-windows characters, 
    //                             e.g. hieroglyphs, are totally ignored)
    //   "windows-1251 entities" - set windows encoding, BUT additionally replace:
    //                             "&"         ->  "&amp;" 
    //                             hieroglyph  ->  &#XXXX; entity
    function setEncoding($enc)
    {
        // Parse encoding.
        preg_match('/^(\S*)(?:\s+(\S*))$/', $enc, $p);
        $this->SCRIPT_ENCODING    = strtolower(@$p[1]? $p[1] : $enc);
        $this->SCRIPT_DECODE_MODE = @$p[2]? $p[2] : '';
        // Manually parse QUERY_STRING because of damned Unicode's %uXXXX.
        $this->_correctQueryString();
    }

    
    // Quote string according to input decoding mode.
    // If entities is used (see setEncoding()), no '&' character is quoted,
    // only '"', '>' and '<' (we presume than '&' is already quoted by
    // input reader function).
    //
    // Use this function INSTEAD of htmlspecialchars() for $_GET data 
    // in your scripts.
    function quoteInput($s)
    {
        if ($this->SCRIPT_DECODE_MODE == 'entities')
            return str_replace(array('"', '<', '>'), array('&quot;', '&lt;', '&gt;'), $s);
        else
            return htmlspecialchars($s);
    }

    
    // Convert PHP scalar, array or hash to JS scalar/array/hash.
    function _php2js($a)
    {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a)) {
            $a = addslashes($a);
            $a = str_replace("\n", '\n', $a);
            $a = str_replace("\r", '\r', $a);
            return "'$a'";
        }
        $isList = true;
        for ($i=0, reset($a); $i<count($a); $i++, next($a))
            if (key($a) !== $i) { $isList = false; break; }
        $result = array();
        if ($isList) {
            foreach ($a as $v) $result[] = Subsys_JsHttpRequest_Php::_php2js($v);
            return '[ ' . join(',', $result) . ' ]';
        } else {
            foreach ($a as $k=>$v) $result[] = Subsys_JsHttpRequest_Php::_php2js($k) . ': ' . Subsys_JsHttpRequest_Php::_php2js($v);
            return '{ ' . join(',', $result) . ' }';
        }
    }


    // Parse & decode QUERY_STRING.
    function _correctQueryString()
    {
        // ATTENTION!!!
        // HTTP_RAW_POST_DATA is only accessible when Content-Type of POST request
        // is NOT default "application/x-www-form-urlencoded"!!!
        // Library frontend sets "application/octet-stream" for that purpose,
        // see JavaScript code.
        foreach (array('_GET'=>$_SERVER['QUERY_STRING'], '_POST'=>@$GLOBALS['HTTP_RAW_POST_DATA']) as $dst=>$src) {
            if (isset($GLOBALS[$dst])) {
                // First correct all 2-byte entities.
                $s = preg_replace('/%(?!5B)(?!5D)([0-9a-f]{2})/si', '%u00\\1', $src);
                // Now we can use standard parse_str() with no worry!
                parse_str($s, $data);
                $GLOBALS[$dst] = $this->_ucs2EntitiesDecode($data);
            }
        }
        $_REQUEST = 
            (isset($_COOKIE)? $_COOKIE : array()) + 
            (isset($_POST)? $_POST : array()) + 
            (isset($_GET)? $_GET : array());
        if (ini_get('register_globals')) {
            // TODO?
        }
    }


    // Called in case of error too!
    function _obHandler($text)
    {
        // Check for error.
        if (preg_match('{'.$this->UNIQ_HASH.'(.*?)'.$this->UNIQ_HASH.'}sx', $text)) {
            $text = str_replace($this->UNIQ_HASH, '', $text);
            $this->WAS_ERROR = 1;
        }
        // Content-type header.
        // In XMLHttpRRequest mode we must return text/plain - damned stupid Opera 8.0. :(
        Header("Content-type: " . ($this->LOADER=="SCRIPT"? "text/javascript" : "text/plain") . "; charset=" . $this->SCRIPT_ENCODING);
        // Make resulting hash.
        if (!isset($this->RESULT)) $this->RESULT = @$GLOBALS['_RESULT'];
        $result = $this->_php2js($this->RESULT);
        $text = 
            "// BEGIN Subsys_JsHttpRequest_Js\n" .
            "Subsys_JsHttpRequest_Js.dataReady(\n" . 
                "  " . $this->_php2js($this->SCRIPT_ID) . ", // this ID is passed from JavaScript frontend\n" . 
                "  " . $this->_php2js(trim($text)) . ",\n" .
                "  " . $result . "\n" .
            ")\n" .
            "// END Subsys_JsHttpRequest_Js\n" .
        "";
//      $f = fopen("debug", "w"); fwrite($f, $text); fclose($f);
        return $text;
    }


    // Decode all %uXXXX entities in string or array (recurrent).
    // String must not contain %XX entities - they are ignored!
    function _ucs2EntitiesDecode($data)
    {
        if (is_array($data)) {
            $d = array();
            foreach ($data as $k=>$v) {
                $d[$this->_ucs2EntitiesDecode($k)] = $this->_ucs2EntitiesDecode($v);
            }
            return $d;
        } else {
            if (strpos($data, '%u') !== false) { // improve speed
                $data = preg_replace_callback('/%u([0-9A-F]{1,4})/si', array(&$this, '_ucs2EntitiesDecodeCallback'), $data);
            }
            return $data;
        }
    }

    // Decode one %uXXXX entity (RE callback).
    function _ucs2EntitiesDecodeCallback($p)
    {
        $hex = $p[1];
        $dec = hexdec($hex);
        if ($dec === "38" && $this->SCRIPT_DECODE_MODE == 'entities') {
            // Process "&" separately in "entities" decode mode.
            $c = "&amp;";
        } else {
            if (is_callable('iconv')) {
                $c = @iconv('UCS-2BE', $this->SCRIPT_ENCODING, pack('n', $dec));
            } else {
                $c = $this->_decUcs2Decode($dec, $this->SCRIPT_ENCODING);
            }
            if (!strlen($c)) {
                if ($this->SCRIPT_DECODE_MODE == 'entities') {
                    $c = '&#'.$dec.';';
                } else {
                    $c = '?';
                }
            }
        }
        return $c;
    }


    /**
     * If there is no ICONV, try to decode 1-byte characters manually
     * (for most popular charsets only).
     */

    // Convert from UCS-2BE decimal to $toEnc.
    function _decUcs2Decode($code, $toEnc)
    {
        if ($code < 128) return chr($code);
        if (isset($this->_encTables[$toEnc])) {
            $p = array_search($code, $this->_encTables[$toEnc]);
            if ($p !== false) return chr(128 + $p);
        }
        return "?";
    }

    // UCS-2BE -> 1-byte encodings (from #128).
    var $_encTables = array(
        'windows-1251' => array(
            0x0402, 0x0403, 0x201A, 0x0453, 0x201E, 0x2026, 0x2020, 0x2021,
            0x20AC, 0x2030, 0x0409, 0x2039, 0x040A, 0x040C, 0x040B, 0x040F,
            0x0452, 0x2018, 0x2019, 0x201C, 0x201D, 0x2022, 0x2013, 0x2014,
            0x0098, 0x2122, 0x0459, 0x203A, 0x045A, 0x045C, 0x045B, 0x045F,
            0x00A0, 0x040E, 0x045E, 0x0408, 0x00A4, 0x0490, 0x00A6, 0x00A7,
            0x0401, 0x00A9, 0x0404, 0x00AB, 0x00AC, 0x00AD, 0x00AE, 0x0407,
            0x00B0, 0x00B1, 0x0406, 0x0456, 0x0491, 0x00B5, 0x00B6, 0x00B7,
            0x0451, 0x2116, 0x0454, 0x00BB, 0x0458, 0x0405, 0x0455, 0x0457,
            0x0410, 0x0411, 0x0412, 0x0413, 0x0414, 0x0415, 0x0416, 0x0417,
            0x0418, 0x0419, 0x041A, 0x041B, 0x041C, 0x041D, 0x041E, 0x041F,
            0x0420, 0x0421, 0x0422, 0x0423, 0x0424, 0x0425, 0x0426, 0x0427,
            0x0428, 0x0429, 0x042A, 0x042B, 0x042C, 0x042D, 0x042E, 0x042F,
            0x0430, 0x0431, 0x0432, 0x0433, 0x0434, 0x0435, 0x0436, 0x0437,
            0x0438, 0x0439, 0x043A, 0x043B, 0x043C, 0x043D, 0x043E, 0x043F,
            0x0440, 0x0441, 0x0442, 0x0443, 0x0444, 0x0445, 0x0446, 0x0447,
            0x0448, 0x0449, 0x044A, 0x044B, 0x044C, 0x044D, 0x044E, 0x044F,
        ),
        'koi8-r' => array(
            0x2500, 0x2502, 0x250C, 0x2510, 0x2514, 0x2518, 0x251C, 0x2524,
            0x252C, 0x2534, 0x253C, 0x2580, 0x2584, 0x2588, 0x258C, 0x2590,
            0x2591, 0x2592, 0x2593, 0x2320, 0x25A0, 0x2219, 0x221A, 0x2248,
            0x2264, 0x2265, 0x00A0, 0x2321, 0x00B0, 0x00B2, 0x00B7, 0x00F7,
            0x2550, 0x2551, 0x2552, 0x0451, 0x2553, 0x2554, 0x2555, 0x2556,
            0x2557, 0x2558, 0x2559, 0x255A, 0x255B, 0x255C, 0x255d, 0x255E,
            0x255F, 0x2560, 0x2561, 0x0401, 0x2562, 0x2563, 0x2564, 0x2565,
            0x2566, 0x2567, 0x2568, 0x2569, 0x256A, 0x256B, 0x256C, 0x00A9,
            0x044E, 0x0430, 0x0431, 0x0446, 0x0434, 0x0435, 0x0444, 0x0433,
            0x0445, 0x0438, 0x0439, 0x043A, 0x043B, 0x043C, 0x043d, 0x043E,
            0x043F, 0x044F, 0x0440, 0x0441, 0x0442, 0x0443, 0x0436, 0x0432,
            0x044C, 0x044B, 0x0437, 0x0448, 0x044d, 0x0449, 0x0447, 0x044A,
            0x042E, 0x0410, 0x0411, 0x0426, 0x0414, 0x0415, 0x0424, 0x0413,
            0x0425, 0x0418, 0x0419, 0x041A, 0x041B, 0x041C, 0x041d, 0x041E,
            0x041F, 0x042F, 0x0420, 0x0421, 0x0422, 0x0423, 0x0416, 0x0412,
            0x042C, 0x042B, 0x0417, 0x0428, 0x042d, 0x0429, 0x0427, 0x042A      
        ),
    );
}
?>