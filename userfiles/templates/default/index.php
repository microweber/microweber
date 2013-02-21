<?php

/*

  type: layout
  content_type: dynamic
  name: Homepage layout
  description: Home layout

*/

?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>


<div class="container edit" id="home-top">
    <div class="mw-row clearfix">
        <div class="mw-col" style="width: 50%">
          <div class="mw-col-container">
              <module type="pictures" template="bootstrap_carousel"  />
          </div>
        </div>
        <div class="mw-col" style="width: 50%">
            <div class="mw-col-container">
                <div class="edit">
                    <h2 class="element">Welcome to Microweber</h2>
                    <h4 class="element">New World Theme</h4>
                    <blockquote class="element"><em>Imagine " ... A world without rules and controls, without borders or boundaries; a world where anything is possible. Where we go from there is a choice I leave to you. ... "</em></blockquote>
                    <p class="element">
                      You can edit this text in what ever ways you like.<br>
                      You can format it, delete it or even insert other elements inside it. <br>
                      You can do what ever you want. It's up to your imagination. <br>
                    </p>
                    <p class="element"><a href="javascript:;" class="btn btn-primary btn-large right">Demo &raquo;</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <br><br>
        <h2 class="text-center">Microweber is a powerfull, UI Friendly, Content Management System, <br>with rich PHP and JavaScript API.</h2>
    </div>
    <div class="container">
        <h2 class="section-title">
            <hr class="left visible-desktop">
            <span><?php _e("Latest Posts"); ?></span>
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
            <span><?php _e("Latest Products"); ?></span>
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




<div class="edit" style="height: 100px;">



</div>





<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
