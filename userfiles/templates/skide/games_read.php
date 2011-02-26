
<div id="main_side">
  <h2><?php print $post['content_title'] ?></h2>
  <br />
  <p>Play this game and share it on your wall if you like it!</p>
  <br />
  <div class="hr">&nbsp;</div>
  <?php print $post['the_content_body'] ?>
  <div class="c">&nbsp;</div>
  
  <div id="the_game">


  
  <?php print html_entity_decode( $post['custom_fields']['embed_code']) ?>
  
  
  <?php $ext_link =  ( $post['custom_fields']['external_link']) ; ?>
  <? if(stristr($ext_link, 'mochigames')): ?>
  
  
  <iframe class="xhidden" src="<?php print ( $post['custom_fields']['external_link']) ; ?>" width="300" height="300"></iframe>


  


  <? $src = CI::model('core')->url_getPage($post['custom_fields']['external_link']);

  $src = substring_between($src, 'http://games.mochimedia.com', '.swf'); 
  


  //var_dump( );
  
  $src = "http://games.mochimedia.com".$src.'.swf';
  //print $src;
  ?>
    <div id="the_game_m">

    </div>

    </div>
  <script>
    $(document).ready(function(){
        var src = "<? print $src; ?>";


        var embed = ''
        +'<embed '
        +   'src="'+ src + '"'
        +   'type="application/x-shockwave-flash"'
        +   'allowscriptaccess="always"'
        +   'allowfullscreen="true"></embed>';

        $("#the_game_m").html(embed);







    });
  </script>

 
 <? else: ?>
  <? /*
  <a href="<?php print ( $post['custom_fields']['external_link']) ; ?>" target="_blank">Click here to open link: <?php print ( $post['custom_fields']['external_link']) ; ?></a>
  */ ?>
  <? endif; ?>

  
   
  <div class="c">&nbsp;</div>
  <div class="gamevote"> <a  class="user_activity_likes right"  href="<? print voting_link($post['id'], '#post-likes-'.$post['id']); ?>"><strong id="post-likes-<? print ($post['id']); ?>"><? print votes_count($post['id']); ?></strong> Like</a> Vote for this game </div>
  <br />
  <h2 style="padding-bottom: 10px;">Who like this game?</h2>
  <div class="hr">&nbsp;</div>
  <ul class="user_friends_list user_friends_list_horizontal">
    <? //mw_var($post['id']); ?>
    <script type="text/javascript">
   
   
   
   $(document).ready(function() {
   refresh_voted_users()
});
	
   mw.ajaxEvent("onAfterVote", function(){
										
		refresh_voted_users()							 

});
   
   
   
   
   
   
function refresh_voted_users(){
	$.ajax({
  url: '{SITE_URL}api/module',
   type: "POST",
      data: ({module : 'posts/voted_users' ,post_id : '<? print ($post['id']); ?>' }),
     // dataType: "html",
      async:false,
	  
  success: function(resp) {
	 // alert(resp);
   $('#voted-users-for-<? print ($post['id']); ?>').html(resp);
   // alert('Load was performed.');
  }
	});
}
</script>
    <div id="voted-users-for-<? print ($post['id']); ?>"></div>
  </ul>
  <div class="c">&nbsp;</div>
  <br />
  <br />
  
      <?php include "banner_wide.php" ?>
    <br />
  <br />
  <?  $category = get_category($active_category); ?>
  <h2>More games from <? print $category['taxonomy_value']; ?></h2>
  <div class="top_games top_games_read">
    <?
 
$params= array();
$params['display']= 'games_list_single_item.php';
$params['selected_categories'] =  array($active_category);
$params['items_per_page'] = 4;
$params['curent_page'] = rand(1,3);
get_posts($params);
 
 ?>
    <a href="<? print category_url($active_categories[1]); ?>" class="mw_btn_s right" ><span>See all <? print get_category_items_count($active_categories[1]); ?> games</span></a> </div>
  <microweber module="comments/default" post_id="<? print $post['id']; ?>">
</div>
<!-- /#main_side -->
