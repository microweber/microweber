<?php

/*

type: layout
content_type: static
name: Portfolio layout

description: Portfolio layout

*/


?>
<? include TEMPLATE_DIR. "header.php"; ?>
<section id="content">
  <div class="container edit" rel="page" field="content">
    <!-- welcome text -->
    <div class="row">
      <div class="span12">
        <div class="page-header1">
          <h2>Portfolio 2 <small><a href="#">Download Decision</a> or customize variables, components, javascript plugins, and more.</small></h2>
        </div>
        <p>Nullam egestas nulla rutrum lorem varius nec faucibus est fringilla. Quisque at urna vel leo tincidunt rutrum vitae at enim. Duis ac mi nulla. Sed convallis lobortis vulputate. Etiam feugiat sapien vel felis scelerisque dapibus. Curabitur dictum massa id urna imperdiet eu blandit dolor faucibus. Fusce eu lobortis sem. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh eui smod tincidunt ut laor.</p>
      </div>
    </div>
    <!-- portfolio 2 -->
    <div class="row">
      <div class="span12 portfolio">
        <!-- sorting menu -->
        <ul id="filterOptions" class="port-filters clearfix">
          <li><a href="#" class="all">All</a></li>
          <li><a href="#" class="business">Business</a></li>
          <li><a href="#" class="graphics">Graphics</a></li>
          <li><a href="#" class="wordpress">Wordpress</a></li>
          <li><a href="#" class="web">Web</a></li>
        </ul>
        <!-- portfolio starts -->
        <module type="posts" template="portfolio" />
      </div>
    </div>
  </div>
</section>
<? include TEMPLATE_DIR. "footer.php"; ?>
