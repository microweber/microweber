<?php

/*

type: layout

name: Shop Inner - Share Icons

description: Skin 1

*/
?>
<ul class="social-list">

    <?php if ($facebook_enabled) { ?>
        <li>
            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php print mw()->url->current(); ?>">
                <i class="socicon-facebook"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($twitter_enabled) { ?>
        <li>
            <a href="https://twitter.com/intent/tweet?text=<?php print content_title(); ?>&url=<?php print mw()->url->current(); ?>" target="_blank">
                <i class="socicon-twitter"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($googleplus_enabled) { ?>
        <li>
            <a href="https://plus.google.com/share?url=<?php print mw()->url->current(); ?>" target="_blank">
                <i class="socicon-googleplus"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($pinterest_enabled) { ?>
        <script type="text/javascript">
            if (!mw.pinMarklet) {
                mw.pinMarklet = function () {
                    var script = mwd.createElement('script');
                    script.src = '//assets.pinterest.com/js/pinmarklet.js';
                    mwd.body.appendChild(script)
                }
            }
        </script>
        <li>
            <a href="javascript:mw.pinMarklet();" target="_self">
                <i class="socicon-pinterest"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($linkedin_enabled) { ?>
        <li>
            <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php print url_current(); ?>&title=<?php print page_title(); ?>&summary=&source=LinkedIn">
                <i class="socicon-linkedin"></i>
            </a>
        </li>
    <?php } ?>

    <?php if ($whatsapp_enabled) { ?>
        <li>
            <a target="_blank" href="whatsapp://send?text=Check this out: <?php print url_current(); ?> " data-action="share/whatsapp/share">
                <i class="socicon-whatsapp"></i>
            </a>
        </li>
    <?php } ?>
</ul>