
<?

   $comments = array ();
	$comments ['to_table'] = 'post';
	$comments ['to_table_id'] = $post['id'];
	//	p($comments);
	$comments = get_comments($comments);
 

// p($cooms);

?>
<? if(!empty($comments)) : ?>
<? foreach($comments as $item): ?>
<? 
	  
	  $email = trim( $item['comment_email'] ); // "MyEmailAddress@example.com"
$email = strtolower( $email ); // "myemailaddress@example.com"
$email = md5( $email );
// "0bc83cb571cd1c50ba6f3e8a78ef1346"
	  ?>

<div class="speak_box">
  <div class="commentee_thumb" style="background-image:url('<? print user_thumbnail($item['created_by'], 90); ?>')"></div>
  <div class="commentee_text">
    <div class="commentee_name"><? print user_name($item['created_by']); ?>:</div>
    <? print $item['comment_body'] ?> </div>
</div>
<?  endforeach ; ?>
<? endif; ?>






<script>
     
     
    function post_comment(){
    $comment_form = jQuery( "#comment_form" ).serialize();
   
	
     jQuery.post("<? print site_url('api/comments/comments_post') ?>",  $comment_form  ,
    function(resp) {
		 jQuery( "#comment_form" ).fadeOut();
		 jQuery( "#comment_form2" ).fadeIn();
		//alert(resp);
		 
  
    }, 'html');
    }
     
     
     
     
     
     
    </script>
  <? $user = user_id(); ?>
    <? if($user > 0): ?>
   <form id="comment_form" onSubmit="post_comment(); return false;">
  <input type="hidden" name="to_table" value="<? print enc('post'); ?>" >
  <input type="hidden" name="to_table_id" value="<? print enc($post['id']); ?>">
  <div class="speaknow_tit">Comment</div>
 
   
 
  <textarea  cols="" rows="" class="speak_text_ara" name="comment_body"></textarea>
  <div class="post_comment_but">
    <input type="image" src="<? print TEMPLATE_URL ?>images/post_coment_but.jpg" />
  </div>
</form>
<h4 id="comment_form2" style="display:none;">Your comment has been posted. Thank you!</h4>

    <? else:  ?>
    <h4 id="comment_form_login" ><a href="<? print site_url('user-login'); ?>">Your must login in order to comment</a></h4>
    <? endif; ?>   
    
