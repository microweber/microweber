<?php

/*

  type: layout

  name: Default

  description: Default



*/

?>
<script>
    mw.require("<?php print $config['url_to_module'];?>style.css");
</script>

<div class="content-categories-images-holder">
    <div class="content-categories-images">

        <?php
        if ($data != false) {
            foreach ($data as $cat) { ?>

                <a href="<?php print category_link($cat['id']); ?>"> <span class="content-categories-images-img"
                                                                            style="background-image: url(<?php print thumbnail($cat['picture'], 600, 600); ?>);"></span>
                    <strong><?php print $cat['title']; ?></strong> </a>

            <?php }
        } ?>


    </div>
</div>