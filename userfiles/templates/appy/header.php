<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<!-- Mirrored from anchor.no/x3/ by HTTrack Website Copier/3.x [XR&CO'2010], Wed, 22 Jun 2011 09:05:07 GMT -->
<head>
<!-- Title, description and keywords -->
<title>Microweber test template</title>
<script type="text/javascript" src="<?  print site_url('api/js'); ?>"></script>
<meta name="description" content="Appy HTML template" />
<meta name="keywords" content=
    "Appy, HTML, CSS, 960, Template, Professional, High end, Clean, Themeforest" />
<!-- Stylesheets -->
<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>css/960.css" />
<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>css/css.css" />
<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>css/effects.css" />
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>css/ie7.css"/>
	<![endif]-->
<!-- Javascripts -->
 
	</script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/superfish.js">
	</script>
<!-- Superfish menu -->
<script type="text/javascript">

    // Superfish Options
    $(document).ready(function(){ 
        $("ul.sf-menu").superfish(); 
    }); 

  
    </script>
<!-- end:Superfish menu -->
<script src="<? print TEMPLATE_URL ?>js/slides.min.jquery.js" type="text/javascript">
	</script>
<!-- Sliders -->
<script type="text/javascript">

    // Feature slider Options

                $(function(){
                        // Set starting slide to 1
                        var startSlide = 1;
                        // Get slide number if it exists
                        if (window.location.hash) {
                                startSlide = window.location.hash.replace('#','');
                        }
                        // Initialize Slides
                        $('#slides').slides({
                                preload: true,
                                preloadImage: '<? print TEMPLATE_URL ?>images/loading.gif',
                                generatePagination: true,
                                play: 5000,
                                pause: 2500,
                                hoverPause: true,
                                // Get the starting slide
                                start: startSlide,
                                animationComplete: function(current){
                                        // Set the slide number as a hash
                                }
                        });
                        // Set starting slide to 1
                        var startSlide = 1;
                        // Get slide number if it exists
                        if (window.location.hash) {
                                startSlide = window.location.hash.replace('#','');
                        }
                        
                
                        
                        // Quote Slider Options
                        $('#slides2').slides({
                                preload: true,
                                preloadImage: '<? print TEMPLATE_URL ?>images/loading.gif',
                                paginationClass: 'pagination2',
                                effect: 'fade',
                                play: 5000,
                                pause: 2500,
                                container: 'slides_container2',
                                hoverPause: true,
                                // Get the starting slide
                                start: startSlide,
                                animationComplete: function(current){
                                        // Set the slide number as a hash
                                }
                        });
                        });


    </script>
<!-- End:Sliders -->
</head>
<body>
<div id="wrapper"  class="wrapper mw">
  <div class="container_12" id="content" style="width:100%;">
    <!-- Header -->
    <div id="header">
      <div class="container_12">
        <div class="grid_12">
          <!-- Logo -->
          <div class="grid_3 alpha" id="logo"> <a href="index-2.html"><img src="<? print TEMPLATE_URL ?>images/logo.png" alt="" /></a> </div>
          <!-- end:Logo -->
          <!-- Navigation -->
          <div class="grid_6">
       
           
           
  <ul id="nav">
              <li class='active'> <a href='index-2.html'><span>Home</span></a> </li>
              <li> <a href='fullwidth.html'><span>Services</span></a>
                <ul>
                  <li> <a href='index-2.html'><span>Home</span></a> </li>
                  <li> <a href='subpage.html'><span>About</span></a> </li>
                  <li> <a href='fullwidth.html'><span>Services</span></a> </li>
                  <li> <a href='contact.html'><span>Contact</span></a> </li>
                </ul>
              </li>
              <li> <a href='portfolio.html'><span>Portfolio</span></a> </li>
              <li> <a href='contact.html'><span>Contact</span></a> </li>
            </ul> 
          </div>
          <!-- end:Navigation -->
          <!-- Search top -->
          <div class="grid_3 omega">
            <div id="searchbox">
              <form action="http://anchor.no/" method="get">
                <fieldset>
                  <input type="hidden" value="search" />
                  <input type="submit" class=
                    "search-btn" value="Submit" />
                  <input type="text" class="searchbox"
                    value="Search" onclick="this.value=''" onchange="toggle(this)" />
                </fieldset>
              </form>
            </div>
            <!-- end:Search -->
          </div>
        </div>
      </div>
    </div>
    <!-- end:Header -->
    <div class="clear" style="height:0px;"></div>