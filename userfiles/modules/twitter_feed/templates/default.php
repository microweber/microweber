<?php

/*

type: layout

name: Default

description: Default Twitter Feed

*/

?>
<script>

    $( document ).ready(function() {
        mw.on('twttr.widgets.load', function (event, data) {
            if(typeof twttr !== "undefined" && typeof twttr.widgets.load !== 'function'){
                twttr.widgets.load();
            }
        });

    });

    $( document ).ready(function() {

        window.twttr = (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0],
                t = window.twttr || {};
            if (d.getElementById(id)) return t;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);
            t._e = [];
            t.ready = function (f) {
                t._e.push(f);
                mw.trigger("twttr.widgets.load");
            };
            return t;
        }(document, "script", "twitter-wjs"));

    });





</script>

<?php $twitter_feed_theme = get_option('twitter_feed_theme', $params['id']); ?>
<?php $twitter_feed_width = get_option('twitter_feed_width', $params['id']); ?>
<?php $twitter_feed_align = get_option('twitter_feed_align', $params['id']); ?>
<?php $twitter_feed_conversation = get_option('twitter_feed_conversation', $params['id']); ?>
<?php $twitter_feed_cards = get_option('twitter_feed_cards', $params['id']); ?>


<?php if ($items): ?>

    <?php foreach ($items as $tweet): ?>
        <blockquote data-id="<?php print $tweet['id']; ?>" data-align="<?php print $twitter_feed_align ?>" data-cards="<?php print $twitter_feed_cards ?>" data-conversation="<?php print $twitter_feed_conversation ?>" data-width="<?php print $twitter_feed_width ?>" data-theme="<?php print $twitter_feed_theme ?>" class="twitter-tweet"><p lang="en" dir="ltr"><a href="<?php print $tweet['url']; ?>"><span class="d-none"><?php print $tweet['text']; ?></span></a></p></blockquote>
    <?php endforeach; ?>
<?php endif; ?>
