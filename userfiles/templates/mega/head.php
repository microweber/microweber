<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php


    $phone = '+1-213-342 8525';
    $cdn = "http://microweber.com/cdn/";

 ?>
  <title>Microweber</title>

  <style type="text/css">
  @font-face {
  	font-family: 'MW';
  	src: url('//microweber.com/cdn/ex2.ttf');
  	src: local('MW'), local('MW'), url('//microweber.com/cdn/ex2.ttf') format('truetype');
  }
  </style>

  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=greek,latin,cyrillic-ext,latin-ext,cyrillic">
  <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
  <link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
  <link href="<?php print $cdn; ?>style.css" rel="stylesheet">
  <link href="<?php print $cdn; ?>style.responsive.css" rel="stylesheet">
  <script type="text/javascript" src="<?php print( MW_INCLUDES_URL); ?>js/jquery-1.9.1.js"></script>
  <script src="<?php print site_url(); ?>apijs"></script>
  <script>
      mw.require("<?php print MW_INCLUDES_URL; ?>css/mw.ui.css");
      mw.require("<?php print MW_INCLUDES_URL; ?>css/mw_framework.css");
      mw.require("tools.js");
      mw.require("url.js");
  </script><script src="<?php print TEMPLATE_URL; ?>siteapi.js"></script>

</head>