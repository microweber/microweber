<!--Showing pots link with title-->



<h2 class="title"> <a href="<?php print CI::model('content')->contentGetHrefForPostId($post['id']) ; ?>"><?php print $post['content_title'] ?></a> </h2>

<!--Show the date-->

<span class="date"><small>Created on: <?php print $post['created_on'] ?></small></span> <br />

<br />

<?php $pictures = CI::model('content')->contentGetPicturesFromGalleryForContentId($post['id'], 'original'); 

				  $pictures2 = CI::model('content')->contentGetPicturesFromGalleryForContentId($post['id'], '600'); 

				  $pictures3 = CI::model('content')->contentGetPicturesFromGalleryForContentId($post['id'], '300'); 

				   ?>

<?php if(!empty($pictures)):

 //var_dump($pictures );

 ?>

<br />

<div id="slides" class="V2">

  <div id="slider">

    <?php $i =0; foreach($pictures as $pic): ?>

    <?php //if($i > 0): ?>

    <a href="<?php print $pictures2[$i]; ?>" class="box active zoom"

        style="background-image:url('<?php print $pictures3[$i]; ?>')"></a>

    <?php //endif; ?>

    <?php $i++; endforeach; ?>

  </div>

</div>

<span id="slides_left">Back</span> <span id="slides_right">Forward</span>

<div class="clear"></div>

<?php endif; ?>

<!--Show comments count -->

<?php //$temp= CI::model('comments')->commentsGetForContentId( $post['id']); print (count(  $temp )); ?>

<!--Comments-->

<!--Show post body-->

<?php print (($post['the_content_body']) ); ?> <br />

<br />

<br />

<?php $the_post_to_share = $post;

	  $social_submit_stuff = array();

	  $social_submit_stuff['url'] =  urlencode( CI::model('content')->contentGetHrefForPostId($the_post_to_share['id']));

	  $social_submit_stuff['title'] =  urlencode( ($the_post_to_share['content_title']));

	  $social_submit_stuff['desc'] =  urlencode(character_limiter($the_post_to_share['content_description'],160, '')); 

	  $social_submit_stuff['desc1'] =  urlencode(character_limiter($the_post_to_share['content_description'],60, '')); 

	  

	  ?>

 

<table border="0" align="left">

  <tr>

  <td><h4>Share this with your friends:  </h4></td>

  <td><a href="http://digg.com/submit?url=<?php print $social_submit_stuff['url'];  ?>&title=<?php print  $social_submit_stuff['title'] ?>&bodytext=<?php print  $social_submit_stuff['desc'] ?>"><img src="<?php print TEMPLATE_URL; ?>img/social_media/digg.png" height="24" width="24" border="0" /></a></td>

  <td><a href="http://www.stumbleupon.com/submit?url=<?php print $social_submit_stuff['url'];  ?>&title=<?php print  $social_submit_stuff['title'] ?>"><img src="<?php print TEMPLATE_URL; ?>img/social_media/stumbleupon.png" height="24" width="24" border="0" /></a></td>

  <td><a href="http://delicious.com/save" onclick="window.open('http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url='+encodeURIComponent(location.href)+'&amp;title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;"><img src="<?php print TEMPLATE_URL; ?>img/social_media/delicious.png" height="24" width="24" border="0" /></a></td>

    <td><script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>

      <a href="http://www.facebook.com/share.php?u=<url>" onclick="return fbs_click()" target="_blank"><img src="<?php print TEMPLATE_URL; ?>img/social_media/facebook.png" height="24" width="24" border="0" /></a></td>

    <td><a href="http://twitter.com/home?status=<?php print $social_submit_stuff['url'];  ?>%20<?php print  $social_submit_stuff['desc1'] ?>"><img src="<?php print TEMPLATE_URL; ?>img/social_media/twitter.png" height="24" width="24" border="0" /></a></td>

    <td><a href="<?php print site_url('main/rss') ?>" rel="alternate" target="_blank" type="application/rss+xml"><img src="<?php print TEMPLATE_URL; ?>img/social_media/rss.png" height="24" width="24" border="0" /></a></td>

  </tr>

