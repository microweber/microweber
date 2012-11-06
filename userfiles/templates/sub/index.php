<? include TEMPLATE_DIR. "header.php"; ?>

<section id="river">
  <header id="begin">
    <time datetime="2012-09-27" id="top_time">September 27, 2012</time>
  </header>
  <div class="module" id="posts_home" data-type="posts_list" data-display="custom">
    <? 

	 
	$query = module("type=posts_list&id=posts_home&display=custom");
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
      
	  
	  <div class="cart_add">
      
      <module type="custom_fields" for="content" for_id="<? print $item['id'] ?>"  />
      
      </div>
	  <? 
	  
	  
	   $cf  = false;
     // $cf  = custom_fields_content($item['id'], 'price');
   // d($cf) ;
   if(isarr($cf)){
	foreach($cf as $field){
	?>
    <? print  make_field($field); ?>
      <a href="javascript:mw.module.shop.add_to_cart('<? print $item['id'] ?>')"><? print ($field['custom_field_name']); ?>: <? print ($field['custom_field_value']); ?></a> <br />
      <?	
	}
   }
   
   
   ?>
      <a href="<? print post_link($item['id']); ?>"> Read more </a> </article>
    <? endforeach; ?>
  </div>
</section>
<? include TEMPLATE_DIR. "footer.php"; ?>
