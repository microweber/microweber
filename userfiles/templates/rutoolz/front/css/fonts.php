<?php header("Content-type: text/css"); ?>
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)): ?>

@font-face {
 font-family: hlr;
 src: url("hlr.eot");
}


<? else: ?>

@font-face {
  font-family: 'hlr';
  font-style: normal;
  font-weight: normal;
  src: local('hroman'), url('hlr___.ttf') format('truetype');
}


<? endif; ?>
