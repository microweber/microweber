<?php

/*

type: layout

name: Default

description: Default Twitter Feed

*/

?>

<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>templates/templates.css"); </script>



<?php if($items): ?>
<div class="twitter-feed-default">
<?php foreach($items as $tweet): ?>

<div class="twitter-feed-item">

<span  target="_blank">
    <a class="twitter-feed-item-image" href="<?php print $tweet['url']; ?>" style="background-image: url(<?php print $tweet['profile_image']; ?>);"></a>
    <span class="twitter-feed-item-content">
    <span class="twitter-feed-item-user"><?php print $tweet['name']; ?></span>
    <?php

    $tweetText = $tweet['text'];;
    $hashPattern = '/\#([A-Za-z0-9\_]+)/i';
    $mentionPattern = '/\@([A-Za-z0-9\_]+)/i';
    $urlPattern = '/(http[s]?\:\/\/[^\s]+)/i';
    $robotsFollow = false;

    $tweetText = preg_replace($urlPattern, '<a href="$1" rel="nofollow"' . '>$1</a>', $tweetText);
    $tweetText = preg_replace($hashPattern, '<a href="http://twitter.com/hashtag/$1" >#$1</a>', $tweetText);
    $tweetText = preg_replace($mentionPattern, '<a href="http://twitter.com/$1">@$1</a>', $tweetText);

    if($tweet['media'] != false){
        $tweetText .= '<img class="twitter-feed-item-media" src="' .$tweet['media'] . '">';
    }


    print ($tweetText ); ?>
    </span>
</span>

</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
