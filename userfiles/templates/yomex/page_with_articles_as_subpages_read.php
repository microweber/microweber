
<div class="heading">
  <h1><?php print $page['content_title']; ?></h1>
</div>
<div class="holder">
  <div class="hleft">
    <div class="list-offers gr">
   
      <h2 class="gtitle"> <?php print ($post['content_title']); ?> </h2>
      <span class="hr"></span>
      <div class="content textpages">
     <?php print (($post['content_body']) ); ?>
     </div>
     </div>
  </div>
  <?php include(ACTIVE_TEMPLATE_DIR.'page_with_articles_as_subpages_sidebar.php') ;  ?>
</div>
<!-- /#left --> 

<!-- /#right -->
<div class="c"></div>
</div>
<!-- /#holder --> 