<?php

/*

type: layout

name: Default

description: Default Twitter Feed

*/

?>
<script>

    $( document ).ready(function() {
        window.twttr = (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0],
                t = window.twttr || {};
            if (d.getElementById(id)) return t;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);

            t._e = [];
            t.ready = function(f) {
                t._e.push(f);
            };

            return t;
        }(document, "script", "twitter-wjs"));

        twttr.widgets.load()
    });
</script>

<style>

    .twitter-feed-title {
        background: #43ACEF;
        color: #fff;
        font-size: 26px;
        padding: 10px 25px;
    }

    .twitter-feed-default ul {
        list-style-type: none;
    }

    .twitter-feed-default ul li {
        background: #E8F5F9;
        padding: 25px;
        border: 1px solid #43ACEF;
        border-bottom: 0;
    }

    .twitter-feed-default ul li:last-of-type {
        border-bottom: 1px solid #43ACEF;
    }

    .twitter-feed-default ul li a,
    .twitter-feed-default ul li a:hover {
        color: #78AEDF;
        text-decoration: none;
    }

    .twitter-feed-default ul li span {
        color: #757575;
    }

    .twitter-feed-default ul li small,
    .twitter-feed-default ul li small a {
        color: #c5c5c5;
    }

    .twitter-feed-default ul li i {
        background: #fff;
        color: #78AEDF;
        padding: 7px;
        font-size: 20px;
        margin-right: 10px;
        margin-bottom: 10px;
    }


</style>

<?php $twitter_feed_theme = get_option('twitter_feed_theme', $params['id']); ?>
<?php $twitter_feed_width = get_option('twitter_feed_width', $params['id']); ?>
<?php $twitter_feed_align = get_option('twitter_feed_align', $params['id']); ?>
<?php $twitter_feed_conversation = get_option('twitter_feed_conversation', $params['id']); ?>
<?php $twitter_feed_cards = get_option('twitter_feed_cards', $params['id']); ?>


<?php if ($items): ?>

    <?php foreach ($items as $tweet): ?>
        <blockquote data-align="<?php print $twitter_feed_align ?>" data-cards="<?php print $twitter_feed_cards ?>" data-conversation="<?php print $twitter_feed_conversation ?>" data-width="<?php print $twitter_feed_width ?>" data-theme="<?php print $twitter_feed_theme ?>" class="twitter-tweet"><p lang="en" dir="ltr"><a href="<?php print $tweet['url']; ?>"><?php print $tweet['text']; ?></a></p></blockquote>
    <?php endforeach; ?>
<?php endif; ?>
