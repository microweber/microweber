<?php header("Content-type: text/css"); ?>
<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)): ?>

@font-face {
 font-family: helvetica;
 src: url("helvetica.eot") /* EOT file for IE */
}




<? else: ?>

@font-face {
  font-family: 'helvetica';
  font-style: normal;
  font-weight: normal;
  src: local('helvetica'), url('helvetica.ttf') format('truetype');
}

<? endif; ?>
