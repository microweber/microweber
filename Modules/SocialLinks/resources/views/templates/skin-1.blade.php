<?php

/*

type: layout

name: Skin-1

description: Skin-1

*/
?>
<ul class="list-inline no-style mb-0">

    <?php if ($social_links_has_enabled == false) {
        print lnotif('Social links');
    } ?>

    <?php if ($facebook_enabled) { ?>
        <li class="me-3"><a href="<?php print $facebook_url; ?>" target="_blank"  ><i class="mdi mdi-facebook mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($twitter_enabled) { ?>
        <li class="me-3"><a href="<?php print $twitter_url; ?>" target="_blank"  ><i class="mdi mdi-twitter mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($googleplus_enabled) { ?>
        <li class="me-3"><a href="<?php print $googleplus_url; ?>" target="_blank"  ><i class="mdi mdi-google-plus mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($pinterest_enabled) { ?>
        <li class="me-3"><a href="<?php print $pinterest_url; ?>" target="_blank"  ><i class="mdi mdi-pinterest mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($youtube_enabled) { ?>
        <li class="me-3"><a href="<?php print $youtube_url; ?>" target="_blank"  ><i class="mdi mdi-youtube mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($instagram_enabled) { ?>
        <li class="me-3"><a href="<?php print $instagram_url; ?>" target="_blank"  ><i class="mdi mdi-instagram mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($linkedin_enabled) { ?>
        <li class="me-3"><a href="<?php print $linkedin_url; ?>" target="_blank"  ><i class="mdi mdi-linkedin mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($github_enabled) { ?>
        <li class="me-3"><a href="<?php print $github_url; ?>" target="_blank"  ><i class="mdi mdi-github mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($soundcloud_enabled) { ?>
        <li class="me-3"><a href="<?php print $soundcloud_url; ?>" target="_blank"  ><i class="mdi mdi-soundcloud mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($mixcloud_enabled) { ?>
        <li class="me-3"><a href="<?php print $mixcloud_url; ?>" target="_blank"  ><i class="fab fa-mixcloud mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($medium_enabled) { ?>
        <li class="me-3"><a href="<?php print $medium_url; ?>" target="_blank"  ><i class="fab fa-medium mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($discord_enabled) { ?>
        <li class="mx-1"><a href="<?php print $discord_url; ?>" target="_blank"><i class="mdi mdi-discord mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($skype_enabled) { ?>
        <li class="mx-1"><a href="<?php print $skype_url; ?>" target="_blank"><i class="mdi mdi-skype mdi-40px"></i></a></li>
    <?php } ?>

    <?php if ($telegram_enabled) { ?>
        <li class="mx-1"><a href="<?php print $telegram_url; ?>" target="_blank"><i class="mdi mdi-telegram mdi-40px"></i></a></li>
    <?php } ?>
</ul>
