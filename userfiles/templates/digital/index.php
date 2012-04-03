<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<div id="container">
  <div id="content">
    <div class="wrapper">
      <div id="hb">
        <?php /*
        <form method="post" action="#" class="TheForm Home_Form">
          <samp  style="left: 628px; top: 90px;">
          <input class="required" type="text" name="name" default="Your Name" value="Your Name">
          </samp> <samp  style="left: 628px; top: 143px;">
          <input class="required" type="text" name="email" default="Your E-mail" value="Your E-mail">
          </samp> <samp  style="left: 629px; top: 195px;">
          <input class="required" type="text" name="phone" default="Your Phone" value="Your Phone">
          </samp> <samp  style="left: 631px; top: 248px;">
          <input class="required" type="text" name="address" default="Address" value="Address">
          </samp>
          <input type="submit" class="xhidden" />
          <a href="#" class="action-submit">&nbsp;</a>
        </form>
        */ ?>
        <form method="post" action="#" class="TheForm homeform2">
          <span class="field"> <span>
          <input class="required" type="text" name="name" default="Your Name" value="Your Name">
          </span> </span> <span class="field"><span>
          <input class="required" type="text" name="email" default="Your E-mail" value="Your E-mail">
          </span></span> <span class="field"><span>
          <input class="required" type="text" name="phone" default="Your Phone" value="Your Phone">
          </span></span> <span class="field"><span>
          <input class="required" type="text" name="city" default="City" style="width:210px;" value="City">
          </span></span> <span class="field"> <span>
          <input type="text" class="required" name="zip_postal_code" default="Zip code"  style="width:76px;" />
          </span> </span> <span class="field"><span>
          <input type="text" class="required" name="street_address"  default="Street Address" />
          </span> </span>
          <input type="submit" class="xhidden" />
          <input type="hidden"   name="post_name"  value="home" />
          <a href="#" class="action-submit hsubmit1">Show me the best offer now</a>
        </form>
      </div>
    </div>
    <div class="c">&nbsp;</div>
    <div id="greyC">
      <div class="wrapper">
        <div class="c" style="height:24px;">&nbsp;</div>
        <div class="b">
          <div class="bt">&nbsp;</div>
          <div class="bm">
            <div id="slider">
              <div id="slider_holder">
                <div class="slide"><a href="<?php //echo get_page_link(34); ?>"><img border="0" src="<? print TEMPLATE_URL ?>images/hm.jpg" alt="" /></a></div>
                <div class="slide"><a href="<?php //echo get_page_link(40); ?>"><img border="0" src="<? print TEMPLATE_URL ?>images/banner_DIRECTV.jpg" alt="" /></a></div>
                <div class="slide"><a href="<?php //echo get_page_link(49); ?>"><img border="0" src="<? print TEMPLATE_URL ?>images/banner_DIRECTV_FOR_BUSINESS.jpg" alt="" /></a></div>
                <div class="slide"><a href="<?php //echo get_page_link(46); ?>"><img border="0" src="<? print TEMPLATE_URL ?>images/banner_Dish_network.jpg" alt="" /></a></div>
                <div class="slide"><a href="<?php //echo get_page_link(57); ?>"><img border="0" src="<? print TEMPLATE_URL ?>images/banner_WILD_BLUE_INTERNET.jpg" alt=""  /></a></div>
              </div>
            </div>
            <div id="ctrl"></div>
            <script type="text/javascript">

            $(document).ready(function(){
$("#slider").hover(function(){
    $(this).addClass("slider_content_hover")
  }, function(){
    $(this).removeClass("slider_content_hover")
  });

  setInterval(function(){

    var curr = $("#ctrl label.active");


    if($(".slider_content_hover").length==0){
      if(curr.next("label").length>0){
        curr.next().click();

      }
      else{
         $("#ctrl label:first").click()
      }
    }


  }, 3000);
            });

            </script>
            <div class="c" style="height:20px;">&nbsp;</div>
            <table cellpadding="0" cellspacing="0" id="xboxes">
              <tr>
                <td class="td1"><img src="<? print TEMPLATE_URL ?>images/td1.jpg"  />
                  <h2>TV</h2>
                  <p>Enjoy high quality picture with
                    our HD TV packages. </p>
                  <ul>
                    <li><a href="<?php //echo get_page_link(40); ?>">DirecTV</a></li>
                    <li><a href="<?php //echo get_page_link(49); ?>">DirecTV For Business</a></li>
                    <li><a href="<?php //echo get_page_link(46); ?>">Dish Network</a></li>
                    <li><a href="<?php //echo get_page_link(34); ?>">Comcast / Xfinity</a></li>
                  </ul></td>
                <td class="td2"><img src="<? print TEMPLATE_URL ?>images/td2.jpg"  />
                  <h2>Internet</h2>
                  <p>Always be connected with high speed internet</p>
                  <ul>
                    <li><a href="<?php //echo get_page_link(57); ?>">Wild Blue Internet</a></li>
                    <li><a href="<?php //echo get_page_link(34); ?>">Xfinity Internet</a></li>
                  </ul></td>
                <td class="td3"><img src="<? print TEMPLATE_URL ?>images/td3.jpg"  />
                  <h2>Phone</h2>
                  <p>Talk more pay less</p>
                  <ul>
                    <li><a href="<?php //echo get_page_link(34); ?>">Comcast / Xfinity</a></li>
                  </ul></td>
              </tr>
            </table>
          </div>
          <div class="bb">&nbsp;</div>
        </div>
        <div class="c" style="height:20px;">&nbsp;</div>
        <a href="<?php //echo get_page_link(65); ?>"> <img src="<? print TEMPLATE_URL ?>images/ip.jpg" alt="" /> </a>
        <div class="c" style="height:25px;">&nbsp;</div>
      </div>
    </div>
  </div>
  <!-- #content -->
</div>
<div>
  <div id="flashid" style="z-index:999;width:275px; height:315px;">
    <script type="text/javascript" language="JavaScript">
			e = true;
			document.write('<object data="<? print TEMPLATE_URL ?>ed_video/player.swf" width="100%" height="100%" type="application/x-shockwave-flash">');
			document.write('<param name="movie" value="<? print TEMPLATE_URL ?>ed_video/player.swf" />');
			document.write('<param name="wmode" value="transparent" />');			
			document.write('<param name="quality" value="high" />');			
			document.write('<param name="align" value="right" />');			
			document.write('<param name="LOOP" value="false" />');			
			document.write('<param name="FlashVars" value="allowResize='+e+'" />');
			document.write('Flash Movie With Resizing Content');
			document.write('</object>');
		</script>
    <noscript>
    Javascript must be enabled to view Flash movie
    </noscript>
  </div>
</div>
<!-- #container -->
<?php get_footer(); ?>
