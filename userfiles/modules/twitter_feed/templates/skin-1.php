<?php

/*

type: layout

name: skin-1

description: Alternative skin for twitter module

*/

?>

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

<?php if ($items): ?>
    <div class="twitter-feed-default">
        <div class="twitter-feed-title">Twitter Feed</div>
        <ul class="widget-twitter margin-bottom-60">
            <?php foreach ($items as $tweet): ?>
                <li><i class="fa fa-twitter"></i><span>
                        <?php

                        $tweetText = $tweet['text'];
                        $hashPattern = '/\#([A-Za-z0-9\_]+)/i';
                        $mentionPattern = '/\@([A-Za-z0-9\_]+)/i';
                        $urlPattern = '/(http[s]?\:\/\/[^\s]+)/i';
                        $robotsFollow = false;

                        $tweetText = preg_replace($urlPattern, '<a href="$1" rel="nofollow"' . '>$1</a>', $tweetText);
                        $tweetText = preg_replace($hashPattern, '<a href="http://twitter.com/hashtag/$1" >#$1</a>', $tweetText);
                        $tweetText = preg_replace($mentionPattern, '<a href="http://twitter.com/$1">@$1</a>', $tweetText);

                        print ($tweetText); ?>
                    </span>
                    <small><a href="<?php print $tweet['url']; ?>" target="_blank"><?php print $tweet['ago']; ?></a></small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
