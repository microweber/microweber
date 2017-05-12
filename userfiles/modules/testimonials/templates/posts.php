<?php

/*

type: layout

name: Posts

description: Testimonials displayed as posts

*/

?>



<script>mw.require("<?php print $config['url_to_module'] ?>templates/templates.css", true);</script>

<div class="mw-testimonials mw-testimonials-posts">

<?php $data = get_testimonials(); ?>


	<?php


        foreach($data as $item){


      ?>

      <div class="mw-testimonials-item">
        <span class="mw-testimonials-item-image" style="background-image: url(<?php print $item['client_picture']; ?>);"></span>
        <div class="mw-testimonials-item-content">
        <?php if(isset($item['client_website'])){ ?>
            <h4><a href="<?php print $item['client_website']; ?>" target="_blank"><?php print $item['name']; ?></a></h4>
        <?php } else{ ?>
            <h5><?php print $item['name']; ?></h5>
        <?php } ?>
            <span class="mw-testimonials-item-role"><em><?php print $item['client_role']; ?></em> &nbsp;at&nbsp;<strong><?php print $item['client_company']; ?></strong></span>
            <?php if(isset($item["project_name"])){ ?>
                <h5><?php print $item["project_name"]; ?></h5>
            <?php } ?>
            <p><?php print nl2br($item['content']); ?></p>
            <?php if(isset($item["read_more_url"])){ ?>
                <div><a href="<?php print $item["read_more_url"]; ?>" target="_blank"><?php _e('Read more'); ?></a></div>
            <?php } ?>
        </div>
      </div>

   
    <?php } ?>
</div>
