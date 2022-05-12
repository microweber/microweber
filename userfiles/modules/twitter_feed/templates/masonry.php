<?php

/*

type: layout

name: Masonry

description: Masonry skin for Twitter Feed

*/

?>
<script>
    mw.require("<?php print modules_url(); ?>pictures/js/masonry.pkgd.min.js");
</script>
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

        // twttr.widgets.load()


        // When widget is ready, run masonry
        twttr.ready(function (twttr) {
            twttr.events.bind('loaded', function (event) {
                $('.grid-twitter-masonry').masonry({
                    itemSelector : '.grid-item-masonry-twitter',
                    columnWidth : '.grid-item-masonry-twitter-sizer',
                    gutter: 20,
                    fitWidth: true
                });
                var time = null;
                window.addEventListener('resize', function () {
                    clearTimeout(time);
                    time = setTimeout(function (){
                        $('.grid-twitter-masonry').masonry({
                            itemSelector : '.grid-item-masonry-twitter',
                            columnWidth : '.grid-item-masonry-twitter-sizer',
                            gutter: 20,
                            fitWidth: true
                        });
                    }, 700)
                })
            });
        });

    });
</script>

<style>
    .grid-twitter-masonry .twitter-tweet{
        max-width: 100% !important;
    }
    .grid-twitter-masonry iframe{
        width: 100% !important;
    }
    .grid-twitter-masonry {
        width:100%;
        /*max-width: 940px;*/
        margin: 50px auto;
        max-width: 100%;

    }

    .grid-item-masonry-twitter,
    .grid-item-masonry-twitter-sizer{
        width: 30%;
    }

    @media (max-width: 1400px) {
        .grid-item-masonry-twitter,
        .grid-item-masonry-twitter-sizer{
            width: 48%;
        }
    }
    @media (max-width: 700px) {
        .grid-item-masonry-twitter,
        .grid-item-masonry-twitter-sizer{
            width: 100%;
        }
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
<div class="grid-twitter-masonry">
    <div class="grid-item-masonry-twitter-sizer"></div>
    <?php foreach ($items as $tweet): ?>
        <div class="grid-item-masonry-twitter">
            <blockquote
                data-id="<?php print $tweet['id']; ?>"
                data-align="<?php print $twitter_feed_align ?>"
                data-cards="<?php print $twitter_feed_cards ?>"
                data-conversation="<?php print $twitter_feed_conversation ?>"
                data-width="<?php print $twitter_feed_width ?>"
                data-theme="<?php print $twitter_feed_theme ?>"
                class="twitter-tweet">
                    <p lang="en" dir="ltr">
                        <a href="<?php print $tweet['url']; ?>">
                            <span class="d-none"><?php print $tweet['text']; ?></span>
                        </a>
                    </p>
            </blockquote>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
