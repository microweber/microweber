<?php

/*

  type: layout
  content_type: static
  name: Home
  position: 11
  description: Home layout

*/

?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>



<style>
.magic-slider, .magic-slider .mw-ui-row-nodrop, .magic-slider .magic-rotator-slide{
  min-height: 650px !important;
}

</style>

  <div class="edit" rel="content" field="content">
  <module type="magicslider" id="homeslider_1">
  <div class="mw-wrapper content-holder">
        <div class="mw-row heading-row">
            <div class="mw-col">
              <div class="mw-col-container">
                <hr>
              </div>
            </div>
            <div class="mw-col">
              <div class="mw-col-container">
                <h2>Women Categories</h2>
              </div>
            </div>
            <div class="mw-col">
              <div class="mw-col-container">
                 <hr>
              </div>
            </div>
        </div>


        <div class="item-box pad">
            <module type="content_categories" template="big"id="home_category_1" />
        </div>
        <p class="element"><br></p>
        </div>
        <module type="magicslider" id="homeslider_2">
        <div class="mw-wrapper content-holder">
        <p class="element"><br></p>
        <div class="mw-row heading-row">
            <div class="mw-col">
              <div class="mw-col-container">
                <hr>
              </div>
            </div>
            <div class="mw-col">
              <div class="mw-col-container">
                <h2>Men Categories</h2>
              </div>
            </div>
            <div class="mw-col">
              <div class="mw-col-container">
                 <hr>
              </div>
            </div>
        </div>
        <div class="item-box pad"><module type="content_categories" template="big" id="home_category_2" /></div>
        <p class="element"><br></p>

        <div class="mw-row text-center">
              <div class="mw-col" style="width: 33.333%">
                <div class="mw-col-container">
                    <span style="display: inline-block; padding: 12px; opacity: 0.6; min-width: 100px; margin-bottom: 12px; font-size: 62px; Xtext-shadow: 1px 1px 0px rgba(0, 0, 0, 0.48); border-radius: 6px;">
                        <span class="mw-icon-truck"></span>
                    </span>
                    <h4>Free Shipping</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nisi augue, tempus sed pellentesque in, posuere id tortor. Vestibulum ultrices nunc pretium.</p>
                </div>
              </div>
              <div class="mw-col" style="width: 33.333%">
                <div class="mw-col-container">
                    <span style="display: inline-block; padding: 12px;  opacity: 0.6;  min-width: 100px; margin-bottom: 12px; font-size: 62px; Xtext-shadow: 1px 1px 0px rgba(0, 0, 0, 0.48); border-radius: 6px;">
                        <span class="mw-icon-users"></span>
                    </span>

                    <h4>Customer service</h4>
                    <p>Phasellus in dignissim urna, sed ornare nisi. Nam molestie velit at imperdiet aliquam. Etiam sit amet erat vitae ex maximus pulvinar.</p>
                </div>
              </div>
              <div class="mw-col" style="width: 33.333%">
                <div class="mw-col-container">
                    <span style="display: inline-block; padding: 12px;  opacity: 0.6;  min-width: 100px; margin-bottom: 12px; font-size: 62px; Xtext-shadow: 1px 1px 0px rgba(0, 0, 0, 0.48); border-radius: 6px;">
                        <span class="mw-icon-product"></span>
                    </span>
                    <h4>Shop Now</h4>
                    <p>Vivamus venenatis sem nulla, sed ullamcorper massa posuere at. Donec quis sapien semper nunc lobortis feugiat. Curabitur accumsan, nisi et ultrices.</p>
                </div>
              </div>
            </div>

        <p class="element"><br></p>
        <div class="mw-row heading-row">
            <div class="mw-col">
              <div class="mw-col-container">
                <hr>
              </div>
            </div>
            <div class="mw-col">
              <div class="mw-col-container">
                <h2>Latest-products</h2>
              </div>
            </div>
            <div class="mw-col">
              <div class="mw-col-container">
                 <hr>
              </div>
            </div>
        </div>
            <div class="item-box pad"><module type="shop/products" template="horizontal_slider.php" hide_paging="true" data-show="thumbnail,title,price" /></div>
        </div>
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