</table>

<?php if($post['comments_enabled'] != 'n') : ?>

<div id="comments_container"> <a name="the_comments_anchor" id="the_comments_anchor"></a>

  <?php $comments = array();

$comments ['to_table'] = 'table_content';

$comments ['to_table_id'] = $post['id'];

$comments = CI::model('comments')->commentsGet($comments);

?>

  <?php if(!empty($comments)) : ?>

  <?php foreach($comments as $item): ?>

  <div class="box comment wrap">

    <div class="comment_image" style="background-image: url('<?php echo gravatar( $item['comment_email'], $rating = 'X', $size = '64', $default =  TEMPLATE_URL .'img/gravatar.jpg' ); ?>')"></div>

    <div class="comment_content"> <span class="author"><strong><?php print $item['comment_name'] ?></strong> <small><a href="<?php print prep_url($item['comment_website']) ?>" rel="nofollow" class="pink_text" target="_blank">(website)</a></small></span>&nbsp;<br />

      <span class="date"><small>posted on <?php print $item['created_on'] ?></small></span>

      <p> <?php print ($item['comment_body']); ?></p>

    </div>

  </div>

  <?php endforeach ; ?>

  <?php else : ?>

  <div class="box comment wrap">

    <div class="comment_content">

      <p>No comments yet. Be the first to comment.</p>

    </div>

  </div>

  <?php endif; ?>

</div>

<script type="text/javascript">



function refresh_after_post_comment(){

		var refresh_the_page = "<?php print CI::model('content')->contentGetHrefForPostId($post['id']) ; ?>#the_comments_anchor";

		//window.location=refresh_the_page;

		window.location.reload();

	}







    $(document).ready(function(){

       $("#comments_form input, #comments_form textarea").focus(function(){$(this).addClass("focus")});

       $("#comments_form input, #comments_form textarea").blur(function(){$(this).removeClass("focus")});



       $("#comments_form").validate();







    CommentOptions = {



		url:       '<?php print site_url('main/comments_post'); ?>'  ,

		clearForm: true,

		type:      'post',

        beforeSubmit:  comments_before,  //

        success:       comments_after



    };



    $('#comments_form').submit(function(){

        $(this).ajaxSubmit(CommentOptions);

        return false;

    });



    function  comments_before(){

        var TF = true;

        if($("#comments_form textarea.error").exists() || $("#comments_form input.error").exists()){

            TF = false;



        }

        var test_tf = TF;

        if(test_tf==true){

          $("#cf_submit").hide();

        }

        return TF;



    }

    function  comments_after(){

       var success_elem = document.createElement("span");

       success_elem.className = "success_elem";

       success_elem.innerHTML = "Your message has been sent."

       $("#comments_form").append(success_elem);

	   refresh_after_post_comment();

    }







    });



</script>

<div id="commentForm">

  <form method="post" action="#" id="comments_form">

    <input type="hidden" name="to_table_id" id="to_table_id"  value="<?php print (base64_encode($post['id']) ); ?>"  />

    <input type="hidden" name="to_table" id="to_table"  value="<?php print (base64_encode('table_content') ); ?>"  />

    <label>Name:*</label>

    <div class="box boxv2 wrap">

      <input type="text" name="comment_name" class="required" />

    </div>

    <label>Email:*</label>

    <div class="box boxv2 wrap">

      <input type="text" name="comment_email" class="required email" />

    </div>

    <label>Website:</label>

    <div class="box boxv2 wrap">

      <input name="comment_website" type="text" />

    </div>

    <label>Comment:*</label>

    <div class="box boxv2 wrap">

      <textarea rows="" cols="" name="comment_body" class="required"></textarea>

    </div>

    <div style="height: 10px"></div>

    <a onclick="$('#comments_form').submit();" class="small_btn left" id="cf_submit" href="javascript:;"><span>Post comment</span></a>

  </form>

</div>

<?php endif; ?>

