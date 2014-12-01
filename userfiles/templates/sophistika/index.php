<?php

/*

  type: layout
  content_type: static
  name: Home
  position: 2
  description: Home layout

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>
<script>

    $(document).ready(function(){
       $("#header").height($(window).height());
       $("#go-to-content-anchor").css('visibility', 'visible');
    });
    $(window).bind("load resize", function(){
       $("#header").height($(window).height());
    });

</script>

<div class="container">
  <div class="edit" field="content" rel="content">
    <div class="content-gallery-slider home-slider">
      <module
          type="pictures"
          content-id="<?php print PAGE_ID; ?>"
          template="bootstrap_carousel"
        />
    </div>
    <div class="box-container latest-items" style="margin-bottom: 0;">
      <h2 class="element section-title"> <small>What's new</small> <span>From the store</span> </h2>
      <module type="shop/products" limit="3" hide-paging="true" data-show="thumbnail,title,add_to_cart,price" template="3columns">
    </div>
      <hr class="hr1">
      <br><br>
    <div class="box-container latest-items">
      <h2 class="element section-title"> <small>Read The  </small> <span>Latest News</span> </h2>
      <module type="posts" template="masonry" limit="3" hide-paging="true" data-show="thumbnail,title,description">
    </div>
    <br>
    <hr class="hr1">
    <br>

    <h2 class="element section-title">  <span>Our Clients</span> </h2>
    <img src="<?php print TEMPLATE_URL; ?>img/clients.png" alt="" />

  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>
