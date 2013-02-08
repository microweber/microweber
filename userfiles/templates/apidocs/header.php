<!DOCTYPE HTML>
<html prefix="og: http://ogp.me/ns#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="<?php print( INCLUDES_URL); ?>js/jquery.js"></script>

<!-- Meta Information -->
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta NAME="Keywords" CONTENT="{content_meta_keywords}">
<meta property="og:title" content="{content_meta_title}" />
<link rel="stylesheet" href="{THIS_TEMPLATE_URL}bootstrap.css" type="text/css" media="screen">
</head>
<body>
<?php $base_path = ( TEMPLATE_DIR); ?>
<? $base_link =  layout_link('docs'); ?>

      <module type="files/directory_tree" base_path="docs"  base_link="<? print $base_link ?>"  ul_class="nav nav-list" />


<a href="<? print layout_link('functions/get'); ?>"><? print layout_link('functions/get'); ?></a>







