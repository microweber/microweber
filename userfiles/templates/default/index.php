<?php

/*

  type: layout
  content_type: dynamic
  name: Homepage layout
  description: Home layout

*/

?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>

<div class="container edit" id="home-top"  rel="page" field="content">
  <div class="mw-row clearfix">
    <div class="mw-col" style="width: 50%">
      <div class="mw-col-container">
        <module type="pictures" content-id="<?php print PAGE_ID; ?>" template="bootstrap_carousel"  />
      </div>
    </div>
    <div class="mw-col" style="width: 50%">
      <div class="mw-col-container" id="mw-welcome">
        <div class="edit">
          <h2 class="element">Welcome to Microweber</h2>
          <p class="element">This is the default theme of (MW). </p>
          <br>
          <h4 class="element">You are able to create your own Website, Blog, Online Shop or anything you need, for free.</h4>
          <br>
          <h4 class="element" style="font-size: 16px;"><strong>Discover more by using drag and drop technology and Make Web!</strong></h4>
          <br>
          <p class="element"><a href="javascript:;" class="btn btn-large pull-right">Simple Button</a></p>
        </div>
      </div>
    </div>
  </div>
  <div class="container"> <br>
    <br>
    <h3 align="center" class="symbol">Powerful &nbsp;&amp;&nbsp; User Friendly &nbsp;Content Management System &nbsp;of &nbsp;New Generation</h3>
    <h4 align="center">with rich PHP and JavaScript API</h4>
  </div>
  <div class="container">
    <h2 class="section-title">
      <hr class="left visible-desktop">
      <span>
      <?php _e("Latest Posts"); ?>
      </span>
      <hr class="right visible-desktop">
    </h2>
    <module
          data-type="posts"
          data-limit="3"
          id="home-posts"
          data-description-length="100"
          data-show="thumbnail,title,created_on,read_more,description"
          data-template="columns" />
  </div>
  <div class="container">
    <h2 class="section-title">
      <hr class="left visible-desktop">
      <span>
      <?php _e("Latest Products"); ?>
      </span>
      <hr class="right visible-desktop">
    </h2>
    <module
          data-type="shop/products"
          data-limit="3"
          id="home-products"
          data-description-length="150"
          data-show="thumbnail,title,add_to_cart,description,price"
          data-template="columns" />
  </div>
</div>
<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
