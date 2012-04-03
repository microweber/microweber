<?php include 'includes/header.php' ?>
 <?php include(ACTIVE_TEMPLATE_DIR.'shop_side_nav.php') ;  ?>
      
      
      
      
      <div id="main">
        <div id="product-profile" class="wrap">
          <h2>Women T-shirt item</h2>
          <p class="product-profile-description"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean interdum magna eget diam fermentum facilisis. Sed pretium, arcu in eleifend fringilla, purus dolor pharetra est, ut accumsan massa lectus quis risus. Sed vitae orci et ante pulvinar tincidunt. Quisque luctus iaculis nisl, id molestie diam euismod a. Morbi ut aliquet risus. Suspendisse a scelerisque lectus. Phasellus sit amet tristique dolor. Aenean euismod pellentesque libero quis dictum. Nunc vulputate varius magna, non molestie diam sollicitudin ac. </p>
          <div id="main_image_holder" class="wrap">
            <script type="text/javascript" src="jquery.magnifier.js"></script>
            <script type="text/javascript">
                                $(document).ready(function(){




                                     $("#main_magnify").magnify({
                                        link: false
                                     });

$("#dio-sensor").click(function(){
    $("#overlay").show();
    var mImg = $(".zoom").attr("href");
    var mImage = new Image();
    document.getElementById('modal').appendChild(mImage);
    mImage.onload = function(){
         var mWidth = this.offsetWidth;
         var mHeight = this.offsetHeight;
         $("#modal").css("top", ($("#main_magnify img").offset().top + 'px'));
         $("#modal").css("left", ($("#main_magnify img").offset().left + 'px'));
         $("#modal").css("width", ($("#main_magnify img").width() + 'px'));
         $("#modal").css("height", ($("#main_magnify img").height() + 'px'));
         var top_final_place = $(window).scrollTop() + $(window).height()/2 - mHeight/2;
         $("#modal").css("visibility", "visible");
         $("#modal").animate({width:mWidth, height:mHeight, left:$(window).width()/2-mWidth/2, top: top_final_place, opacity:1}, 'slow', function(){
            $("#modal").append(app_close_append);
            $("#modal").css("border", "solid 4px #3D638D");
         });
    }
    mImage.src=mImg;
    return false;
});
$("#overlay, .closeModal").click(function(){
       $("#modal").css("visibility", "hidden").empty().removeAttr("style");$("#overlay").hide();
});


$("#main_image_holder ul a:first").click();

                                });

                                function close_modal(){
                                    $("#modal").css("visibility", "hidden").empty().removeAttr("style");$("#overlay").hide();
                                }
                            </script>
            <div id="main_image" class="box"> <a href="img/a.jpg" id="main_magnify" class="zoom"> <img src="img/b.jpg" alt="" height="235" /> </a> </div>
            <ul>
              <li><a href="img/b.jpg" rel="img/a.jpg"
                                style="background-image:url(http://images.ethicalsuperstore.com/images/resize75/Gossypium%20Yogo%20Trousers.jpg)"></a></li>
              <li><a href="img/z2.jpg" rel="img/z1.jpg"
                                style="background-image:url(img/z3.jpg)"></a></li>
              <li><a href="img/dz2.jpg" rel="img/dz1.jpg"
                                style="background-image:url(img/dz3.jpg)"></a></li>
            </ul>
          </div>
          <!-- /main_image_holder -->
          <div id="product_profile_details" class="wrap">
            <div class="dtitled left" style="margin-right:45px"> <span class="dropdown_title">Colors:</span>
              <div class="DropDown DropDownAlpha DropDownColors DropDownGray"> <span></span>
                <ul style="width:215px">
                  <li title="purple" class="active"><s style="background:purple"></s><em>Purple</em></li>
                  <li title="green"><s style="background:green"></s><em>Green</em></li>
                  <li title="yellow"><s style="background:yellow"></s><em>Green</em></li>
                  <li title="red"><s style="background:red"></s><em>Green</em></li>
                </ul>
                <input type="hidden" />
              </div>
            </div>
            <div class="dtitled left" style="margin-right:45px"> <span class="dropdown_title">Size:<em>(size-chart)</em></span>
              <div class="DropDown DropDownAlpha DropDownGray" style="width:100px"> <span>Select Size</span>
                <ul style="width:100px">
                  <li title="M" class="active">M</li>
                  <li title="L">L</li>
                  <li title="XL">XL</li>
                  <li title="XXL">XXL</li>
                </ul>
                <input type="hidden" />
              </div>
            </div>
            <div class="dtitled left" style="margin-right:0px"> <span class="dropdown_title">QTY:</span>
              <div class="DropDown DropDownAlpha DropDownColors DropDownGray" style="width:50px" id="qty"> <span></span>
                <ul style="width:50px">
                  <li title="1" class="active"><em>1</em></li>
                  <li title="2"><em>2</em></li>
                  <li title="3"><em>3</em></li>
                  <li title="4"><em>4</em></li>
                  <li title="5"><em>5</em></li>
                  <li title="6"><em>6</em></li>
                  <li title="7"><em>7</em></li>
                  <li title="8"><em>8</em></li>
                  <li title="9"><em>9</em></li>
                  <li title="10"><em>10</em></li>
                </ul>
                <input type="hidden" />
              </div>
            </div>
            <div class="clear">
              <!--  -->
            </div>
            <div class="availability"> Availability: In the stock <br />
              <br />
              This product is ready to ship within
              1-2 days. </div>
            <div class="cart-price"> <span id="price" title="56.90">$<strong>56.90</strong></span>
              <div class="clear"></div>
              <a href="#" class="big_btn right"><span>Add to cart</span></a> </div>
            <div class="clear">
              <!--  -->
            </div>
            <div class="box spreadsheet" style="">
              <h3 class="check_title">Spreadshirt Guarantee</h3>
              <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </p>
            </div>
          </div>
          <!-- /product_profile_details -->
        </div>
        <!-- /product-profile -->
        <div class="related wrap">
          <h2>Related Products</h2>
          <div class="item_wrap">
            <div class="box" style="background-image:url(img/ritem.jpg)"> <a href="#"></a> </div>
            <em class="related-item-name">Item Name</em> <em class="related-item-price"><strong>$35</strong></em> </div>
          <div class="item_wrap">
            <div class="box" style="background-image:url(img/ritem.jpg)"> <a href="#"></a> </div>
            <em class="related-item-name">Item Name</em> <em class="related-item-price"><strong>$35</strong></em> </div>
          <div class="item_wrap">
            <div class="box" style="background-image:url(img/ritem.jpg)"> <a href="#"></a> </div>
            <em class="related-item-name">Item Name</em> <em class="related-item-price"><strong>$35</strong></em> </div>
          <div class="item_wrap">
            <div class="box" style="background-image:url(img/ritem.jpg)"> <a href="#"></a> </div>
            <em class="related-item-name">Item Name</em> <em class="related-item-price"><strong>$35</strong></em> </div>
        </div>
        <!-- /related -->
      </div>
      <!-- /main -->
      <?php include 'includes/footer.php' ?>
