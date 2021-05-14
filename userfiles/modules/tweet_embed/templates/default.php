<?php

/*

type: layout

name: Default

description: Default

*/
?>



<script>mw.moduleCSS('<?php print module_url(); ?>style.css');</script>
<div class="mw-tweets-embed">
    <script src="http://platform.twitter.com/widgets.js"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
            $.getJSON("https://api.twitter.com/1/statuses/oembed.json?id=<?php print $status_id; ?>&align=center&callback=?",
                function (data) {
                    $('.tweet-embeded', '#<?php print $params['id']; ?>').html(data.html);
                });
        });
    </script>

    <div class="tweet-embeded"></div>
</div>
