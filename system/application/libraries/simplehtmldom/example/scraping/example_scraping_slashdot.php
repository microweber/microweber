<?php
include_once('../../simple_html_dom.php');

function scraping_slashdot() {
    // create HTML DOM
    $html = file_get_html('http://slashdot.org/');

    // get article block
    foreach($html->find('div[id^=firehose-]') as $article) {
        // get title
        $item['title'] = trim($article->find('a.datitle', 0)->plaintext);
        // get body
        $item['body'] = trim($article->find('div.body', 0)->plaintext);

        $ret[] = $item;
    }
    
    // clean up memory
    $html->clear();
    unset($html);

    return $ret;
}

// -----------------------------------------------------------------------------
// test it!
$ret = scraping_slashdot();

foreach($ret as $v) {
    echo $v['title'].'<br>';
    echo '<ul>';
    echo '<li>'.$v['body'].'</li>';
    echo '</ul>';
}
?>