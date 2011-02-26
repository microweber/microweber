<div id="user_sidebar">
  <? $categories = get_categories(); ?>
  <h3 class="user_sidebar_title nomargin"><a href="<? print page_link($page['id']); ?>">Games by category</a></h3>
  <ul class="user_side_nav user_side_nav_nobg">
    <? foreach($categories as $category): ?>
    <li><a href="<? print get_category_url($category['id']); ?>"  class="<? is_active_category($category['id'],' active') ?>"  ><? print $category['taxonomy_value'] ?></a> </li>
    <? endforeach; ?>
  </ul>
  <script type="text/javascript">
function content_list($kw){
   
   if(($kw == false) || ($kw == '')){
	$kw = '';   
	   $('#results_holder').fadeOut();
	$('#top_games_holder').fadeIn();
	$('#top_games_holder_title').fadeIn();
	$('#results_holder_title').fadeOut();
   } else {
   
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: ({module : 'posts/list' ,
			// user_id : $user_id, 
			 file : 'posts_list_games', 
			 category : 'games',  
			
			 keyword : $kw 

			 }),
     // dataType: "html",
      async:false,

  success: function(resp) {
     // alert(resp);
   $('#results_holder').html(resp);
    $('#results_holder').fadeIn();
	$('#top_games_holder').fadeOut();
	$('#top_games_holder_title').fadeOut();
	
	 $('#results_holder_title').fadeIn();
	
	$('#results_holder_title').html("Search results for: "+ $kw);
	
   // alert('Load was performed.');
  }
    });
   
   }
}

$(document).ready(function() {
  //users_list();

  $(".content_search").onStopWriting(function(){
       content_list(this.value);
  });

});

</script>
 
 <br />

  <h3 class="user_sidebar_title nomargin">Search all games</h3>
  <form class="sidebar_search">
    <input type="text"  class="content_search"  />
    <input type="submit" value="" class="xhidden" />
    
    <a href="#" class="gsearch"></a>
  </form>
</div>
<!-- /#user_sidebar -->
