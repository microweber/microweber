<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Master Debate</title>
        <link rel="stylesheet" href="<?php print_the_static_files_url() ; ?>css/style.css" type="text/css" media="screen"  />
        <style type="text/css">
           body{behavior: url(<?php print_the_static_files_url() ; ?>"hover.htc")}
           .ie6 #logo a{behavior: url("<?php print_the_static_files_url() ; ?>iepngfix.htc")}
        </style>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>iepngfix_tilebg.js"></script>
        <script type="text/javascript">
          try {
            document.execCommand("BackgroundImageCache", false, true);
          } catch(err) {}
        </script>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/jquery.js"></script>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/functions.js"></script>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/jquery.dimensions.js"></script>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/ui.mouse.js"></script>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/ui.slider.js"></script>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/jquery.easing.min.js"></script>
        <script type="text/javascript" src="<?php print_the_static_files_url() ; ?>js/jquery.lavalamp.js"></script>
        <script type="text/javascript">
        //Slider Top
            $(document).ready(function() {
                var container = $('#topslider');
                var ul = $('ul', container);

                var itemsWidth = ul.innerWidth() - container.outerWidth();

                $('.slider', container).slider({
                    minValue: 0,
                    maxValue: itemsWidth,
                    handle: '.handle',
                    stop: function (event, ui) {
                        ul.animate({'left' : ui.value* -1}, 500);
                    },
                    slide: function (event, ui) {

                        ul.css('left',  ui.value* -1) , 500;
                    }
                });

          //Slider Content 1

                var container1 = $('#content-slider-1');
                var ul1 = $('ul', container1);

                var itemsWidth1 = ul1.innerWidth() - container1.outerWidth() ;

                $('.slider', container1).slider({
                    minValue: 0,
                    maxValue: itemsWidth1,
                    handle: '.handle',
                    stop: function (event, ui) {
                        ul1.animate({'left' : ui.value * -1.05}, 500);
                    },
                    slide: function (event, ui) {
                        ul1.css('left', ui.value * -1.05);
                    }
                });


                //Slider Content 2

                var container2 = $('#content-slider-2');
                var ul2 = $('ul', container2);

                var itemsWidth2 = ul2.innerWidth() - container2.outerWidth() ;

                $('.slider', container2).slider({
                    minValue: 0,
                    maxValue: itemsWidth2,
                    handle: '.handle',
                    stop: function (event, ui) {
                        ul2.animate({'left' : ui.value * -1.05}, 500);
                    },
                    slide: function (event, ui) {
                        ul2.css('left', ui.value * -1.05);
                    }
                });


            });
        </script>
        <script type="text/javascript">
            //Lava Lamp Settings
          $(document).ready(function() {
              $(function() {
                  $("#nav").lavaLamp({
                      //fx: "backout",
                      fx: "linear",
                      speed: 200,
                      click: function(event, menuItem) {
                          return false;
                          $("#nav li").addClass("active");
                      }
                  });
              });
          });
        </script>
        <script type="text/javascript">
                  $(document).ready(function() {
            		$.tabs("sidebar-tabs");
            	});
        </script>

         <script type="text/javascript">
         $(document).ready(function() {

         });
        </script>


	</head>
	<body>
		<div id="wrapper"><!-- Main Wrapper -->
			<div id="header"><!-- Header -->
				<div class="container">
                    <h1 id="logo"><a href="#" title="Master-Debat.com"></a></h1>
                    <ul id="topnav">
                        <li id="channels-btn"><a href="#">Chanels</a></li>
                        <li id="start-debat-btn"><a href="#">Start Debate</a></li>
                        <li id="about-btn"><a href="#">About</a></li>
                        <li class="signin"><a href="#">Join us</a></li>
                        <li class="signin"><a href="#">Login in</a></li>
                    </ul>
                    <ul id="nav">
                      <li><a href="#">Technology</a></li>
                      <li><a href="#">Politics</a>
                        <div class="subnav">
                            <a href="#">Sub link 1</a>
                            <a href="#">Sub link 2</a>
                            <a href="#">Sub link 3</a>
                            <a href="#">Sub link 4</a>
                        </div>
                      </li>
                      <li><a href="#">Science</a></li>
                      <li><a href="#">Gaming</a></li>
                      <li class="active"><a href="#">Lifestyle</a></li>
                      <li><a href="#">Entertainment</a></li>
                      <li><a href="#">Sports</a></li>
                      <li><a href="#">Offbeat</a></li>
                    </ul><!-- /#nav -->
                </div><!-- /.container -->
			</div><!-- /Header -->
			<div id="content"><!-- Content -->
               <div id="content_shadow_right"><!--  --></div>
               <div class="container">
                <div id="content-bottom-bg">
                 <div class="sliderGallery" id="topslider">
                    <div class="slider">
                         <ul>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                            <li> <a href="#"><strong>d kjghdlghdlfk</strong> <span><small>Bush warning over ...</small></span></a></li>
                         </ul>
                         <div class="slide-border"><div class="handle"></div></div>
                    </div><!-- /.slider -->
                 </div><!-- /.sliderGallery -->
                 <h1 class="page-title">Most popular in category</h1>
                 <div id="content-left">
                    <div class="sliderGallery content-slider" id="content-slider-1">
                        <div class="slider">
                            <h2>Science</h2><a href="#" class="btn">All Videos</a>
                         <div class="slide-border"><div class="handle"></div></div>
                         <ul>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <span class="c"></span>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>


                         </ul>
                    </div><!-- /slider -->
                    </div><!-- /content-slider-1 -->
                    <div class="sliderGallery content-slider" id="content-slider-2">
                        <div class="slider">
                            <h2>Politics</h2><a href="#" class="btn">All Videos</a>
                         <div class="slide-border"><div class="handle"></div></div>
                         <ul>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <span class="c"></span>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>
                            <li><a href="#" style="background-image:url(<?php print_the_static_files_url() ; ?>images/csliderimg.jpg)" class="content-slider-image">Kids' Science</a>
                                <div>
                                    <span class="rate-stars"><!--  --></span><span class="vote-up"><a href="javascript:;">Vote Up</a>212</span>
                                        <span class="hr"><!--  --></span>
                                    <span class="visits">512Visits</span><span class="vote-down"><a href="javascript:;">Vote Down</a>12</span>
                                </div>
                            </li>


                         </ul>
                    </div><!-- /slider -->
                    </div><!-- /content-slider-2 -->

                 </div><!-- /content-left -->
                 <div id="sidebar">
                   <a href="#"><img src="<?php print_the_static_files_url() ; ?>images/bannerhome.jpg" align="right" alt="" /></a>
                   <div id="sidebar-tabs">
                        <ul class="tabs">
                            <li class="active"><a href="#popular-upcomming">Polular Upcoming</a></li>
                            <li><a href="#last-comments">Last Comments</a></li>
                        </ul>
                        <div id="popular-upcomming" class="sidebar-tabs-top">
                           <ul class="sidebar-tabs-bottom">
                                <li><a href="#"><span style="background-image:url(<?php print_the_static_files_url() ; ?>images/tabimage.jpg)"><!-- Element for the image --></span>The standard Lorem Ipsum passage, used since the 1500s</a></li>
                                <li><a href="#"><span style="background-image:url(<?php print_the_static_files_url() ; ?>images/tabimage.jpg)"><!--  --></span>The standard Lorem Ipsum passage, used since the 1500s</a></li>
                                <li><a href="#"><span style="background-image:url(<?php print_the_static_files_url() ; ?>images/tabimage.jpg)"><!--  --></span>The standard Lorem Ipsum passage, used since the 1500s</a></li>
                                <li><a href="#"><span style="background-image:url(<?php print_the_static_files_url() ; ?>images/tabimage.jpg)"><!--  --></span>The standard Lorem Ipsum passage, used since the 1500s</a></li>
                           </ul>
                           <a href="#" class="btn">See All</a>
                        </div>
                        <div id="last-comments" class="sidebar-tabs-top">
                            <ul class="sidebar-tabs-bottom">
                                <li>1</li>
                                <li>1</li>
                                <li>1</li>
                                <li>1</li>
                            </ul>
                            <a href="#" class="btn">See All</a>
                        </div>
                   </div><!-- /#sidebar-tabs -->
                   <div class="p10 c"><br />
                     <h5>Join your favorite</h5><br />
                     <span class="span">Download beta version The standard Lorem Ipsum passage, used since the 1500s</span>
                   </div>
                   <img src="<?php print_the_static_files_url() ; ?>images/sidebar_bottom_banner.jpg" align="right" alt="" />
                 </div><!-- /#sidebar -->
                </div><!-- /#content-bottom-bg -->
               </div><!-- /.container -->
			</div><!-- /Content -->
		</div><!-- /Wrapper -->
        <div id="footer"><!-- Footer -->
            <div class="container">
                <ul class="footer-nav">
                  <li><a href="#"><strong>Site Links</strong></a></li>
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Take a Tour</a></li>
                  <li><a href="#">Search Digg</a></li>
                  <li><a href="#">Digg Mobile</a></li>
                  <li><a href="#">RSS Feeds</a></li>
                  <li><a href="#">Popular Archive</a></li>
                  <li><a href="#">Terms of Use</a></li>
                </ul>
                <address>
                    &copy; The Master Debate  2008 — Content posted by Digg users is dedicated to the public domain.<br />
                    DIGG, DIGG IT, DUGG, DIGG THIS, Digg graphics, logos, designs, page headers, button icons, scripts, and other service names are the trademarks of Digg Inc.
                </address>
            </div>
        </div><!-- /Footer -->
	</body>
</html>