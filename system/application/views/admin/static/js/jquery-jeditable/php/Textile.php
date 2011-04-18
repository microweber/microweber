<?php

/**
 * Example: get XHTML from a given Textile-markup string ($string)
 *
 *        $textile = new Textile;
 *        echo $textile->TextileThis($string);
 *
 */

/*
$Id: Textile.php,v 1.2 2006/12/11 10:18:59 toni Exp $
$LastChangedRevision: 1998 $
*/

/*

_____________
T E X T I L E

A Humane Web Text Generator

Version 2.0

Copyright (c) 2003-2004, Dean Allen <dean@textism.com>
All rights reserved.

Thanks to Carlo Zottmann <carlo@g-blog.net> for refactoring
Textile's procedural code into a class framework

Additions and fixes Copyright (c) 2006 Alex Shiels http://thresholdstate.com/

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
    ->  <blockquote cite="http://textism.com">Text...</blockquote>

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

// define these before including this file to override the standard glyphs
@define('txt_quote_single_open',  '&#8216;');
@define('txt_quote_single_close', '&#8217;');
@define('txt_quote_double_open',  '&#8220;');
@define('txt_quote_double_close', '&#8221;');
@define('txt_apostrophe',         '&#8217;');
@define('txt_prime',              '&#8242;');
@define('txt_prime_double',       '&#8243;');
@define('txt_ellipsis',           '&#8230;');
@define('txt_emdash',             '&#8212;');
@define('txt_endash',             '&#8211;');
@define('txt_dimension',          '&#215;');
@define('txt_trademark',          '&#8482;');
@define('txt_registered',         '&#174;');
@define('txt_copyright',          '&#169;');

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
    
    var $shelf = array();
    var $restricted = false;
    var $noimage = false;
    var $lite = false;
    var $url_schemes = array();
    var $glyph = array();
    var $hu = '';
    
    var $ver = '2.0.0';
    var $rev = '$Rev: 1998 $';
    
    var $doc_root;

// -------------------------------------------------------------
    function Textile()
    {
        $this->hlgn = "(?:\<(?!>)|(?<!<)\>|\<\>|\=|[()]+(?! ))";
        $this->vlgn = "[\-^~]";
        $this->clas = "(?:\([^)]+\))";
        $this->lnge = "(?:\[[^]]+\])";
        $this->styl = "(?:\{[^}]+\})";
        $this->cspn = "(?:\\\\\d+)";
        $this->rspn = "(?:\/\d+)";
        $this->a = "(?:{$this->hlgn}|{$this->vlgn})*";
        $this->s = "(?:{$this->cspn}|{$this->rspn})*";
        $this->c = "(?:{$this->clas}|{$this->styl}|{$this->lnge}|{$this->hlgn})*";

        $this->pnct = '[\!"#\$%&\'()\*\+,\-\./:;<=>\?@\[\\\]\^_`{\|}\~]';
        $this->urlch = '[\w"$\-_.+!*\'(),";\/?:@=&%#{}|\\^~\[\]`]';

        $this->url_schemes = array('http','https','ftp','mailto');

        $this->btag = array('bq', 'bc', 'notextile', 'pre', 'h[1-6]', 'fn\d+', 'p');

        $this->glyph = array(
           'quote_single_open'  => txt_quote_single_open,
           'quote_single_close' => txt_quote_single_close,
           'quote_double_open'  => txt_quote_double_open,
           'quote_double_close' => txt_quote_double_close,
           'apostrophe'         => txt_apostrophe,
           'prime'              => txt_prime,
           'prime_double'       => txt_prime_double,
           'ellipsis'           => txt_ellipsis,
           'emdash'             => txt_emdash,
           'endash'             => txt_endash,
           'dimension'          => txt_dimension,
           'trademark'          => txt_trademark,
           'registered'         => txt_registered,
           'copyright'          => txt_copyright,
        );

        if (defined('hu'))
            $this->hu = hu;

        if (defined('DIRECTORY_SEPARATOR'))
            $this->ds = constant('DIRECTORY_SEPARATOR');
        else
            $this->ds = '/';

        $this->doc_root = @$_SERVER['DOCUMENT_ROOT'];
        if (!$this->doc_root)
            $this->doc_root = @$_SERVER['PATH_TRANSLATED']; // IIS
            
        $this->doc_root = rtrim($this->doc_root, $this->ds).$this->ds;

    }

// -------------------------------------------------------------

	function TextileThis($text, $lite = '', $encode = '', $noimage = '', $strict = '', $rel = '')
	{
		$this->rel = ($rel) ? ' rel="'.$rel.'"' : '';

		$this->lite = $lite;
		$this->noimage = $noimage;

        if ($encode) {
         $text = $this->incomingEntities($text);
            $text = str_replace("x%x%", "&#38;", $text);
            return $text;
        } else {

            if(!$strict) {
                $text = $this->cleanWhiteSpace($text);
            }

            $text = $this->getRefs($text);

            if (!$lite) {
                $text = $this->block($text);
            }

            $text = $this->retrieve($text);

                // just to be tidy
            $text = str_replace("<br />", "<br />\n", $text);

            return $text;
        }
    }

// -------------------------------------------------------------

	function TextileRestricted($text, $lite = 1, $noimage = 1, $rel = 'nofollow')
	{
		$this->restricted = true;
		$this->lite = $lite;
		$this->noimage = $noimage;

		$this->rel = ($rel) ? ' rel="'.$rel.'"' : '';

            // escape any raw html
            $text = $this->encode_html($text, 0);

            $text = $this->cleanWhiteSpace($text);
            $text = $this->getRefs($text);

            if ($lite) {
                $text = $this->blockLite($text);
            }
            else {
                $text = $this->block($text);
            }

            $text = $this->retrieve($text);

                // just to be tidy
            $text = str_replace("<br />", "<br />\n", $text);

            return $text;
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
            }

            if ($element == 'td' or $element == 'tr') {
                if (preg_match("/($this->vlgn)/", $matched, $vert))
                    $style[] = "vertical-align:" . $this->vAlign($vert[1]) . ";";
            }

            if (preg_match("/\{([^}]*)\}/", $matched, $sty)) {
                $style[] = rtrim($sty[1], ';') . ';';
                $matched = str_replace($sty[0], '', $matched);
            }

            if (preg_match("/\[([^]]+)\]/U", $matched, $lng)) {
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

            if ($this->restricted)
                return ($lang)    ? ' lang="'    . $lang            .'"':'';

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
    function hasRawText($text)
    {
        // checks whether the text has text not already enclosed by a block tag
        $r = trim(preg_replace('@<(p|blockquote|div|form|table|ul|ol|pre|h\d)[^>]*?>.*</\1>@s', '', trim($text)));
        $r = trim(preg_replace('@<(hr|br)[^>]*?/>@', '', $r));
        return '' != $r;
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
            if (preg_match("/^($this->a$this->c\. )(.*)/m", ltrim($row), $rmtch)) {
                $ratts = $this->pba($rmtch[1], 'tr');
                $row = $rmtch[2];
            } else $ratts = '';

                $cells = array();
            foreach(explode("|", $row) as $cell) {
                $ctyp = "d";
                if (preg_match("/^_/", $cell)) $ctyp = "h";
                if (preg_match("/^(_?$this->s$this->a$this->c\. )(.*)/", $cell, $cmtch)) {
                    $catts = $this->pba($cmtch[1], 'td');
                    $cell = $cmtch[2];
                } else $catts = '';

                $cell = $this->graf($this->span($cell));

                if (trim($cell) != '')
                    $cells[] = "\t\t\t<t$ctyp$catts>$cell</t$ctyp>";
            }
            $rows[] = "\t\t<tr$ratts>\n" . join("\n", $cells) . ($cells ? "\n" : "") . "\t\t</tr>";
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
                $nl = '';
                if (preg_match("/^([#*]+)\s.*/", $nextline, $nm))
                	$nl = $nm[1];
                if (!isset($lists[$tl])) {
                    $lists[$tl] = true;
                    $atts = $this->pba($atts);
                    $line = "\t<" . $this->lT($tl) . "l$atts>\n\t\t<li>" . $this->graf($content);
                } else {
                    $line = "\t\t<li>" . $this->graf($content);
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
    function doPBr($in)
    {
        return preg_replace_callback('@<(p)([^>]*?)>(.*)(</\1>)@s', array(&$this, 'doBr'), $in);
    }

// -------------------------------------------------------------
    function doBr($m)
    {
        $content = preg_replace("@(.+)(?<!<br>|<br />)\n(?![#*\s|])@", '$1<br />', $m[3]);
        return '<'.$m[1].$m[2].'>'.$content.$m[4];
    }

// -------------------------------------------------------------
    function block($text)
    {
        $find = $this->btag;
        $tre = join('|', $find);

        $text = explode("\n\n", $text);

        $tag = 'p';
        $atts = $cite = $graf = $ext  = '';

        foreach($text as $line) {
            $anon = 0;
            if (preg_match("/^($tre)($this->a$this->c)\.(\.?)(?::(\S+))? (.*)$/s", $line, $m)) {
                // last block was extended, so close it
                if ($ext)
                    $out[count($out)-1] .= $c1;
                // new block
                list(,$tag,$atts,$ext,$cite,$graf) = $m;
                list($o1, $o2, $content, $c2, $c1) = $this->fBlock(array(0,$tag,$atts,$ext,$cite,$graf));

                // leave off c1 if this block is extended, we'll close it at the start of the next block
                if ($ext)
                    $line = $o1.$o2.$content.$c2;
                else
                    $line = $o1.$o2.$content.$c2.$c1;
            }
            else {
                // anonymous block
                $anon = 1;
                if ($ext or !preg_match('/^ /', $line)) {
                    list($o1, $o2, $content, $c2, $c1) = $this->fBlock(array(0,$tag,$atts,$ext,$cite,$line));
                    // skip $o1/$c1 because this is part of a continuing extended block
                    if ($tag == 'p' and !$this->hasRawText($content)) {
                        $line = $content;
                    }
                    else {
                        $line = $o2.$content.$c2;
                    }
                }
                else {
                   $line = $this->graf($line);
                }
            }

            $line = $this->doPBr($line);
            $line = preg_replace('/<br>/', '<br />', $line);

            if ($ext and $anon)
                $out[count($out)-1] .= "\n".$line;
            else
                $out[] = $line;

            if (!$ext) {
                $tag = 'p';
                $atts = '';
                $cite = '';
                $graf = '';
            }
        }
        if ($ext) $out[count($out)-1] .= $c1;
        return join("\n\n", $out);
    }



// -------------------------------------------------------------
    function fBlock($m)
    {
        // $this->dump($m);
        list(, $tag, $atts, $ext, $cite, $content) = $m;
        $atts = $this->pba($atts);

        $o1 = $o2 = $c2 = $c1 = '';

        if (preg_match("/fn(\d+)/", $tag, $fns)) {
            $tag = 'p';
            $fnid = empty($this->fn[$fns[1]]) ? $fns[1] : $this->fn[$fns[1]];
            $atts .= ' id="fn' . $fnid . '"';
            if (strpos($atts, 'class=') === false)
                $atts .= ' class="footnote"';
            $content = '<sup>' . $fns[1] . '</sup> ' . $content;
        }

        if ($tag == "bq") {
            $cite = $this->checkRefs($cite);
            $cite = ($cite != '') ? ' cite="' . $cite . '"' : '';
            $o1 = "\t<blockquote$cite$atts>\n";
            $o2 = "\t\t<p$atts>";
            $c2 = "</p>";
            $c1 = "\n\t</blockquote>";
        }
        elseif ($tag == 'bc') {
            $o1 = "<pre$atts>";
            $o2 = "<code$atts>";
            $c2 = "</code>";
            $c1 = "</pre>";
            $content = $this->shelve($this->r_encode_html(rtrim($content, "\n")."\n"));
        }
        elseif ($tag == 'notextile') {
            $content = $this->shelve($content);
            $o1 = $o2 = '';
            $c1 = $c2 = '';
        }
        elseif ($tag == 'pre') {
            $content = $this->shelve($this->r_encode_html(rtrim($content, "\n")."\n"));
            $o1 = "<pre$atts>";
            $o2 = $c2 = '';
            $c1 = "</pre>";
        }
        else {
            $o2 = "\t<$tag$atts>";
            $c2 = "</$tag>";
          }

        $content = $this->graf($content);

        return array($o1, $o2, $content, $c2, $c1);
    }

// -------------------------------------------------------------
    function graf($text)
    {
        // handle normal paragraph text
        if (!$this->lite) {
            $text = $this->noTextile($text);
            $text = $this->code($text);
        }

        $text = $this->links($text);
        if (!$this->noimage)
            $text = $this->image($text);

        if (!$this->lite) {
            $text = $this->lists($text);
            $text = $this->table($text);
        }

        $text = $this->span($text);
        $text = $this->footnoteRef($text);
        $text = $this->glyphs($text);
        return rtrim($text, "\n");
    }

// -------------------------------------------------------------
    function span($text)
    {
        $qtags = array('\*\*','\*','\?\?','-','__','_','%','\+','~','\^');
        $pnct = ".,\"'?!;:";

        foreach($qtags as $f) {
            $text = preg_replace_callback("/
                (?:^|(?<=[\s>$pnct\(])|([{[]))
                ($f)(?!$f)
                ({$this->c})
                (?::(\S+))?
                ([^\s$f]+|\S.*?[^\s$f\n])
                ([$pnct]*)
                $f
                (?:$|([\]}])|(?=[[:punct:]]{1,2}|\s|\)))
            /x", array(&$this, "fSpan"), $text);
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
            '~'  => 'sub',
            '^'  => 'sup',
        );

        list(,, $tag, $atts, $cite, $content, $end) = $m;
        $tag = $qtags[$tag];
        $atts = $this->pba($atts);
        $atts .= ($cite != '') ? 'cite="' . $cite . '"' : '';

        $out = "<$tag$atts>$content$end</$tag>";

//      $this->dump($out);

        return $out;

    }

// -------------------------------------------------------------
    function links($text)
    {
        return preg_replace_callback('/
            (?:^|(?<=[\s>.$pnct\(])|([{[])) # $pre
            "                            # start
            (' . $this->c . ')           # $atts
            ([^"]+)                      # $text
            (?:\(([^)]+)\)(?="))?        # $title
            ":
            ('.$this->urlch.'+)          # $url
            (\/)?                        # $slash
            ([^\w\/;]*)                  # $post
            (?:([\]}])|(?=\s|$|\)))
        /Ux', array(&$this, "fLink"), $text);
    }

// -------------------------------------------------------------
    function fLink($m)
    {
        list(, $pre, $atts, $text, $title, $url, $slash, $post) = $m;

        $url = $this->checkRefs($url);

        $atts = $this->pba($atts);
        $atts .= ($title != '') ? ' title="' . $this->encode_html($title) . '"' : '';

        if (!$this->noimage)
            $text = $this->image($text);

        $text = $this->span($text);
        $text = $this->glyphs($text);

        $url = $this->relURL($url);

        $out = '<a href="' . $this->r_encode_html($url . $slash) . '"' . $atts . $this->rel . '>' . trim($text) . '</a>' . $post;

        // $this->dump($out);
        return $this->shelve($out);

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
    function relURL($url)
    {
        $parts = parse_url($url);
        if ((empty($parts['scheme']) or @$parts['scheme'] == 'http') and
             empty($parts['host']) and
             preg_match('/^\w/', @$parts['path']))
            $url = $this->hu.$url;
        if ($this->restricted and !empty($parts['scheme']) and
              !in_array($parts['scheme'], $this->url_schemes))
            return '#';
        return $url;
    }

// -------------------------------------------------------------
    function isRelURL($url)
    {
        $parts = parse_url($url);
        return (empty($parts['scheme']) and empty($parts['host']));
    }

// -------------------------------------------------------------
    function image($text)
    {
        return preg_replace_callback("/
            (?:[[{])?          # pre
            \!                 # opening !
            (\<|\=|\>)?        # optional alignment atts
            ($this->c)         # optional style,class atts
            (?:\. )?           # optional dot-space
            ([^\s(!]+)         # presume this is the src
            \s?                # optional space
            (?:\(([^\)]+)\))?  # optional title
            \!                 # closing
            (?::(\S+))?        # optional href
            (?:[\]}]|(?=\s|$|\))) # lookahead: space or end of string
        /x", array(&$this, "fImage"), $text);
    }

// -------------------------------------------------------------
    function fImage($m)
    {
        list(, $algn, $atts, $url) = $m;
        $atts  = $this->pba($atts);
        $atts .= ($algn != '')  ? ' align="' . $this->iAlign($algn) . '"' : '';
        $atts .= (isset($m[4])) ? ' title="' . $m[4] . '"' : '';
        $atts .= (isset($m[4])) ? ' alt="'   . $m[4] . '"' : ' alt=""';
        $size = false;
        if ($this->isRelUrl($url))
	        $size = @getimagesize(realpath($this->doc_root.ltrim($url, $this->ds)));
        if ($size) $atts .= " $size[3]";

        $href = (isset($m[5])) ? $this->checkRefs($m[5]) : '';
        $url = $this->checkRefs($url);

        $url = $this->relURL($url);

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
        $text = $this->doSpecial($text, '<code>', '</code>', 'fCode');
        $text = $this->doSpecial($text, '@', '@', 'fCode');
        $text = $this->doSpecial($text, '<pre>', '</pre>', 'fPre');
        return $text;
    }

// -------------------------------------------------------------
    function fCode($m)
    {
      @list(, $before, $text, $after) = $m;
      return $before.$this->shelve('<code>'.$this->r_encode_html($text).'</code>').$after;
    }

// -------------------------------------------------------------
    function fPre($m)
    {
      @list(, $before, $text, $after) = $m;
      return $before.'<pre>'.$this->shelve($this->r_encode_html($text)).'</pre>'.$after;
    }
// -------------------------------------------------------------
    function shelve($val)
    {
        $i = uniqid(rand());
        $this->shelf[$i] = $val;
        return $i;
    }

// -------------------------------------------------------------
    function retrieve($text)
    {
        if (is_array($this->shelf))
            do {
                $old = $text;
                $text = strtr($text, $this->shelf);
             } while ($text != $old);

        return $text;
    }

// -------------------------------------------------------------
// NOTE: deprecated
    function incomingEntities($text)
    {
        return preg_replace("/&(?![#a-z0-9]+;)/i", "x%x%", $text);
    }

// -------------------------------------------------------------
// NOTE: deprecated
    function encodeEntities($text)
    {
        return (function_exists('mb_encode_numericentity'))
        ?    $this->encode_high($text)
        :    htmlentities($text, ENT_NOQUOTES, "utf-8");
    }

// -------------------------------------------------------------
// NOTE: deprecated
    function fixEntities($text)
    {
        /*  de-entify any remaining angle brackets or ampersands */
        return str_replace(array("&gt;", "&lt;", "&amp;"),
            array(">", "<", "&"), $text);
    }

// -------------------------------------------------------------
    function cleanWhiteSpace($text)
    {
        $out = str_replace("\r\n", "\n", $text);        # DOS line endings
        $out = preg_replace("/^ *\n/m", "\n", $out);    # lines containing only spaces
        $out = preg_replace("/\n{3,}/", "\n\n", $out);  # 3 or more line ends
        $out = preg_replace("/^\n*/", "", $out);        # leading blank lines
        return $out;
    }

// -------------------------------------------------------------
    function doSpecial($text, $start, $end, $method='fSpecial')
    {
      return preg_replace_callback('/(^|\s|[[({>])'.preg_quote($start, '/').'(.*?)'.preg_quote($end, '/').'(\s|$|[\])}])?/ms',
            array(&$this, $method), $text);
    }

// -------------------------------------------------------------
    function fSpecial($m)
    {
        // A special block like notextile or code
      @list(, $before, $text, $after) = $m;
        return $before.$this->shelve($this->encode_html($text)).$after;
    }

// -------------------------------------------------------------
    function noTextile($text)
    {
         $text = $this->doSpecial($text, '<notextile>', '</notextile>', 'fTextile');
         return $this->doSpecial($text, '==', '==', 'fTextile');

    }

// -------------------------------------------------------------
    function fTextile($m)
    {
        @list(, $before, $notextile, $after) = $m;
        #$notextile = str_replace(array_keys($modifiers), array_values($modifiers), $notextile);
        return $before.$this->shelve($notextile).$after;
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
        return '<sup class="footnote"><a href="#fn'.$fnid.'">'.$id.'</a></sup>'.$t;
    }

// -------------------------------------------------------------
    function glyphs($text)
    {

        // fix: hackish
        $text = preg_replace('/"\z/', "\" ", $text);
        $pnc = '[[:punct:]]';

        $glyph_search = array(
            '/(\w)\'(\w)/',                                      // apostrophe's
            '/(\s)\'(\d+\w?)\b(?!\')/',                          // back in '88
            '/(\S)\'(?=\s|'.$pnc.'|<|$)/',                       //  single closing
            '/\'/',                                              //  single opening
            '/(\S)\"(?=\s|'.$pnc.'|<|$)/',                       //  double closing
            '/"/',                                               //  double opening
            '/\b([A-Z][A-Z0-9]{2,})\b(?:[(]([^)]*)[)])/',        //  3+ uppercase acronym
            '/\b([A-Z][A-Z\'\-]+[A-Z])(?=[\s.,\)>]|$)/',         //  3+ uppercase
            '/\b( )?\.{3}/',                                     //  ellipsis
            '/(\s?)--(\s?)/',                                    //  em dash
            '/\s-(?:\s|$)/',                                     //  en dash
            '/(\d+)( ?)x( ?)(?=\d+)/',                           //  dimension sign
            '/(\b ?|\s|^)[([]TM[])]/i',                          //  trademark
            '/(\b ?|\s|^)[([]R[])]/i',                           //  registered
            '/(\b ?|\s|^)[([]C[])]/i',                           //  copyright
         );

        extract($this->glyph, EXTR_PREFIX_ALL, 'txt');

        $glyph_replace = array(
            '$1'.$txt_apostrophe.'$2',           // apostrophe's
            '$1'.$txt_apostrophe.'$2',           // back in '88
            '$1'.$txt_quote_single_close,        //  single closing
            $txt_quote_single_open,              //  single opening
            '$1'.$txt_quote_double_close,        //  double closing
            $txt_quote_double_open,              //  double opening
            '<acronym title="$2">$1</acronym>',  //  3+ uppercase acronym
            '<span class="caps">$1</span>',      //  3+ uppercase
            '$1'.$txt_ellipsis,                  //  ellipsis
            '$1'.$txt_emdash.'$2',               //  em dash
            ' '.$txt_endash.' ',                 //  en dash
            '$1$2'.$txt_dimension.'$3',          //  dimension sign
            '$1'.$txt_trademark,                 //  trademark
            '$1'.$txt_registered,                //  registered
            '$1'.$txt_copyright,                 //  copyright
         );

         $text = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
         foreach($text as $line) {
             if (!preg_match("/<.*>/", $line)) {
                 $line = preg_replace($glyph_search, $glyph_replace, $line);
             }
              $glyph_out[] = $line;
         }
         return join('', $glyph_out);
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
// NOTE: deprecated
    function encode_high($text, $charset = "UTF-8")
    {
        return mb_encode_numericentity($text, $this->cmap(), $charset);
    }

// -------------------------------------------------------------
// NOTE: deprecated
    function decode_high($text, $charset = "UTF-8")
    {
        return mb_decode_numericentity($text, $this->cmap(), $charset);
    }

// -------------------------------------------------------------
// NOTE: deprecated
    function cmap()
    {
        $f = 0xffff;
        $cmap = array(
            0x0080, 0xffff, 0, $f);
        return $cmap;
    }

// -------------------------------------------------------------
    function encode_html($str, $quotes=1)
    {
        $a = array(
            '&' => '&#38;',
            '<' => '&#60;',
            '>' => '&#62;',
        );
        if ($quotes) $a = $a + array(
            "'" => '&#39;',
            '"' => '&#34;',
        );

        return strtr($str, $a);
    }
    
// -------------------------------------------------------------
    function r_encode_html($str, $quotes=1)
    {
        // in restricted mode, input has already been escaped
        if ($this->restricted)
            return $str;
        return $this->encode_html($str, $quotes);
    }

// -------------------------------------------------------------
    function textile_popup_help($name, $helpvar, $windowW, $windowH)
    {
        return ' <a target="_blank" href="http://www.textpattern.com/help/?item=' . $helpvar . '" onclick="window.open(this.href, \'popupwindow\', \'width=' . $windowW . ',height=' . $windowH . ',scrollbars,resizable\'); return false;">' . $name . '</a><br />';

        return $out;
    }

// -------------------------------------------------------------
// NOTE: deprecated
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
// NOTE: deprecated
    function dump()
    {
        foreach (func_get_args() as $a)
            echo "\n<pre>",(is_array($a)) ? print_r($a) : $a, "</pre>\n";
    }

// -------------------------------------------------------------

    function blockLite($text)
    {
        $this->btag = array('bq', 'p');
        return $this->block($text."\n\n");
    }


} // end class

?>
