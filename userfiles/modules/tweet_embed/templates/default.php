<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php
$statusID = explode('status/', $twitter_url);
if (!isset($statusID[1])) {
    print lnotif('Tweet Embed');
    return;
} else {
    $statusID = $statusID[1];
}
?>

<script>mw.moduleCSS('<?php print module_url(); ?>style.css');</script>
<div class="mw-tweets-embed">
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
            $.getJSON("https://api.twitter.com/1/statuses/oembed.json?id=<?php print $statusID; ?>&align=center&callback=?",
                function (data) {
                    $('.tweet-embeded', '#<?php print $params['id']; ?>').html(data.html);
                });
        });
    </script>

    <div class="tweet-embeded"></div>
</div>