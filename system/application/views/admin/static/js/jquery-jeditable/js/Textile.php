<?php

/**
 * Example: get XHTML from a given Textile-markup string ($string)
 *
 *        $textile = new Textile;
 *        echo $textile->TextileThis($string);
 *
 */

/*
$HeadURL: http://svn.textpattern.com/development/crockery/textpattern/lib/classTextile.php $
$LastChangedRevision: 13 $
*/

/*

_____________
T E X T I L E

A Humane Web Text Generator

Version 2.0 beta

Copyright (c) 2003-2004, Dean Allen <dean@textism.com>
All rights reserved.

Thanks to Carlo Zottmann <carlo@g-blog.net> for refactoring 
Textile's procedural code into a class framework

_____________
L I C E N S E

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice,
  this list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name Textile nor the names of its contributors may be used to
  endorse or promote products derived from this software without specific
  prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
POSSIBILITY OF SUCH DAMAGE.

_________
U S A G E

Block modifier syntax:

	Header: h(1-6).
	Paragraphs beginning with 'hn. ' (where n is 1-6) are wrapped in header tags.
	Example: h1. Header... -> <h1>Header...</h1>

	Paragraph: p. (also applied by default)
	Example: p. Text -> <p>Text</p>

	Blockquote: bq.
	Example: bq. Block quotation... -> <blockquote>Block quotation...</blockquote>

	Blockquote with citation: bq.:http://citation.url
	Example: bq.:http://textism.com/ Text...
	->	<blockquote cite="http://textism.com">Text...</blockquote>

	Footnote: fn(1-100).
	Example: fn1. Footnote... -> <p id="fn1">Footnote...</p>

	Numeric list: #, ##
	Consecutive paragraphs beginning with # are wrapped in ordered list tags.
	Example: <ol><li>ordered list</li></ol>

	Bulleted list: *, **
	Consecutive paragraphs beginning with * are wrapped in unordered list tags.
	Example: <ul><li>unordered list</li></ul>

Phrase modifier syntax:

           _emphasis_   ->   <em>emphasis</em>
           __italic__   ->   <i>italic</i>
             *strong*   ->   <strong>strong</strong>
             **bold**   ->   <b>bold</b>
         ??citation??   ->   <cite>citation</cite>
       -deleted text-   ->   <del>deleted</del>
      +inserted text+   ->   <ins>inserted</ins>
        ^superscript^   ->   <sup>superscript</sup>
          ~subscript~   ->   <sub>subscript</sub>
               @code@   ->   <code>computer code</code>
          %(bob)span%   ->   <span class="bob">span</span>

        ==notextile==   ->   leave text alone (do not format)

       "linktext":url   ->   <a href="url">linktext</a>
 "linktext(title)":url  ->   <a href="url" title="title">linktext</a>

           !imageurl!   ->   <img src="imageurl" />
  !imageurl(alt text)!  ->   <img src="imageurl" alt="alt text" />
    !imageurl!:linkurl  ->   <a href="linkurl"><img src="imageurl" /></a>

ABC(Always Be Closing)  ->   <acronym title="Always Be Closing">ABC</acronym>


Table syntax:

	Simple tables:

        |a|simple|table|row|
        |And|Another|table|row|

        |_. A|_. table|_. header|_.row|
        |A|simple|table|row|

    Tables with attributes:

        table{border:1px solid black}.
        {background:#ddd;color:red}. |{}| | | |


Applying Attributes:

    Most anywhere Textile code is used, attributes such as arbitrary css style,
    css classes, and ids can be applied. The syntax is fairly consistent.

    The following characters quickly alter the alignment of block elements:

        <  ->  left align    ex. p<. left-aligned para
        >  ->  right align       h3>. right-aligned header 3
        =  ->  centred           h4=. centred header 4
        <> ->  justified         p<>. justified paragraph

    These will change vertical alignment in table cells:

        ^  ->  top         ex. |^. top-aligned table cell|
        -  ->  middle          |-. middle aligned|
        ~  ->  bottom          |~. bottom aligned cell|

    Plain (parentheses) inserted between block syntax and the closing dot-space
    indicate classes and ids:

        p(hector). paragraph -> <p class="hector">paragraph</p>

        p(#fluid). paragraph -> <p id="fluid">paragraph</p>

        (classes and ids can be combined)
        p(hector#fluid). paragraph -> <p class="hector" id="fluid">paragraph</p>

    Curly {brackets} insert arbitrary css style

        p{line-height:18px}. paragraph -> <p style="line-height:18px">paragraph</p>

        h3{color:red}. header 3 -> <h3 style="color:red">header 3</h3>

    Square [brackets] insert language attributes

        p[no]. paragraph -> <p lang="no">paragraph</p>

        %[fr]phrase% -> <span lang="fr">phrase</span>

    Usually Textile block element syntax requires a dot and space before the block
    begins, but since lists don't, they can be styled just using braces

        #{color:blue} one  ->  <ol style="color:blue">
        # big                   <li>one</li>
        # list                  <li>big</li>
                                <li>list</li>
                               </ol>

	Using the span tag to style a phrase

        It goes like this, %{color:red}the fourth the fifth%
              -> It goes like this, <span style="color:red">the fourth the fifth</span>

*/

