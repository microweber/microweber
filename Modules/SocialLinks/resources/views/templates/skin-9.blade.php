<?php

/*

type: layout

name: skin-9

description: skin-9

*/
?>

<style>
    .social-links-rounded {

        ul {
            margin: 0;
            padding: 0;
        }

        li {
            list-style: none;
            display: inline-block;
            vertical-align: top;
        }

        a {
            border: 1px solid #ececec;
            border-radius: 100px;
            display: inline-block;
            vertical-align: top;
            margin: 2px 2px 5px 2px;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;

            i::before {
                font-size: 20px;
                color: var(--mw-primary-color);

            }
        }

        a:hover {
            background-color: var(--mw-primary-color);
            border-color: transparent;
            i::before {

                color: #fff;
            }
        }
    }
</style>
<div class="social-links-rounded">
    <ul class="list-inline no-style">

    <?php if ($social_links_has_enabled == false) {
        print lnotif('Social links');
    } ?>

    <?php if ($facebook_enabled) { ?>
        <li class="me-3"><a href="<?php print $facebook_url; ?>" target="_blank"><i class="mdi mdi-facebook"></i></a></li>
    <?php } ?>

    <?php if ($twitter_enabled) { ?>
        <li class="me-3"><a href="<?php print $twitter_url; ?>" target="_blank"><i class="mdi mdi-twitter"></i></a></li>
    <?php } ?>

    <?php if ($googleplus_enabled) { ?>
        <li class="me-3"><a href="<?php print $googleplus_url; ?>" target="_blank"><i class="mdi mdi-google-plus"></i></a></li>
    <?php } ?>

    <?php if ($pinterest_enabled) { ?>
        <li class="me-3"><a href="<?php print $pinterest_url; ?>" target="_blank"><i class="mdi mdi-pinterest"></i></a></li>
    <?php } ?>

    <?php if ($youtube_enabled) { ?>
        <li class="me-3"><a href="<?php print $youtube_url; ?>" target="_blank"><i class="mdi mdi-youtube"></i></a></li>
    <?php } ?>

    <?php if ($instagram_enabled) { ?>
        <li class="me-3"><a href="<?php print $instagram_url; ?>" target="_blank"><i class="mdi mdi-instagram"></i></a></li>
    <?php } ?>

    <?php if ($linkedin_enabled) { ?>
        <li class="me-3"><a href="<?php print $linkedin_url; ?>" target="_blank"><i class="mdi mdi-linkedin"></i></a></li>
    <?php } ?>

    <?php if ($github_enabled) { ?>
        <li class="me-3"><a href="<?php print $github_url; ?>" target="_blank"><i class="mdi mdi-github"></i></a></li>
    <?php } ?>

    <?php if ($soundcloud_enabled) { ?>
        <li class="me-3"><a href="<?php print $soundcloud_url; ?>" target="_blank"><i class="mdi mdi-soundcloud"></i></a></li>
    <?php } ?>

    <?php if ($mixcloud_enabled) { ?>
        <li class="me-3"><a href="<?php print $mixcloud_url; ?>" target="_blank"><i class="fab fa-mixcloud"></i></a></li>
    <?php } ?>

    <?php if ($medium_enabled) { ?>
        <li class="me-3"><a href="<?php print $medium_url; ?>" target="_blank"><i class="fab fa-medium"></i></a></li>
    <?php } ?>

    <?php if ($discord_enabled) { ?>
        <li class="mx-1"><a href="<?php print $discord_url; ?>" target="_blank"><i class="mdi mdi-discord"></i></a></li>
    <?php } ?>

    <?php if ($skype_enabled) { ?>
        <li class="mx-1"><a href="<?php print $skype_url; ?>" target="_blank"><i class="mdi mdi-skype"></i></a></li>
    <?php } ?>

    <?php if ($telegram_enabled) { ?>
        <li class="mx-1"><a href="<?php print $telegram_url; ?>" target="_blank"><i class="mdi mdi-telegram"></i></a></li>
    <?php } ?>
</ul>
</div>
