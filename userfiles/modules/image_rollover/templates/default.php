<?php

//if($size != 'auto') $size = ' width:' . $size . 'px';
$size = ($size == 'auto'? '':' width:' . $size . 'px');

$style = ($size == ''? '':' style="' . $size . '"');
?>

<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>/styles.css"); </script>

  <?php
    if (is_live_edit() and $default_image == '' and $text == '') {
  ?>
        <span class="mw-image-rollover-no-values"><?php _e('Click to add image'); ?></span>
  <?php
    } else {
  ?>

	  <div class="rollover"<?php print $style; ?>>

			 <p class="rollover-images">
			   <a href="<?php print $href_url; ?>">
				 <img class="default-image" src="<?php print $default_image; ?>" alt=""<?php print $style;?>/>
			   </a>
			   <div class="overlay">
				 <a href="<?php print $href_url; ?>"<?php print $style;?>>
				   <img src="<?php print $rollover_image; ?>" alt=""<?php print $style;?>/>
				 </a>
			   </div>
			 </p>

			<p class="module-image-rollover-text">
				<a href="<?php print $href_url; ?>">
				  <?php print $text; ?>
				</a>
			</p>

	  </div>

  <?php } ?>