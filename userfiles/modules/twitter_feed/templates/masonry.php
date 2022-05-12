<?php

/*

type: layout

name: Masonry

description: Masonry skin for Twitter Feed

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


        // When widget is ready, run masonry
        twttr.ready(function (twttr) {
            twttr.events.bind('loaded', function (event) {
                $('.grid-twitter-masonry').masonry({
                    itemSelector : '.grid-item-masonry-twitter',
                    columnWidth : 300,
                    gutter: 20
                });
            });
        });
        mw.require("<?php print modules_url(); ?>pictures/js/masonry.pkgd.min.js", true);
    });
</script>

<style>
    .grid-twitter-masonry {
        width:100%;
        max-width: 940px;
        margin: 50px auto;
    }
    .grid-item-masonry-twitter {
        float:left;
        padding: 0px 3px;
        width: 300px;
    }

    .twitter-tweet.twitter-tweet-rendered {
        margin-right: 0px!important;
    }

</style>

<?php $twitter_feed_theme = get_option('twitter_feed_theme', $params['id']); ?>
<?php $twitter_feed_width = get_option('twitter_feed_width', $params['id']); ?>
<?php $twitter_feed_align = get_option('twitter_feed_align', $params['id']); ?>
<?php $twitter_feed_conversation = get_option('twitter_feed_conversation', $params['id']); ?>
<?php $twitter_feed_cards = get_option('twitter_feed_cards', $params['id']); ?>


<?php if ($items): ?>
    <?php foreach ($items as $tweet): ?>
        <div class="grid-twitter-masonry" data-masonry='{ "itemSelector": ".grid-item", "columnWidth": 300, "gutter":20 }'>
            <div class="grid-item-masonry-twitter">

                <blockquote data-id="<?php print $tweet['id']; ?>" data-align="<?php print $twitter_feed_align ?>" data-cards="<?php print $twitter_feed_cards ?>" data-conversation="<?php print $twitter_feed_conversation ?>" data-width="<?php print $twitter_feed_width ?>" data-theme="<?php print $twitter_feed_theme ?>" class="twitter-tweet"><p lang="en" dir="ltr"><a href="<?php print $tweet['url']; ?>"><span class="d-none"><?php print $tweet['text']; ?></span></a></p></blockquote>

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
