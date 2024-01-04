<?php

/*

type: layout

name: Default

description: Default

*/
?>

<script>mw.lib.require('font_awesome');</script>

<div class="mw-social-share-links">
    <script>mw.moduleCSS('<?php print module_url(); ?>style.css');</script>

    <?php if ($facebook_enabled) { ?>
        <a   target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php print mw()->url->current(); ?>"><span class="mdi mdi-facebook"></span></a>
    <?php } ?>

    <?php if ($twitter_enabled) { ?>
        <a   href="https://twitter.com/intent/tweet?text=<?php print content_title(); ?>&url=<?php print mw()->url->current(); ?>" target="_blank"><span class="mdi mdi-twitter"></span></a>
    <?php } ?>

    <?php if ($pinterest_enabled) { ?>
        <script type="text/javascript">
            if (!mw.pinMarklet) {
                mw.pinMarklet = function () {
                    var script = document.createElement('script');
                    script.src = '//assets.pinterest.com/js/pinmarklet.js';
                    document.body.appendChild(script)
                }
            }
        </script>

        <a   href="javascript:mw.pinMarklet();" target="_self"><span class="mdi mdi-pinterest"></span></a>
    <?php } ?>

    <?php if ($linkedin_enabled) { ?>
        <a   href="https://www.linkedin.com/shareArticle?mini=true&url=<?php print url_current(); ?>&title=<?php print page_title(); ?>&summary=&source=LinkedIn" target="_blank">
            <span class="mdi mdi-linkedin"></span></a>
    <?php } ?>

    <?php if ($viber_enabled) { ?>
        <a   target="_blank" href="#" id="viber_share"><span class="mdi mdi-viber"></span></a>
        <script>
            var buttonID = "viber_share";
            var text = "Check this out: ";
            document.getElementById(buttonID)
                .setAttribute('href', "https://3p3x.adj.st/?adjust_t=u783g1_kw9yml&adjust_fallback=https%3A%2F%2Fwww.viber.com%2F%3Futm_source%3DPartner%26utm_medium%3DSharebutton%26utm_campaign%3DDefualt&adjust_campaign=Sharebutton&adjust_deeplink=" + encodeURIComponent("viber://forward?text=" + encodeURIComponent(text + " " + window.location.href)));
        </script>
    <?php } ?>

    <?php if ($whatsapp_enabled) { ?>
        <a   target="_blank" href="whatsapp://send?text=Check this out: <?php print url_current(); ?> " data-action="share/whatsapp/share"><span class="mdi mdi-whatsapp"></span></a>
    <?php } ?>
</div>
