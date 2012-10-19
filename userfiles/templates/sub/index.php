<!DOCTYPE html>
<!-- saved from url=(0078)file:///C:/My%20Web%20Sites/http___blog.svbtle.com_/blog.svbtle.com/index.html -->
<html lang="en" class="wf-proximanova-n6-inactive wf-proximanova-n7-inactive wf-freightsanspro-n7-inactive wf-proximanova-n4-inactive wf-inactive">
<!-- Mirrored from blog.svbtle.com/ by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 14 Oct 2012 14:53:03 GMT --><!-- Added by HTTrack -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- /Added by HTTrack -->

<meta charset="utf-8">
<title>The Official Svbtle Blog by Svbtle Network</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="generator" content="Svbtle.com">
<meta name="description" content="The future of news and opinion on the web • Svbtle Network’s blog">
<link href="{TEMPLATE_URL}/index_files/style.css" media="screen" rel="stylesheet" type="text/css">
<style>
blockquote {
	border-color: #000000;
}
aside#logo, aside#logo div a, ul#user_meta a:hover span.link_logo_inside, ul#user_meta a:hover span.link_logo, aside.kudo.complete span.circle {
	background-color: #000000;
}
aside#logo div a, aside.kudo.complete span.circle {
}
section.preview header#begin h2, ul#user_meta a:hover, nav.pagination span.next a, nav.pagination span.prev a {
	color: #000000;
}
ul#user_meta a:hover, nav.pagination span.next a, nav.pagination span.prev a {
	border-color: #000000;
}
</style>
<style type="text/css">
.tk-freight-sans-pro {
	font-family:"freight-sans-pro", sans-serif;
}
.tk-proxima-nova {
	font-family:"proxima-nova", sans-serif;
}
</style>
</head>
<body class="blog">
<header id="sidebar">
  <aside id="logo" class="clearfix">
    <div class="editable" id="sdasd<? print $item['id'] ?>" field="content">My post</div>
  </aside>
  <ul id="user_meta">
    <li class="blog_name">
      <h1 id="blog_name"> <a href="{TEMPLATE_URL}/index_files/index.php">The Official Svbtle Blog</a> </h1>
    </li>
    <li class="blog_owner">
      <h2 id="blog_owner"> <a href="{TEMPLATE_URL}/index_files/index.php">Svbtle Network</a> </h2>
    </li>
    <li class="tagline">
      <h2 id="tagline"> The future of news and opinion on the web </h2>
    </li>
    <li class="link website"> <a href="http://svbtle.com/"> svbtle.com </a> </li>
    <li class="link twitter"> <a href="http://twitter.com/svbtle"> @svbtle </a> </li>
    <li class="link email"> <a href="mailto:hello@svbtle.com?subject=hi"> say hello </a> </li>
    <li class="link feed"> <a href="file:///C:/My%20Web%20Sites/http___blog.svbtle.com_/blog.svbtle.com/feedhtml.html"> feed </a> </li>
  </ul>
  <aside id="svbtle_linkback"> <a href="https://svbtle.com/"> <span class="logo_square"><span class="logo_circle">&nbsp;</span></span>&nbsp;<span class="svbtle">Svbtle</span> </a> </aside>
</header>
<section id="river">
  <header id="begin">
    <time datetime="2012-09-27" id="top_time">September 27, 2012</time>
  </header>
  <div class="module" id="posts_home" data-type="posts_list" data-display="custom">
    <? $query = module("type=posts_list&id=posts_home&display=custom");
	 //d($query );
 $cont  = ($query['data'] );

//print $query['edit'];
?>
    <? //$cont =  get_posts("id=22&parent=".$posts_parent_page );  ?>
    <? 

  foreach($cont as $item): ?>
    <article id="eidEPbDNHCf7HcO1IgzovgucHUQ5XFDZsgdXMAaX<? print $item['id'] ?>" class="post">
      <h2 class="edit no-drop"    rel="content"  data-field="title"  data-id="<? print $item['id'] ?>"  >My post title</h2>
      <div class="edit"  rel="content"  data-field="description" data-id="<? print $item['id'] ?>"  >
        <p>My post description</p>
      </div>
      <a href="<? print post_link($item['id']); ?>"> Read more </a> </article>
    <? endforeach; ?>
  </div>
</section>
</body>
</html>