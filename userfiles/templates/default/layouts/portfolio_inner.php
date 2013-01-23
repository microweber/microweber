<?php

/*

type: layout
content_type: post
name: Portfolio inner page

description: Portfolio inner page

*/


?>
<? include TEMPLATE_DIR. "header.php"; ?>

<section id="content"> 
  
  <!-- welcome text
    <div class="row">
      <div class="span12">
      <div class="container edit" field="content" rel="content"> 
        <div class="page-header1">
          <h2>Single Portfolio <small><a href="#">Download Decision</a> or customize variables, components, javascript plugins, and more.</small></h2>
        </div>
        <p>Nullam egestas nulla rutrum lorem varius nec faucibus est fringilla. Quisque at urna vel leo tincidunt rutrum vitae at enim. Duis ac mi nulla. Sed convallis lobortis vulputate. Etiam feugiat sapien vel felis scelerisque dapibus. Curabitur dictum massa id urna imperdiet eu blandit dolor faucibus. Fusce eu lobortis sem. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui smod tincidunt ut laor.</p>
       </div></div>
    </div>
    --> 
  
  <!-- portfolio 2 -->
  <div class="row">
    <div class="container">
      <div class="span12"> 
        <!-- portfolio starts -->
        <div class="row"> 
          <!-- 1 -->
          <div class="span8">
            <div id="myCarousel" class="carousel slide portfolio-single-slider">
              <div class="carousel-inner">
                <div class="item active"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/portfolio/port-single1.jpg"></div>
                <div class="item"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/portfolio/port-single2.jpg"></div>
                <div class="item"><img class="img-circle" alt="" src="{TEMPLATE_URL}img/portfolio/port-single3.jpg"></div>
              </div>
              <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a> <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a> </div>
          </div>
          <!-- 2 -->
          <div class="span4">
            <div class="portfolio-single span4">
              <div class="edit" field="content" rel="content">
                <h2>Case studie</h2>
                <p>Donec tristique rhoncus libero vitae cursus. Morbi commodo, massa non lobortis rutrum, tortor risus viverra. Donec cursus fringilla aliquet. </p>
                <h2>Project Overview</h2>
                <ol class="list-style-1">
                  <li><span>Date:</span><em>July 5, 2012</em></li>
                  <li><span>Client:</span><em>Apple</em></li>
                  <li><span>Category:</span><em>Web Design</em></li>
                </ol>
                <h2>Services used</h2>
                <ul class="list-style-2">
                  <li class="check-list"><a href="#">Web Design</a></li>
                  <li class="check-list"><a href="#">Logo Design</a></li>
                  <li class="check-list"><a href="#">Wordpress</a></li>
                  <li class="check-list"><a href="#">Joomla</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<? include TEMPLATE_DIR. "footer.php"; ?>
