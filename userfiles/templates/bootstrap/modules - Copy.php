<?php

/*

type: layout

name: Index

description: Home site layout

*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Twitter Bootstrap</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script src="{TEMPLATE_URL}assets/js/jquery.js"></script>
<script src="{TEMPLATE_URL}assets/js/google-code-prettify/prettify.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-transition.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-alert.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-modal.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-dropdown.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-scrollspy.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-tab.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-tooltip.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-popover.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-button.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-collapse.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-carousel.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-typeahead.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-affix.js"></script>
<script src="{TEMPLATE_URL}assets/js/application.js"></script>
<link href="{TEMPLATE_URL}assets/css/bootstrap.css" rel="stylesheet">
<link href="{TEMPLATE_URL}assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="{TEMPLATE_URL}assets/css/docs.css" rel="stylesheet">
<link href="{TEMPLATE_URL}assets/js/google-code-prettify/prettify.css" rel="stylesheet">

<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<link rel="shortcut icon" href="{TEMPLATE_URL}assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-57-precomposed.png">
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">

<!-- Navbar
    ================================================== -->
<div class="navbar navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="brand" href="./index.html">Bootstrap</a>
      <div class="nav-collapse collapse">
      <module type="pages_menu" ul_class_name="nav" />
         
      </div>
    </div>
  </div>
</div>
<div class="jumbotron masthead edit" field="page_heading">
  <div class="container element">
    <h1>Bootstrap</h1>
    <p>Sleek, intuitive, and powerful front-end framework for faster and easier web development.</p>
    <p><a href="{TEMPLATE_URL}assets/bootstrap.zip" class="btn btn-primary btn-large" >Download Bootstrap</a></p>
    <ul class="masthead-links">
      <li><a href="http://github.com/twitter/bootstrap" >GitHub project</a></li>
      <li><a href="./extend.html" >Extend</a></li>
      <li>Version 2.1.1</li>
    </ul>
  </div>
</div>
<div class="bs-docs-social edit" field="sub_heading">
  <div class="container element">
    <ul class="bs-docs-social-buttons">
      <li>
        <iframe class="github-btn" src="http://ghbtns.com/github-btn.html?user=twitter&repo=bootstrap&type=watch&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="100px" height="20px"></iframe>
      </li>
      <li>
        <iframe class="github-btn" src="http://ghbtns.com/github-btn.html?user=twitter&repo=bootstrap&type=fork&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="98px" height="20px"></iframe>
      </li>
      <li class="follow-btn"> <a href="https://twitter.com/twbootstrap" class="twitter-follow-button" data-link-color="#0069D6" data-show-count="true">Follow @twbootstrap</a> </li>
      <li class="tweet-btn"> <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://twitter.github.com/bootstrap/" data-count="horizontal" data-via="twbootstrap" data-related="mdo:Creator of Twitter Bootstrap">Tweet</a> </li>
    </ul>
  </div>
</div>
<div class="container edit" field="content">
  <div class="marketing element">
    <h1>Introducing Bootstrap.</h1>
    <p class="marketing-byline">Need reasons to love Bootstrap? Look no further.</p>
    <div class="row-fluid">
      <div class="span4"> <img src="{TEMPLATE_URL}assets/img/bs-docs-twitter-github.png">
        <h2>By nerds, for nerds.</h2>
        <p>Built at Twitter by <a href="http://twitter.com/mdo">@mdo</a> and <a href="http://twitter.com/fat">@fat</a>, Bootstrap utilizes <a href="http://lesscss.org">LESS CSS</a>, is compiled via <a href="http://nodejs.org">Node</a>, and is managed through <a href="http://github.com">GitHub</a> to help nerds do awesome stuff on the web.</p>
      </div>
      <div class="span4"> <img src="{TEMPLATE_URL}assets/img/bs-docs-responsive-illustrations.png">
        <h2>Made for everyone.</h2>
        <p>Bootstrap was made to not only look and behave great in the latest desktop browsers (as well as IE7!), but in tablet and smartphone browsers via <a href="./scaffolding.html#responsive">responsive CSS</a> as well.</p>
      </div>
      <div class="span4"> <img src="{TEMPLATE_URL}assets/img/bs-docs-bootstrap-features.png">
        <h2>Packed with features.</h2>
        <p>A 12-column responsive <a href="./scaffolding.html#grid">grid</a>, dozens of components, <a href="./javascript.html">javascript plugins</a>, typography, form controls, and even a <a href="./customize.html">web-based Customizer</a> to make Bootstrap your own.</p>
      </div>
    </div>
    <hr class="soften">
    <h1>Built with Bootstrap.</h1>
    <p class="marketing-byline">For even more sites built with Bootstrap, <a href="http://builtwithbootstrap.tumblr.com/" target="_blank">visit the unofficial Tumblr</a> or <a href="./getting-started.html#examples">browse the examples</a>.</p>
    <div class="row-fluid">
      <ul class="thumbnails example-sites">
        <li class="span3"> <a class="thumbnail" href="http://soundready.fm/" target="_blank"> <img src="{TEMPLATE_URL}assets/img/example-sites/soundready.png" alt="SoundReady.fm"> </a> </li>
        <li class="span3"> <a class="thumbnail" href="http://kippt.com/" target="_blank"> <img src="{TEMPLATE_URL}assets/img/example-sites/kippt.png" alt="Kippt"> </a> </li>
        <li class="span3"> <a class="thumbnail" href="http://www.fleetio.com/" target="_blank"> <img src="{TEMPLATE_URL}assets/img/example-sites/fleetio.png" alt="Fleetio"> </a> </li>
        <li class="span3"> <a class="thumbnail" href="http://www.jshint.com/" target="_blank"> <img src="{TEMPLATE_URL}assets/img/example-sites/jshint.png" alt="JS Hint"> </a> </li>
      </ul>
    </div>
  </div>
</div>

<!-- Footer
    ================================================== -->
<footer class="footer edit" field="footer" rel="global">
  <div class="container element">
    <p class="pull-right"><a href="#">Back to top</a></p>
    <p>Designed and built with all the love in the world <a href="http://twitter.com/twitter" target="_blank">@twitter</a> by <a href="http://twitter.com/mdo" target="_blank">@mdo</a> and <a href="http://twitter.com/fat" target="_blank">@fat</a>.</p>
    <p>Code licensed under the <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License v2.0</a>. Documentation licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
    <p>Icons from <a href="http://glyphicons.com">Glyphicons Free</a>, licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
    <ul class="footer-links">
      <li><a href="http://blog.getbootstrap.com">Read the blog</a></li>
      <li><a href="https://github.com/twitter/bootstrap/issues?state=open">Submit issues</a></li>
      <li><a href="https://github.com/twitter/bootstrap/wiki">Roadmap and changelog</a></li>
    </ul>
  </div>
</footer>
</body>
</html>
