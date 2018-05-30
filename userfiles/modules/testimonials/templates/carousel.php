<?php

/*

type: layout

name: Carousel

description: Carousel testimonials template

Uses Animate functions which work only with one item and only in browsers that support perspective property.
*/

?>

<script>
     mw.require("<?php print $config['url_to_module']; ?>testimonials.css", true);
     if(!window.jQuery.fn.owlCarousel){
       mw.require("<?php print $config['url_to_module']; ?>owl.carousel.min.js", true);
       mw.require("<?php print $config['url_to_module']; ?>owl.carousel.css", true);
       mw.require("<?php print $config['url_to_module']; ?>animate.css", true);
     }
</script>

<style>
.testimonials-wrapper { max-width: 100%; }
.rotating-item { background-color:#f5f5f5; padding:10px; }
</style>

<div id="rotator_<?php print $params['id'] ?>" class="testimonials-wrapper">
  <?php if ($data): ?>
  <?php foreach ($data as $item): ?>
  <div class="rotating-item">
    <h3 class="testimonial-name"><?php print $item['name'] ?></h3>
    <div class="testimonial-content">
      <div class="mw-ui-row">
        <?php if($openquote != false and $openquote != ''){  ?>
        <div class="mw-ui-col" style="width: 70px;">
          <div class="mw-ui-col-container"> <img src="<?php print $openquote;  ?>" alt="'" class="testimonial-quote-image" /> </div>
        </div>
        <?php } ?>
        <div class="mw-ui-col">
          <div class="mw-ui-col-container">
            <?php

                print character_limiter($item['content'], $limit, '...');

              ?>
            <?php if(isset($item['read_more_url']) and $item['read_more_url'] != ''){ ?>
            &nbsp;&nbsp;<a href="<?php print $item['read_more_url']; ?>" class="mw-ui-link">Read More</a>
            <?php } ?>
          </div>
        </div>
        <?php if($closequote != false and $closequote != ''){  ?>
        <div class="mw-ui-col" style="width: 70px;">
          <div class="mw-ui-col-container"> <img src="<?php print $closequote;  ?>" alt="'" class="testimonial-quote-image" /> </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<script>
    $(document).ready(function () {
        $("#rotator_<?php print $params['id'] ?>").owlCarousel({
            items:1, //
            autoplay:true,
            loop:true,
            autoplayTimeout:<?php print ($interval * 1000); ?>,
            autoplayHoverPause:true,
            dots:true,
            margin:0,
			center:true,
    		animateOut: 'slideOutDown',
    		animateIn: 'flipInX'
  			//animateOut: 'slideOutUp',
  			//animateIn: 'slideInUp'
        });
    });
</script>
