<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>

<meta name="googlebot" content="index,follow" />

<meta name="robots" content="index,follow" />

<meta http-equiv="imagetoolbar" content="no" />

<meta name="rating" content="GENERAL" />

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<link rel="start" href="<?php print site_url(); ?>" />

<link rel="home" type="text/html" href="<?php print site_url(); ?>"  />

<link rel="index" type="text/html" href="<?php print site_url(); ?>" />

<meta name="generator" content="Microweber" />

<title>{content_meta_title}</title>

<meta name="keywords" content="{content_meta_keywords}" />

<meta name="description" content="{content_meta_description}" />

<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="http://omnitom.com/intro.css" />
<?php echo '<!--[if IE]><?import namespace="v" implementation="#default#VML" ?><![endif]-->' ?>
<script type="text/javascript" src="http://omnitom.com/facelift/flir.js"></script>
<script type="text/javascript" src="jquery.min.js"></script>
<!--<script type="text/javascript" src="http://google.com/jsapi"></script>
<script type="text/javascript">google.load("jqueryui", "1.7.1");</script>-->

<script type="text/javascript">
    function circles_fix(){
        if($(window).width()<1250){
          $(".circle").css("top", "102px");
        }
        else{
            $(".circle").css("top", "25px");
        }
    }

    $(document).ready(function(){
      $("#ht_wrapper").css("marginLeft", -($("#tree").width() + $("#hoe").width())/2);

        $("#tree_injector").css({
            "width":$(window).width(),
            "height":$(window).height()

        });

       circles_fix()

      /*$(window).load(function(){
           $("#tree").animate({opacity:1}, 1200, function(){
               $("#hoe").animate({opacity:1}, 1200, function(){
                  $("body").addClass("tree_done");
                  getFlash();
                  $("#tree_injector").fadeOut(1200);

               })
           })
      }) */



     $(window).load(function(){circles_fix()})
     $(window).resize(function(){circles_fix()})

      var dropdownbg = new Image();
      dropdownbg.src='http://omnitom.com/img/intro/dropdown.png';


    })



  </script>



<script type="text/javascript">
    $(document).ready(function(){

    FLIR.init({ path: 'http://omnitom.com/facelift/' });
    var nav_style = new FLIRStyle({ cFont:'bryant', cColor:'000000', cSize:'14px' });


      //$(".circle").draggable();
      $("#nav li").addClass("parent");
      $("#nav li a").addClass("parentA");
      $("#nav li li").removeClass("parent");
      $("#nav li li a").removeClass("parentA");
      function normalize(){
         window_height = $(window).height();
         window_width = $(window).width();
         $("#introImages").css({"width":window_width, "height":window_height});
         //$("#flashIntro").css({"width":window_width, "height":window_height});
         //alert(window_height)
      }
      normalize();
      $(window).load(function(){normalize();})
      $(window).resize(function(){normalize()});



    $("#nav .parentA").each( function() {
         FLIR.replace(this,  nav_style);
    });


      //nav

      $("#nav li.parent").hover(function(){
            $("#nav li.parent").find("div.sub").hide();
           $(this).find("div.sub").show();
           $(this).css("height", "300px");
      }, function(){
          $(this).find("div.sub").hide();
          $("#nav li.parent").css("height", "auto");
      })

      $("#nav").hover(function(){}, function(){
           $(this).find("div.sub").hide();
           $("#nav li.parent").css("height", "auto");
      })


      var pimg22 = new Image();
      pimg22.src="http://omnitom.com/userfiles/templates/omnitom/img/ictnh.png";
    });

  </script>
