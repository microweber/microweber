<?php

/*

type: layout

name: Default

description: Default

*/
?>

<script>mw.lib.require('font_awesome');</script>

<div class="mw-social-share-links">
    <?php if ($facebook_enabled) { ?>
        <a class="btn btn--sm bg--facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php print mw()->url->current(); ?>">
            <span class="btn__text"><i class="socicon-facebook"></i> Share on Facebook</span>
        </a>
    <?php } ?>

    <?php if ($twitter_enabled) { ?>
        <a class="btn btn--sm bg--twitter" href="https://twitter.com/intent/tweet?text=<?php print content_title(); ?>&url=<?php print mw()->url->current(); ?>" target="_blank">
            <span class="btn__text"><i class="socicon-twitter"></i> Share on Twitter</span>
        </a>
    <?php } ?>

    <?php if ($googleplus_enabled) { ?>
        <a class="btn btn--sm bg--googleplus" href="https://plus.google.com/share?url=<?php print mw()->url->current(); ?>" target="_blank">
            <span class="btn__text"><i class="socicon-googleplus"></i> Share on GooglePlus</span>
        </a>
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

        <a class="btn btn--sm bg--pinterest" href="javascript:mw.pinMarklet();" target="_self">
            <span class="btn__text"><i class="socicon-pinterest"></i> Share on Pinterest</span>
        </a>
    <?php } ?>

    <?php if ($linkedin_enabled) { ?>
        <a class="btn btn--sm bg--linkedin" target="_blank"
           href="https://www.linkedin.com/shareArticle?mini=true&url=<?php print url_current(); ?>&title=<?php print page_title(); ?>&summary=&source=LinkedIn">
            <span class="btn__text"><i class="socicon-linkedin"></i> Share on LinkedIn</span>
        </a>
    <?php } ?>

    <?php if ($whatsapp_enabled) { ?>
        <a class="btn btn--sm bg--whatsapp hidden-lg hidden-md" target="_blank" href="whatsapp://send?text=Check this out: <?php print url_current(); ?> " data-action="share/whatsapp/share">
            <span class="btn__text"><i class="socicon-whatsapp"></i> Share on Whatsapp</span>
        </a>
    <?php } ?>
</div>