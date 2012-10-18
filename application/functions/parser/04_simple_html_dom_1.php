<?php

require_once (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'simple_html_dom.php');
$pattern = '@# match nested tag
(?(DEFINE)
    (?<comment>     <!--.*?-->)
    (?<cdata>       <![CDATA[.*?]]>)
    (?<empty>       <\w+[^>]*?/>)
    (?<inline>      <(script|style)[^>]+>.*?</\g{-1}>)
    (?<nested>      <(\w+)[^>]*(?<!/)>(?&innerHTML)</\g{-1}>)
    (?<unclosed>        <\w+[^>]*(?<!/)>)
    (?<text>        [^<]+)
)
(?<outerHTML><(?<tagName>div)\s?(?<attributes>[^>]*?class\h*=\h*(?<quote>"|\')[^(?&quote)\v>]*\bedit\b[^(?&quote)\v>]*(?&quote)[^>]*)> # opening tag
(?<innerHTML>
    (?: (?&comment) | (?&cdata) | (?&empty) | (?&inline) | (?&nested) | (?&unclosed) | (?&text) )*
)
</(?&tagName)>) # closing tag
@six';


$pattern = '@# match nested tag
(?(DEFINE)
    (?<comment>     <!--.*?-->)
    (?<cdata>       <![CDATA[.*?]]>)
    (?<empty>       <\w+[^>]*?/>)
    (?<inline>      <(script|style)[^>]+>.*?</\g{-1}>)
    (?<nested>      <(\w+)[^>]*(?<!/)>(?&innerHTML)</\g{-1}>)
    (?<unclosed>        <\w+[^>]*(?<!/)>)
    (?<text>        [^<]+)
)
(?<outerHTML><(?<tagName>div)\s?(?<attributes>[^>]*?class\h*=\h*(?<quote>"|\')[^(?&quote)\v>]*\b\s*?edit.*?\h*\b[^(?&quote)\v>]*(?&quote)[^>]*)> # opening tag
(?<innerHTML>
    (?: (?&comment) | (?&cdata) | (?&empty) | (?&inline) | (?&nested) | (?&unclosed) | (?&text) )*
)
</(?&tagName)>) # closing tag
@six';


$the_trim = function_exists('mb_trim') ? 'mb_trim' : 'trim';

$all_edits = array();

static $dom;
if ($dom == false) {
    // Create a DOM object
    $dom = new simple_html_dom();
}
$dom->load($layout);

$div = $dom->find('div.edit');
foreach ($div as $article) {
     $inner = $article->innertext();

$name = $article->getAttribute('id');
d($name);
}

// clean up memory
$dom->clear();
//unset($html);




