<?php

/*

type: layout

name: Masonry

description: Masonry skin for Twitter Feed

*/

?>
<?php $uid = uniqid('twitter-feed-'); ?>
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


            setTimeout(function() {
                if(typeof twttr !== "undefined" typeof twttr.widgets.load !== 'function'){
                    twttr.widgets.load();
                }
            }, 1000);


        var msnConfig = {
            itemSelector : '.grid-item-masonry-twitter',
            columnWidth : '.grid-item-masonry-twitter-sizer',
            fitWidth: false
        }
        var el = $('#<?php print $uid; ?>').masonry(msnConfig);

        mw.spinner({
            element: el.get(0),
            decorate: true,
            size: 50
        }).show()

        // When widget is ready, run masonry
        twttr.ready(function (twttr) {
            twttr.events.bind('loaded', function (event) {



                setTimeout(function (){
                    el.masonry(msnConfig);
                    mw.spinner({
                        element: el.get(0),
                        decorate: true
                    }).remove()
                    el.addClass('ready')
                }, 700)
                var time = null;
                window.addEventListener('resize', function () {
                    clearTimeout(time);
                    time = setTimeout(function (){
                        el.masonry(msnConfig)
                    }, 400)
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
    .grid-twitter-masonry .d-none{
        display: block !important;
    }
    .grid-twitter-masonry {
        width:100%;
        /*max-width: 940px;*/
        margin: 30px auto;
        max-width: 100%;



    }

    .grid-item-masonry-twitter .twitter-tweet,
    .grid-item-masonry-twitter .twitter-tweet iframe{
        display: block !important;
    }
    .grid-item-masonry-twitter,
    .grid-item-masonry-twitter-sizer{
        width: 30%;
        margin: 1%;
        opacity: 0;
    }
    .ready .grid-item-masonry-twitter{
        opacity: 1;
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



</style>

<?php $twitter_feed_theme = get_option('twitter_feed_theme', $params['id']); ?>
<?php $twitter_feed_width = get_option('twitter_feed_width', $params['id']); ?>
<?php $twitter_feed_align = get_option('twitter_feed_align', $params['id']); ?>
<?php $twitter_feed_conversation = get_option('twitter_feed_conversation', $params['id']); ?>
<?php $twitter_feed_cards = get_option('twitter_feed_cards', $params['id']); ?>


<?php if ($items): ?>
<div class="grid-twitter-masonry" id="<?php print $uid; ?>">
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

                        <a href="<?php print $tweet['url']; ?>">

                        </a>

            </blockquote>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