</head>
<body class="tree_done">
<div id="container">
  <div id="introImages">
    <script type="text/javascript">
             function getFlash(){
               if($("body").hasClass("tree_done")){
                   $("#introImages embed").remove();
                    var embed = document.createElement('embed');
                        embed.setAttribute("type", "application/x-shockwave-flash");
                        embed.setAttribute("src", "http://omnitom.com/imagerotator.swf");
                        embed.setAttribute("id", "flashIntro");
                        embed.setAttribute("width", "100%");
                        embed.setAttribute("height", "100%");
                        embed.setAttribute("wmode", "transparent");
                        embed.setAttribute("allowscriptaccess", "always");
                        embed.setAttribute("allowfullscreen", "false");
                        embed.setAttribute("flashvars", "file=<?php print urlencode('http://omnitom.com/intro.xml') ?>&transition=fluids&shownavigation=false&overstretch=true&shuffle=false&rotatetime=3");
                        document.getElementById('introImages').appendChild(embed);
               }

             //document.getElementById('introImages').innerHTML='<embed type="application/x-shockwave-flash" src="imagerotator.swf" id="flashIntro" width="100%" height="100%" wmode="transparent" allowscriptaccess="always" allowfullscreen="false" flashvars="file=intro.xml&transition=random&shownavigation=false&overstretch=true&shuffle=false"></embed>';
            }
             window.onload = getFlash;
             window.onresize = getFlash;
         </script>
    <!--<embed
      type="application/x-shockwave-flash"
      src="imagerotator.swf"
      id="flashIntro"
      width="100%"
      height="100%"
      wmode="transparent"
      allowscriptaccess="always"
      allowfullscreen="false"
      flashvars="file=intro.xml&transition=random&shownavigation=false&overstretch=true&shuffle=false"
    ></embed>-->
    <!--<div id="treeanimation">
        <img src="img/intro/t.jpg" alt="" />
    </div>-->
    <div id="flashoverlay">&nbsp;</div>
  </div>
  <div id="header">

    <div id="headerbg">&nbsp;</div>
    <div id="headerContent"> <a href="#" class="logo"></a>



      <ul id="nav">

        <li><a href="#">Omnitom</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
               <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_menu');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
            </ul>
          </div>
        </li>


          <li><a href="<?php print $link = CI::model ( 'content' )->getContentURLById(31)  ?>">Collections</a>


          <li><a href="shop">Online Boutique</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
               <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('on_line_shop_menu');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
            </ul>
          </div>
        </li>


        <li><a href="#">Omnitom World</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('omnitom_world');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
            </ul>
          </div>
        </li>
        <li><a href="contacts">Get in touch</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <?php $menu_items = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('get_in_touch');	?>
          <?php foreach($menu_items as $item): ?>
          <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
          <?php endforeach ;  ?>
            </ul>
          </div>
        </li>



        </li>




      </ul>

    </div>
  </div>
</div>
<!-- /#container -->
<div id="ctn">
  <!--  -->
</div>
<!--<a id="skip" href="#">Skip</a>-->
<!--<a id="ictn" href="http://omnitom.com/news/omnitom-will-be-available-in-uk"></a>-->
<div class="circle circle3" style="cursor: pointer;display:none" onclick="window.location.href='http://www.omnitom.com/are-you-a-teacher'">

 <div class="circlebg">&nbsp;</div>
 <div class="circlecontent" style="cursor: pointer;font-size:15px"  onclick="window.location.href='http://www.omnitom.com/are-you-a-teacher'">

<br/>Are you a teacher?
    <!-- Съдържание за Кръгче 1-->

 </div>
</div>
<div class="circle circle2" style="cursor: pointer;display" onclick="window.location.href='http://www.omnitom.com/shop'">
  <div class="circlebg">&nbsp;</div>
  <div class="circlecontent" style="cursor: pointer;font-size:15px"  onclick="window.location.href='http://www.omnitom.com/shop'">
<br/>50% off all Embrace tops
<br/> and bottoms*

      <!-- Съдържание за Кръгче 2-->

  </div>
</div>

<div class="circle circle1" style="cursor: pointer;display" onclick="window.location.href='http://www.omnitom.com/shop'">
  <div class="circlebg">&nbsp;</div>
  <div class="circlecontent" style="cursor: pointer;font-size:15px"  onclick="window.location.href='http://www.omnitom.com/news'">
<br/>  Free shipping worldwide*


       <!-- Съдържание за Кръгче 3-->

 </div>
</div>


<!--<div id="coming-soon" align="center">The 2009/2010 collection will be ready to ship on 21st of September. Pre-order now!</div>-->
<?php include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>

<!--<div id="tree_injector">
   <div id="ht_wrapper">
       <img src="http://omnitom.com/userfiles/templates/omnitom/img/intro/tree_intro.jpg" id="tree" />
       <img src="http://omnitom.com/userfiles/templates/omnitom/img/intro/hoe_intro.jpg" id="hoe" />
   </div>
</div>-->
</body>
</html>
