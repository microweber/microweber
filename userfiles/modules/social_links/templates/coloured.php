<?php

/*

type: layout

name: Coloured

description: Coloured backgrounds

*/
?>
<script>mw.lib.require('font_awesome5');</script>
<script>mw.moduleCSS('<?php print module_url(); ?>style.css');</script>
<div class="mw-social-links justify">

    <?php if ($social_links_has_enabled == false) {
        print lnotif('Social links');
    } ?>


    <?php if ($facebook_enabled) { ?>


        <a class="facebook" href="//facebook.com/<?php print $facebook_url; ?>" target="_blank" aria-label="Facebook"><span class="mw-icon-facebook"></span></a>

    <?php } ?>

    <?php if ($twitter_enabled) { ?>

        <a class="twitter" href="//twitter.com/<?php print $twitter_url; ?>" target="_blank" aria-label="Twitter"><span class="mw-icon-twitter"></span></a>

    <?php } ?>

    <?php if ($pinterest_enabled) { ?>

        <a class="pinterest" href="//pinterest.com/<?php print $pinterest_url; ?>" target="_blank" aria-label="Pinterest"><span class="mw-icon-social-pinterest"></span></a>

    <?php } ?>

    <?php if ($youtube_enabled) { ?>

        <a class="youtube" href="//youtube.com/<?php print $youtube_url; ?>" target="_blank" aria-label="YouTube"><span class="mw-icon-social-youtube"></span></a>

    <?php } ?>

    <?php if ($instagram_enabled) { ?>

        <a class="instagram" href="https://instagram.com/<?php print $instagram_url; ?>" target="_blank" aria-label="Instagram"><span class="mw-icon-social-instagram"></span></a>

    <?php } ?>

    <?php if ($linkedin_enabled) { ?>

        <a class="linkedin" href="//linkedin.com/<?php print $linkedin_url; ?>" target="_blank" aria-label="LinkedIn"><span class="mw-icon-social-linkedin"></span></a>

    <?php } ?>

    <?php if ($github_enabled) { ?>

        <a class="github" href="//github.com/<?php print $github_url; ?>" target="_blank" aria-label="GitHub"><span class="mw-icon-social-github"></span></a>

    <?php } ?>

    <?php if ($soundcloud_enabled) { ?>

        <a class="soundcloud" href="//soundcloud.com/<?php print $soundcloud_url; ?>" target="_blank" aria-label="SoundCloud"><span class="fab fa-soundcloud mw-icon-social-soundcloud"></span></a>

    <?php } ?>

    <?php if ($mixcloud_enabled) { ?>

        <a class="mixcloud" href="//mixcloud.com/<?php print $mixcloud_url; ?>" target="_blank" aria-label="Mixcloud"><span class="fab fa-mixcloud mw-icon-social-mixcloud"></span></a>

    <?php } ?>

    <?php if ($medium_enabled) { ?>

        <a class="medium" href="//medium.com/<?php print $medium_url; ?>" target="_blank" aria-label="Medium"><span class="fab fa-medium mw-icon-social-medium"></span></a>

    <?php } ?>


</div>