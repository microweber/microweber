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

<div class="container edit" id="home-top"  rel="page" field="content">
  <div class="mw-row clearfix">
    <div class="mw-col" style="width: 50%">
      <div class="mw-col-container">
        <module type="pictures" content-id="<?php print PAGE_ID; ?>" template="bootstrap_carousel"  />
      </div>
    </div>
    <div class="mw-col" style="width: 50%">
      <div class="mw-col-container" id="mw-welcome">
         
          <h2 class="element">Welcome to Microweber</h2>
          <p class="element">This is the default theme of (MW). </p>
          <br>
          <h4 class="element">You are able to create your own Website, Blog, Online Shop or anything you need, for free.</h4>
          <br>
          <h4 class="element" style="font-size: 16px;"><strong>Discover more by using drag and drop technology and Make Web!</strong></h4>
          <br>
          <module type="btn btn-default" button_size="btn btn-default-large" class="pull-right">
      </div>
    </div>
  </div>
  <div class="container">
    <div class="element">
      <br><br>
      <h3 align="center" class="symbol">Powerful &nbsp;&amp;&nbsp; User Friendly &nbsp;Content Management System &nbsp;of &nbsp;New Generation</h3>
      <h4 align="center">with rich PHP and JavaScript API</h4>
    </div>
  </div>
  <div class="container">
    <div class="mw-row">
      <div class="mw-col" style="width:33.33%">
          <div class="mw-col-container"><div class="element"><hr class="visible-desktop column-hr"></div></div>
      </div>
      <div class="mw-col" style="width:33.33%">
          <div class="mw-col-container"><h2 align="center"><?php _e("Latest Posts"); ?></h2></div>
      </div>
      <div class="mw-col" style="width:33.33%">
          <div class="mw-col-container"><div class="element"><hr class="visible-desktop column-hr"></div></div>
      </div>
    </div>


<?php

    /*

         Parameters(attributes) for "Posts" Module:


             * template - Name of the template.
               Templates provided from Microweber:
                 - default - loads when no template is specified
                 - 3 columns
                 - 4 columns
                 - sidebar

             * limit - number of posts to show per page. Default is the value specified in the Admin -> Settings (10) .

             * description-length
                - number: Number of symbols you want to show from description. Default: 400

             * title-length
                - number: Number of symbols you want to show from title. Default: 120

             * current_page - usage is for paging
                - number: The number of the page where the posts will appear. Default: 1

             * hide-paging
                - y/n - Default: n

             * data-show:
                 Possible values:
                    - thumbnail,
                    - title,
                    - read_more,
                    - description,
                    - created_at

     */

?>
    <module
          data-type="posts"
          data-limit="3"
          id="home-posts"
          data-description-length="100"
          data-show="thumbnail,title,created_at,read_more,description"
          data-template="columns" />
  </div>


  <div class="container">
    <div class="mw-row">
      <div class="mw-col" style="width:33.33%">
          <div class="mw-col-container"><div class="element"><hr class="visible-desktop column-hr"></div></div>
      </div>
      <div class="mw-col" style="width:33.33%">
          <div class="mw-col-container"><h2 align="center"><?php _e("Latest Products"); ?></h2></div>
      </div>
      <div class="mw-col" style="width:33.33%">
          <div class="mw-col-container"><div class="element"><hr class="visible-desktop column-hr"></div></div>
      </div>
    </div>

 <?php

    /*

         Parameters(attributes) for "Products" Module:


             * template - Name of the template.
               Templates provided from Microweber:
                 - default - loads when no template is specified
                 - 3 columns
                 - 4 columns
                 - sidebar

             * limit - number of posts to show per page. Default is the value specified in the Admin -> Settings (10) .

             * description-length
                - number: Number of symbols you want to show from description. Default: 400

             * title-length
                - number: Number of symbols you want to show from title. Default: 120

             * current_page - usage is for paging
                - number: The number of the page where the posts will appear. Default: 1

             * hide-paging
                - y/n - Default: n

             * data-show:
                 Possible values:
                    - thumbnail,
                    - title,
                    - read_more,
                    - description,
                    - created_at

     */

?>

    <module
          data-type="shop/products"
          data-limit="3"
          id="home-products"
          data-description-length="150"
          data-show="thumbnail,title,add_to_cart,description,price"
          data-template="columns" />



  </div>
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