class Textile
{
    var $hlgn;
    var $vlgn;
    var $clas;
    var $lnge;
    var $styl;
    var $cspn;
    var $rspn;
    var $a;
    var $s;
    var $c;
    var $pnct;
    var $rel;
    var $fn;

// -------------------------------------------------------------
    function Textile()
    {
        $this->hlgn = "(?:\<(?!>)|(?<!<)\>|\<\>|\=|[()]+)";
        $this->vlgn = "[\-^~]";
        $this->clas = "(?:\([^)]+\))";
        $this->lnge = "(?:\[[^]]+\])";
        $this->styl = "(?:\{[^}]+\})";
        $this->cspn = "(?:\\\\\d+)";
        $this->rspn = "(?:\/\d+)";
        $this->a = "(?:{$this->hlgn}?{$this->vlgn}?|{$this->vlgn}?{$this->hlgn}?)";
        $this->s = "(?:{$this->cspn}?{$this->rspn}?|{$this->rspn}?{$this->cspn}?)";
        $this->c = "(?:{$this->clas}?{$this->styl}?{$this->lnge}?|{$this->styl}?{$this->lnge}?{$this->clas}?|{$this->lnge}?{$this->styl}?{$this->clas}?)";
        $this->pnct = '[\!"#\$%&\'()\*\+,\-\./:;<=>\?@\[\\\]\^_`{\|}\~]';

    }

// -------------------------------------------------------------
    function TextileThis($text, $lite='', $encode='', $noimage='', $strict='', $rel='')
    {
        if ($rel)
           $this->rel = ' rel="'.$rel.'" ';

        $text = $this->incomingEntities($text);
        
        if ($encode) {
			$text = str_replace("x%x%", "&#38;", $text);
        	return $text;
        } else {
        
	    	if(!$strict) {
				$text = $this->fixEntities($text);
				$text = $this->cleanWhiteSpace($text);
			}
	
			$text = $this->getRefs($text);
	
			$text = $this->noTextile($text);
			$text = $this->links($text);
			if (!$noimage) {
				$text = $this->image($text);
			}
			$text = $this->code($text);
			$text = $this->span($text);
			$text = $this->superscript($text);
			$text = $this->footnoteRef($text);
			$text = $this->glyphs($text);
			$text = $this->retrieve($text);
	
			if (!$lite) {
				$text = $this->lists($text);
				$text = $this->table($text);
				$text = $this->block($text);
			}

				// clean up <notextile>
			$text = preg_replace('/<\/?notextile>/', "", $text);
	
				// turn the temp char back to an ampersand entity
			$text = str_replace("x%x%", "&#38;", $text);
	
				// just to be tidy
			$text = str_replace("<br />", "<br />\n", $text);
	
			return $text;
      	}
    }

// -------------------------------------------------------------
    function pba($in, $element = "") // "parse block attributes"
    {
        $style = '';
        $class = '';
        $lang = '';
        $colspan = '';
        $rowspan = '';
        $id = '';
        $atts = '';

        if (!empty($in)) {
            $matched = $in;
            if ($element == 'td') {
                if (preg_match("/\\\\(\d+)/", $matched, $csp)) $colspan = $csp[1];
                if (preg_match("/\/(\d+)/", $matched, $rsp)) $rowspan = $rsp[1];

                if (preg_match("/($this->vlgn)/", $matched, $vert))
                    $style[] = "vertical-align:" . $this->vAlign($vert[1]) . ";";
            }

            if (preg_match("/\{([^}]*)\}/", $matched, $sty)) {
                $style[] = $sty[1] . ';';
                $matched = str_replace($sty[0], '', $matched);
            }

            if (preg_match("/\[([^)]+)\]/U", $matched, $lng)) {
                $lang = $lng[1];
                $matched = str_replace($lng[0], '', $matched);
            }

            if (preg_match("/\(([^()]+)\)/U", $matched, $cls)) {
                $class = $cls[1];
                $matched = str_replace($cls[0], '', $matched);
            }

            if (preg_match("/([(]+)/", $matched, $pl)) {
                $style[] = "padding-left:" . strlen($pl[1]) . "em;";
                $matched = str_replace($pl[0], '', $matched);
            }

            if (preg_match("/([)]+)/", $matched, $pr)) {
                // $this->dump($pr);
                $style[] = "padding-right:" . strlen($pr[1]) . "em;";
                $matched = str_replace($pr[0], '', $matched);
            }

            if (preg_match("/($this->hlgn)/", $matched, $horiz))
                $style[] = "text-align:" . $this->hAlign($horiz[1]) . ";";

            if (preg_match("/^(.*)#(.*)$/", $class, $ids)) {
                $id = $ids[2];
                $class = $ids[1];
            }

            return join('',array(
                ($style)   ? ' style="'   . join("", $style) .'"':'',
                ($class)   ? ' class="'   . $class           .'"':'',
                ($lang)    ? ' lang="'    . $lang            .'"':'',
                ($id)      ? ' id="'      . $id              .'"':'',
                ($colspan) ? ' colspan="' . $colspan         .'"':'',
                ($rowspan) ? ' rowspan="' . $rowspan         .'"':''
            ));
        }
        return '';
    }

// -------------------------------------------------------------
    function table($text)
    {
        $text = $text . "\n\n";
        return preg_replace_callback("/^(?:table(_?{$this->s}{$this->a}{$this->c})\. ?\n)?^({$this->a}{$this->c}\.? ?\|.*\|)\n\n/smU", 
           array(&$this, "fTable"), $text);
    }

// -------------------------------------------------------------
    function fTable($matches)
    {
        $tatts = $this->pba($matches[1], 'table');

        foreach(preg_split("/\|$/m", $matches[2], -1, PREG_SPLIT_NO_EMPTY) as $row) {
            if (preg_match("/^($this->a$this->c\. )(.*)/m", $row, $rmtch)) {
                $ratts = $this->pba($rmtch[1], 'tr');
                $row = $rmtch[2];
            } else $ratts = '';

            foreach(explode("|", $row) as $cell) {
                $ctyp = "d";
                if (preg_match("/^_/", $cell)) $ctyp = "h";
                if (preg_match("/^(_?$this->s$this->a$this->c\. )(.*)/", $cell, $cmtch)) {
                    $catts = $this->pba($cmtch[1], 'td');
                    $cell = $cmtch[2];
                } else $catts = '';

                if (trim($cell) != '')
                    $cells[] = "\t\t\t<t$ctyp$catts>$cell</t$ctyp>";
            }
            $rows[] = "\t\t<tr$ratts>\n" . join("\n", $cells) . "\n\t\t</tr>";
            unset($cells, $catts);
        }
        return "\t<table$tatts>\n" . join("\n", $rows) . "\n\t</table>\n\n";
    }

// -------------------------------------------------------------
    function lists($text)
    {
        return preg_replace_callback("/^([#*]+$this->c .*)$(?![^#*])/smU", array(&$this, "fList"), $text);
    }

// -------------------------------------------------------------
    function fList($m)
    {
        $text = explode("\n", $m[0]);
        foreach($text as $line) {
            $nextline = next($text);
            if (preg_match("/^([#*]+)($this->a$this->c) (.*)$/s", $line, $m)) {
                list(, $tl, $atts, $content) = $m;
                $nl = preg_replace("/^([#*]+)\s.*/", "$1", $nextline);
                if (!isset($lists[$tl])) {
                    $lists[$tl] = true;
                    $atts = $this->pba($atts);
                    $line = "\t<" . $this->lT($tl) . "l$atts>\n\t\t<li>" . $content;
                } else {
                    $line = "\t\t<li>" . $content;
                }

                if(strlen($nl) <= strlen($tl)) $line .= "</li>";
                foreach(array_reverse($lists) as $k => $v) {
                    if(strlen($k) > strlen($nl)) {
                        $line .= "\n\t</" . $this->lT($k) . "l>";
                        if(strlen($k) > 1) 
                            $line .= "</li>";
                        unset($lists[$k]);
                    }
                }
            }
            $out[] = $line;
        }
        return join("\n", $out);
    }

// -------------------------------------------------------------
    function lT($in)
    {
        return preg_match("/^#+/", $in) ? 'o' : 'u';
    }

// -------------------------------------------------------------
    function block($text)
    {
        $pre = $php = $txp = false;
        $find = array('bq', 'h[1-6]', 'fn\d+', 'p');

        $text = preg_replace("/(.+)\n(?![#*\s|])/",
            "$1<br />", $text);

        $text = explode("\n", $text);
        array_push($text, " ");

        foreach($text as $line) {
            if (preg_match('/<pre>/i', $line)) {
                $pre = true;
            }
            elseif (preg_match('/<txp:php>/i', $line)) {
                $php = true;
            }
            elseif (preg_match('/^\s*<txp:/i', $line)) {
                $txp = true;
            }


            foreach($find as $tag) {
                $line = ($pre == false and $php == false and $txp == false)
                ? preg_replace_callback("/^($tag)($this->a$this->c)\.(?::(\S+))? (.*)$/",
                    array(&$this, "fBlock"), $line)
                : $line;
            }

            $line = (!$php and !$txp) ? preg_replace('/^(?!\t|<\/?pre|<\/?code|$| )(.*)/', "\t<p>$1</p>", $line) : $line;

            $line = ($pre or $php) ? str_replace("<br />", "\n", $line):$line;
            if (preg_match('/<\/pre>/i', $line)) {
                $pre = false;
            }
            elseif (preg_match('/<\/txp:php>/i', $line)) {
		$php = false;
            }
			if ($txp == true) $txp = false;
            $out[] = $line;
        }
        return join("\n", $out);
    }

// -------------------------------------------------------------
    function fBlock($m)
    {
        // $this->dump($m);
        list(, $tag, $atts, $cite, $content) = $m;

        $atts = $this->pba($atts);

        if (preg_match("/fn(\d+)/", $tag, $fns)) {
            $tag = 'p';
            $fnid = empty($this->fn[$fns[1]]) ? $fns[1] : $this->fn[$fns[1]];
            $atts .= ' id="fn' . $fnid . '"';
            $content = '<sup>' . $fns[1] . '</sup> ' . $content;
        }

        $start = "\t<$tag";
        $end = "</$tag>";

        if ($tag == "bq") {
            $cite = $this->checkRefs($cite);
            $cite = ($cite != '') ? ' cite="' . $cite . '"' : '';
            $start = "\t<blockquote$cite>\n\t\t<p";
            $end = "</p>\n\t</blockquote>";
        }

        return "$start$atts>$content$end";
    }

// -------------------------------------------------------------
    function span($text)
    {
        $qtags = array('\*\*','\*','\?\?','-','__','_','%','\+','~');

        foreach($qtags as $f) {
            $text = preg_replace_callback("/
                (?<=^|\s|[[:punct:]]|[{([])
                ($f)
                ($this->c)
                (?::(\S+))?
                ([\w<&].*)
                ([[:punct:];]*)
                $f
                (?=[])}]|[[:punct:]]+|\s|$)
            /xmU", array(&$this, "fSpan"), $text);
        }
        return $text;
    }

// -------------------------------------------------------------
    function fSpan($m)
    {
        $qtags = array(
        	'*'  => 'strong',
            '**' => 'b',
            '??' => 'cite',
            '_'  => 'em',
            '__' => 'i',
            '-'  => 'del',
            '%'  => 'span',
            '+'  => 'ins',
            '~'  => 'sub'
        );

        list(, $tag, $atts, $cite, $content, $end) = $m;
        $tag = $qtags[$tag];
        $atts = $this->pba($atts);
        $atts .= ($cite != '') ? 'cite="' . $cite . '"' : '';

        $out = "<$tag$atts>$content$end</$tag>";

//		$this->dump($out);

        return $out;
    
    }

// -------------------------------------------------------------
    function links($text)
    {
        return preg_replace_callback('/
            ([\s[{(]|[[:punct:]])?       # $pre
            "                            # start
            (' . $this->c . ')           # $atts
            ([^"]+)                      # $text
            \s?
            (?:\(([^)]+)\)(?="))?        # $title
            ":
            (\S+\b)                      # $url
            (\/)?                        # $slash
            ([^\w\/;]*)                  # $post
            (?=\s|$)
        /Ux', array(&$this, "fLink"), $text);
    }

// -------------------------------------------------------------
    function fLink($m)
    {
        list(, $pre, $atts, $text, $title, $url, $slash, $post) = $m;

        $url = $this->checkRefs($url);

        $atts = $this->pba($atts);
        $atts .= ($title != '') ? 'title="' . $title . '"' : '';

        $atts = ($atts) ? $this->shelve($atts) : '';

        $parts = parse_url($url);
        if (empty($parts['host']) and preg_match('/^\w/', @$parts['path']))
            $url = hu.$url;

        $out = $pre . '<a href="' . $url . $slash . '"' . $atts . $this->rel . '>' . $text . '</a>' . $post;

		// $this->dump($out);
		return $out;

    }

// -------------------------------------------------------------
    function getRefs($text)
    {
        return preg_replace_callback("/(?<=^|\s)\[(.+)\]((?:http:\/\/|\/)\S+)(?=\s|$)/U",
            array(&$this, "refs"), $text);
    }
    // -------------------------------------------------------------

function refs($m)
    {
        list(, $flag, $url) = $m;
        $this->urlrefs[$flag] = $url;
        return '';
    }

// -------------------------------------------------------------
    function checkRefs($text)
    {
        return (isset($this->urlrefs[$text])) ? $this->urlrefs[$text] : $text;
    }

// -------------------------------------------------------------
    function image($text)
    {
        return preg_replace_callback("/
            \!                 # opening !
            (\<|\=|\>)?        # optional alignment atts
            ($this->c)         # optional style,class atts
            (?:\. )?           # optional dot-space
            ([^\s(!]+)         # presume this is the src
            \s?                # optional space
            (?:\(([^\)]+)\))?  # optional title
            \!                 # closing
            (?::(\S+))?        # optional href
            (?=\s|$)           # lookahead: space or end of string
        /Ux", array(&$this, "fImage"), $text);
    }

// -------------------------------------------------------------
    function fImage($m)
    {
        list(, $algn, $atts, $url) = $m;
        $atts  = $this->pba($atts);
        $atts .= ($algn != '')  ? ' align="' . $this->iAlign($algn) . '"' : '';
        $atts .= (isset($m[4])) ? ' title="' . $m[4] . '"' : '';
        $atts .= (isset($m[4])) ? ' alt="'   . $m[4] . '"' : ' alt=""';
        $size = @getimagesize($url);
        if ($size) $atts .= " $size[3]";

        $href = (isset($m[5])) ? $this->checkRefs($m[5]) : '';
        $url = $this->checkRefs($url);

        $parts = parse_url($url);
        if (empty($parts['host']) and preg_match('/^\w/', @$parts['path']))
            $url = hu.$url;

        $out = array(
            ($href) ? '<a href="' . $href . '">' : '',
            '<img src="' . $url . '"' . $atts . ' />',
            ($href) ? '</a>' : ''
        );

        return join('',$out);
    }

// -------------------------------------------------------------
    function code($text)
    {
        return preg_replace_callback("/
            (?:^|(?<=[\s\(])|([[{]))        # before
            @                               
            (?:\|(\w+)\|)?                  # lang
            (.+)                            # code
            @                               
            (?:$|([\]}])|
            (?=[[:punct:]]{1,2}|
            \s|$))                           # after
        /Ux", array(&$this, "fCode"), $text);
    }

// -------------------------------------------------------------
    function fCode($m)
    {
        @list(, $before, $lang, $code, $after) = $m;
        $lang = ($lang) ? ' language="' . $lang . '"' : '';
        return $before . '<code' . $lang . '>' . $code . '</code>' . $after;
    }

// -------------------------------------------------------------
    function shelve($val)
    {
        $this->shelf[] = $val;
        return ' <' . count($this->shelf) . '>';
    }

// -------------------------------------------------------------
    function retrieve($text)
    {
        $i = 0;
        if (isset($this->shelf) && is_array($this->shelf)) {
            foreach($this->shelf as $r) {
                $i++;
                $text = str_replace("<$i>", $r, $text);
            }
        }
        return $text;
    }

// -------------------------------------------------------------
    function incomingEntities($text)
    {
        return preg_replace("/&(?![#a-z0-9]+;)/i", "x%x%", $text);
    }

// -------------------------------------------------------------
    function encodeEntities($text)
    {
        return (function_exists('mb_encode_numericentity'))
        ?    $this->encode_high($text)
        :    htmlentities($text, ENT_NOQUOTES, "utf-8");
    }

// -------------------------------------------------------------
    function fixEntities($text)
    {
        /*  de-entify any remaining angle brackets or ampersands */
        return str_replace(array("&gt;", "&lt;", "&amp;"),
            array(">", "<", "&"), $text);
    }

// -------------------------------------------------------------
    function cleanWhiteSpace($text)
    {
        $out = str_replace(array("\r\n", "\t"), array("\n", ''), $text);
        $out = preg_replace("/\n{3,}/", "\n\n", $out);
        $out = preg_replace("/\n *\n/", "\n\n", $out);
        $out = preg_replace('/"$/', "\" ", $out);
        return $out;
    }

// -------------------------------------------------------------
    function noTextile($text)
    {
        $text = preg_replace_callback('/(^|\s)<notextile>(.*)<\/notextile>(\s|$)?/msU', 
            array(&$this, "fTextile"), $text);
        return preg_replace_callback('/(^|\s)==(.*)==(\s|$)?/msU', 
            array(&$this, "fTextile"), $text);
    } 

// -------------------------------------------------------------
    function fTextile($m)
    {
        $modifiers = array(     
            '"' => '&#34;',
            '%' => '&#37;',
            '*' => '&#42;',
            '+' => '&#43;',
            '-' => '&#45;',
            '<' => '&#60;',
            '=' => '&#61;',
            '>' => '&#62;',
            '?' => '&#63;',     
            '^' => '&#94;',
            '_' => '&#95;',
            '~' => '&#126;',        
            );
        
        @list(, $before, $notextile, $after) = $m;
        $notextile = str_replace(array_keys($modifiers), array_values($modifiers), $notextile);
        return $before . '<notextile>' . $notextile . '</notextile>' . $after;
    }

// -------------------------------------------------------------
    function superscript($text)
    {
        return preg_replace('/\^(.*)\^/mU', '<sup>$1</sup>', $text);
    }

// -------------------------------------------------------------
    function footnoteRef($text)
    {
        return preg_replace('/\b\[([0-9]+)\](\s)?/Ue',
            '$this->footnoteID(\'\1\',\'\2\')', $text);
    }

// -------------------------------------------------------------
    function footnoteID($id, $t)
    {
        if (empty($this->fn[$id]))
            $this->fn[$id] = uniqid(rand());
        $fnid = $this->fn[$id];
        return '<sup><a href="#fn'.$fnid.'">'.$id.'</a></sup>'.$t;
    }

// -------------------------------------------------------------
    function glyphs($text)
    {
        // fix: hackish
        $text = preg_replace('/"\z/', "\" ", $text);
		$pnc = '[[:punct:]]';

        $glyph_search = array(
            '/([^\s[{(>_*])?\'(?(1)|(?=\s|s\b|'.$pnc.'))/',      //  single closing
            '/\'/',                                              //  single opening
            '/([^\s[{(>_*])?"(?(1)|(?=\s|'.$pnc.'))/',           //  double closing
            '/"/',                                               //  double opening
            '/\b( )?\.{3}/',                                     //  ellipsis
            '/\b([A-Z][A-Z0-9]{2,})\b(?:[(]([^)]*)[)])/',        //  3+ uppercase acronym
            '/\s?--\s?/',                                        //  em dash
            '/\s-\s/',                                           //  en dash
            '/(\d+) ?x ?(\d+)/',                                 //  dimension sign
            '/\b ?[([]TM[])]/i',                                 //  trademark
            '/\b ?[([]R[])]/i',                                  //  registered
            '/\b ?[([]C[])]/i');                                 //  copyright

        $glyph_replace = array('$1&#8217;$2',   //  single closing
            '&#8216;',                          //  single opening
            '$1&#8221;',                        //  double closing
            '&#8220;',                          //  double opening
            '$1&#8230;',                        //  ellipsis
            '<acronym title="$2">$1</acronym>', //  3+ uppercase acronym
            '&#8212;',                          //  em dash
            ' &#8211; ',                        //  en dash
            '$1&#215;$2',                       //  dimension sign
            '&#8482;',                          //  trademark
            '&#174;',                           //  registered
            '&#169;');                          //  copyright

        $codepre = false;
        /*  if no html, do a simple search and replace... */
        if (!preg_match("/<.*>/", $text)) {
            $text = preg_replace($glyph_search, $glyph_replace, $text);
            return $text;
        }
        else {
            $text = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach($text as $line) {
                $offtags = ('code|pre|kbd|notextile|txp:php');

                /*  matches are off if we're between <code>, <pre> etc. */
                if (preg_match('/<(' . $offtags . ')>/i', $line)) $codepre = true;
                if (preg_match('/<\/(' . $offtags . ')>/i', $line)) $codepre = false;

                if (!preg_match("/<.*>/", $line) && $codepre == false) {
                    $line = preg_replace($glyph_search, $glyph_replace, $line);
                }

                /* do htmlspecial if between <code> */
                if ($codepre == true) {
                    $line = htmlspecialchars($line, ENT_NOQUOTES, "UTF-8");
                    $line = preg_replace('/&lt;(\/?' . $offtags . ')&gt;/', "<$1>", $line);
                    $line = str_replace("&amp;#","&#",$line);
                }

                $glyph_out[] = $line;
            }
            return join('', $glyph_out);
        }
    }

// -------------------------------------------------------------
    function iAlign($in)
    {
        $vals = array(
            '<' => 'left',
            '=' => 'center',
            '>' => 'right');
        return (isset($vals[$in])) ? $vals[$in] : '';
    }

// -------------------------------------------------------------
    function hAlign($in)
    {
        $vals = array(
            '<'  => 'left',
            '='  => 'center',
            '>'  => 'right',
            '<>' => 'justify');
        return (isset($vals[$in])) ? $vals[$in] : '';
    }

// -------------------------------------------------------------
    function vAlign($in)
    {
        $vals = array(
            '^' => 'top',
            '-' => 'middle',
            '~' => 'bottom');
        return (isset($vals[$in])) ? $vals[$in] : '';
    }

// -------------------------------------------------------------
    function encode_high($text, $charset = "UTF-8")
    {
        return mb_encode_numericentity($text, $this->cmap(), $charset);
    }

// -------------------------------------------------------------
    function decode_high($text, $charset = "UTF-8")
    {
        return mb_decode_numericentity($text, $this->cmap(), $charset);
    }

// -------------------------------------------------------------
    function cmap()
    {
        $f = 0xffff;
        $cmap = array(
            0x0080, 0xffff, 0, $f);
        return $cmap;
    }

// -------------------------------------------------------------
    function textile_popup_help($name, $helpvar, $windowW, $windowH)
    {
        return ' <a target="_blank" href="http://www.textpattern.com/help/?item=' . $helpvar . '" onclick="window.open(this.href, \'popupwindow\', \'width=' . $windowW . ',height=' . $windowH . ',scrollbars,resizable\'); return false;">' . $name . '</a><br />';

        return $out;
    }

// -------------------------------------------------------------
    function txtgps($thing)
    {
        if (isset($_POST[$thing])) {
            if (get_magic_quotes_gpc()) {
                return stripslashes($_POST[$thing]);
            }
            else {
                return $_POST[$thing];
            }
        }
        else {
            return '';
        }
    }
    
// -------------------------------------------------------------
    function dump()
    {
		foreach (func_get_args() as $a)
			echo "\n<pre>",(is_array($a)) ? print_r($a) : $a, "</pre>\n";
	}
	
// -------------------------------------------------------------
	function blockLite($text)
    {
        $find = array('bq', 'p');

        $text = preg_replace("/(.+)\n(?![#*\s|])/",
            "$1<br />", $text);

        $text = explode("\n", $text);
        array_push($text, " ");

        foreach($text as $line) {

            foreach($find as $tag) {
                $line = preg_replace_callback("/^($tag)($this->a$this->c)\.(?::(\S+))? (.*)$/",
                    array(&$this, "fBlock"), $line);
            }

            $line = preg_replace('/^(?!\t|<\/?pre|<\/?code|$| )(.*)/', "\t<p>$1</p>", $line);
            $out[] = $line;
        }
        return join("\n", $out);
    }


} // end class

?>
