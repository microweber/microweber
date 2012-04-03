<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Intro</title>
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="http://omnitom.com/intro.css" />
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $("#nav li").addClass("parent");
      $("#nav li li").removeClass("parent");
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

      //nav

      $("#nav li.parent").hover(function(){
            $("#nav li.parent").find("div.sub").hide();
           $(this).find("div.sub").show();
           $(this).css("height", "200px");
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
<body>
<div id="container">
  <div id="introImages">
    <script type="text/javascript">
             function getFlash(){
             $("#introImages embed").remove();
              var embed = document.createElement('embed');
                  embed.setAttribute("type", "application/x-shockwave-flash");
                  embed.setAttribute("src", "imagerotator.swf");
                  embed.setAttribute("id", "flashIntro");
                  embed.setAttribute("width", "100%");
                  embed.setAttribute("height", "100%");
                  embed.setAttribute("wmode", "transparent");
                  embed.setAttribute("allowscriptaccess", "always");
                  embed.setAttribute("allowfullscreen", "false");
                  embed.setAttribute("flashvars", "file=<?php print urlencode('http://omnitom.com/intro.xml') ?>?transition=fade&shownavigation=false&overstretch=true&shuffle=false&rotatetime=3");
             document.getElementById('introImages').appendChild(embed);
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
        <li><a href="about">Omnitom</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <li   class="active"    ><a title="Behind the brand" name="Behind the brand" href="http://omnitom.com/about">Behind the brand</a></li>
              <li   ><a title="Omnitom Atelier" name="Omnitom Atelier" href="http://omnitom.com/omnitom-atelier">Omnitom Atelier</a></li>
              <li   ><a title="News" name="News" href="http://omnitom.com/news">News</a></li>
              <li   ><a title="Press" name="Press" href="http://omnitom.com/press-kit">Press</a></li>
              <li   ><a title="Where to find us" name="Where to find us" href="http://omnitom.com/where-to-find-omnitom">Where to find us</a></li>
              <li   ><a title="Terms and conditions" name="Terms and conditions" href="http://omnitom.com/terms-and-conditions">Terms and conditions</a></li>
            </ul>
          </div>
        </li>
        <li><a href="omnitom-world">Omnitom World</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <li   ><a title="Omnitom World" name="Omnitom World" href="http://omnitom.com/omnitom-world">Omnitom World</a></li>
              <li   ><a title="Friends" name="Friends" href="http://omnitom.com/friends">Friends</a></li>
              <li   ><a title="Charity" name="Charity" href="http://omnitom.com/charity">Charity</a></li>
              <li   ><a title="Yoga off the Mat" name="Yoga off the Mat" href="http://omnitom.com/yoga-off-the-mat">Yoga off the Mat</a></li>
            </ul>
          </div>
        </li>
        <li><a href="contacts">Get in touch</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <li   ><a title="Contact Omnitom" name="Contact Omnitom" href="http://omnitom.com/contacts">Contact Omnitom</a></li>
              <li   ><a title="Become a distributor" name="Become a distributor" href="http://omnitom.com/become-a-distributor">Become a distributor</a></li>
            </ul>
          </div>
        </li>
        <li><a href="shop">On-line Shop</a>
          <div class="sub">
            <div class="subbg"></div>
            <ul>
              <li   ><a title="Shop" name="Shop" href="http://omnitom.com/shop">Shop</a></li>
              <li   ><a title="Sale" name="Sale" href="http://omnitom.com/sale">Sale</a></li>
            </ul>
          </div>
        </li>
        <li><a href="collections">Collections</a>
          <!--<div class="sub">
                        <div class="subbg"></div>
                        <ul>
                          <li><a href="#">Prana Power</a></li>
                          <li><a href="#">Embrace</a></li>
                          <li><a href="#">Chakra Chick</a></li>
                          <li><a href="#">Info</a></li>
                        </ul>
                    </div>-->
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
<a id="ictn" href="http://omnitom.com/news/omnitom-will-be-available-in-uk">
<!-- -->
</a>
</body>
</html>