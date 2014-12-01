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
<style>
#bgimage,
#bgimagemaster,
#bgimagecontent{
  height: 460px;
}
#header{
  height:530px;
}

#master-header{
  font-size: 60px;
}

</style>

<div class="container">
  <div class="edit" field="content" rel="content">
    <div class="box-container latest-items">
      <div class="quote">
        <h2 style="padding-bottom: 5px;margin-bottom: 0;">Popular destinations</h2>

        <hr>
      </div>
      <div style="max-width: 700px; margin: auto;"><module type="shop/products" limit="3" hide-paging="true" data-show="thumbnail,title,add_to_cart,price" template="3columns"></div>




      <div class="quote">


    <h2>About us</h2>

     <hr style="margin-top: 0;">

    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        Maecenas dapibus, massa commodo rutrum cursus, eros felis semper nulla, non tristique dui urna eget est.
        Nullam tempus feugiat lacinia. Fusce tincidunt diam a scelerisque ullamcorper. Integer sit amet urna quis
        purus tincidunt consequat a nec magna.
    </p>

     <br><br>

    </div>

<?php /*
    <div class="quote">
        <h2 style="padding-bottom: 5px;margin-bottom: 0;">Latest Posts</h2>
        <p class="text-center"><small>Fusce tincidunt diam a scelerisque ullamcorper.</small></p>
        <hr>
      </div>

    <module type="posts" limit="3" hide-paging="true" data-show="thumbnail,title,add_to_cart,price" template="columns">
*/ ?>


    </div>






  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>
