<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php if ($social_links_has_enabled == false) {
    print lnotif('Social links');
} ?>

<ul class="social-list">
    <?php if ($facebook_enabled) { ?>
        <li>
            <a href="//facebook.com/<?php print $facebook_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Facebook">
                <i class="socicon-facebook"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($twitter_enabled) { ?>
        <li>
            <a href="//twitter.com/<?php print $twitter_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Twitter">
                <i class="socicon-twitter"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($googleplus_enabled) { ?>
        <li>
            <a href="//plus.google.com/<?php print $googleplus_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Google plus">
                <i class="socicon-googleplus"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($pinterest_enabled) { ?>
        <li>
            <a href="//pinterest.com/<?php print $pinterest_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Pinterest">
                <i class="socicon-pinterest"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($youtube_enabled) { ?>
        <li>
            <a href="//youtube.com/<?php print $youtube_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="YouTube">
                <i class="socicon-youtube"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($instagram_enabled) { ?>
        <li>
            <a href="https://instagram.com/<?php print $instagram_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Instagram">
                <i class="socicon-instagram"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($linkedin_enabled) { ?>
        <li>
            <a href="//linkedin.com/<?php print $linkedin_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Linkedin">
                <i class="socicon-linkedin"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($soundcloud_enabled) { ?>

        <li><a href="//soundcloud.com/<?php print $soundcloud_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="SoundCloud"><i class="socicon-soundcloud"></i></a></li>

    <?php } ?>

    <?php if ($mixcloud_enabled) { ?>

        <li><a href="//mixcloud.com/<?php print $mixcloud_url; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="MixCloud"><i class="socicon-mixcloud"></i></a></li>

    <?php } ?>

    <?php if ($rss_enabled) { ?>
        <li>
            <a href="<?php site_url(); ?>rss" target="_blank" data-toggle="tooltip" data-placement="top" title="Rss">
                <i class="socicon-rss"></i>
            </a>
        </li>
    <?php } ?>
</ul>