
               <? include "header.php"; ?>

           <? include "sidebar.php"; ?>


           <div id="main">

                <div id="product_image">
                    <a class="product product_active" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>3MM TITANIUM HYDRO and ZIP BOOT</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>


                    <script type="text/javascript">

                    $(document).ready(function(){
                       $("#products_slide .slide_engine li").multiWrap(2, '<ul></ul>');
                        slide.init({
                            elem:"#products_slide",
                            items:"ul",
                            step:3
                        });

                        slide.init({
                            elem:"#related_slide",
                            items:".product_item",
                            step:3
                        });




                        $("#products_slide li a").click(function(){
                            var href = $(this).attr("href");
                            var rel = $(this).attr("rel");

                            $("#product_image .product").attr("href", rel);
                            $("#product_image .product .img").css("backgroundImage", "url(" + href + ")");


                            return false;
                        });
                        $("#product_image a.product").modal("single")

                    });

                    </script>
                   <div class="c" style="padding-bottom: 10px;">&nbsp;</div>


                    <div class="photoslider" id="products_slide">
                       <div class="photoslider_holder">
                          <div class="slide_engine">
                             <li>
                                <a href="http://wickeddiving.com/images/marine-life-large/similan-diving3-2.jpg" rel="http://www.bigbluetech.net/big-blue-tech-news/wp-content/uploads/2009/03/night-dive-1.jpg" style="background-image: url(img/_demo_slide1.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide2.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide1.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide2.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide1.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide2.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide1.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide2.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>

                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide1.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide2.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide1.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide2.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>
                             <li>
                                <a href="#" style="background-image: url(img/_demo_slide3.jpg)">

                                </a>
                             </li>



                          </div>
                       </div>
                       <span class="slide_left product_slide_left">Back</span>
                       <span class="slide_right product_slide_right">More</span>
                     </div>









                </div>


                <div id="product_main">
                    <h3 class="title nopadding">DISCOVERY III FLASHLIGHT</h3>
                    <br /><br />
                    Description:<br />
                    <div class="richtext">
                        <p>5 watt LED flashlight w/Philips LUMILEDS LED lights
Withstand water pressure up to 100 meters in depth
Lasts up to 24 hours of continuous use
Powered by 4 C Alkaline batteries
Heat dissipating design helps extend the life of the batteries and LED emitter
One hand operation thumb switch w/lock switch in off position
Soft rubber over-molding around the handle for slip-free use
Triple seal soft rubber over-molding prevents water leakage
Packaged in clamshell
</p>

<br />
Model: LT06

<br />Choose color: &nbsp;<input type="radio" /> Yellow

<div class="c" style="padding-bottom: 20px;">&nbsp;</div>

<h4 class="title pprice nopadding nomargin">Price: $85</h4>

<div class="borc">
<a href="#" class="buy">Buy now</a>
  <div>OR</div>
<a href="#" class="con_tag">Contact us</a>
</div>



                    </div>

                </div>
                <div class="c"></div>
<br /><br />

<h2 class="title">Similar products</h2>
 <br />
























        <div class="photoslider" id="related_slide">
           <div class="photoslider_holder" style="width: 710px">
              <div class="slide_engine">
                <div class="product_item product_item_slide">
                    <a class="product" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>Hood/Vest</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>
                    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
                    <a href="#" class="btnH left">Read more</a>
                    <a href="#" class="lbuy right">Buy now</a>
                </div>
                <div class="product_item product_item_slide">
                    <a class="product" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>Hood/Vest</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>
                    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
                    <a href="#" class="btnH left">Read more</a>
                    <a href="#" class="lbuy right">Buy now</a>
                </div>
                <div class="product_item product_item_slide">
                    <a class="product" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>Hood/Vest</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>
                    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
                    <a href="#" class="btnH left">Read more</a>
                    <a href="#" class="lbuy right">Buy now</a>
                </div>
                <div class="product_item product_item_slide">
                    <a class="product" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>Hood/Vest</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>
                    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
                    <a href="#" class="btnH left">Read more</a>
                    <a href="#" class="lbuy right">Buy now</a>
                </div>
                <div class="product_item product_item_slide">
                    <a class="product" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>Hood/Vest</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>
                    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
                    <a href="#" class="btnH left">Read more</a>
                    <a href="#" class="lbuy right">Buy now</a>
                </div>
                <div class="product_item product_item_slide">
                    <a class="product" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>Hood/Vest</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>
                    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
                    <a href="#" class="btnH left">Read more</a>
                    <a href="#" class="lbuy right">Buy now</a>
                </div>
                <div class="product_item product_item_slide">
                    <a class="product" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>Hood/Vest</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>
                    <div class="c" style=" padding-bottom: 5px;">&nbsp;</div>
                    <a href="#" class="btnH left">Read more</a>
                    <a href="#" class="lbuy right">Buy now</a>
                </div>

              </div>
           </div>
           <span class="slide_left product_slide_left">Back</span>
           <span class="slide_right product_slide_right">More</span>
        </div>































           </div>

               <? include "footer.php"; ?>
