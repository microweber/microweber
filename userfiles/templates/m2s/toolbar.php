<script type="text/javascript">

      $(document).ready(function(){
	 $(':input[title]').each(function() {
  var $this = $(this);
  if($this.val() === '') {
    $this.val($this.attr('title'));
  }
  $this.focus(function() {
    if($this.val() === $this.attr('title')) {
      $this.val('');
    }
  });
  $this.blur(function() {
    if($this.val() === '') {
      $this.val($this.attr('title'));
    }
  });
});
	 
	 
	 
      
    });
     

</script>

<div class="top_stripe" align="center">
  <div class="top_content">
    <? $user = user_id(); ?>
    <? if($user > 0): ?>
    <? $user_data = get_user($user); ?>
    <? if(stristr($user_data['email'], 'gmail')): ?>
    <?  //  p( $user_data); ?>
    
     <? if(($user_data['is_active']) == 'n' and url_param('view') != 'activation'): ?>
    
    <meta http-equiv="refresh" content="0;URL=<? print site_url('register/view:activation'); ?>" />

    
    
     <? endif; ?>
    <? else:  ?>
    <? endif; ?>
    <? else:  ?>
    <? endif; ?>
    <? if($user > 0): ?>
    <div class="header_user_box">
      <? $u_pic = get_picture($user, $for = 'user'); 
	
	?>
      <? if($u_pic == false){
	$u_pic = TEMPLATE_URL . "images/mystery-man.jpg";
	
	} else {
		
	$u_pic = get_media_thumbnail($u_pic ['id'], $size_width = 32, $size_height = false);	
	
	//p($u_pic_1);
	}?>
      <div class="header_user_box_in">
        <table border="0" cellpadding="0" cellspacing="0" width="180">
          <tr valign="middle">
            <td><a  style="float:left; margin-right:5px;" href="<? print site_url('dashboard'); ?>"> <img src="<? print $u_pic;  ?>" height="25" /></a></td>
            <td><a class=""  href="<? print site_url('dashboard'); ?>">Dashboard</a> |</td>
            <td><? $new_msgs = get_unread_messages(); ?>
              <? 
	  $msg_class = 'msg-ico-no-new';
	  if($new_msgs > 0): ?>
              <?  $msg_class = 'msg-ico-new'; ?>
              <? endif; ?>
              <a class="<? print $msg_class; ?>" href="<? print site_url('dashboard/view:my-messages'); ?>" title="<? print $new_msgs; ?> messages"></a> |</td>
            <td><a class=""  href="#" onclick="mw.users.LogOut()">Exit</a></td>
          </tr>
        </table>
        <!--<a class=""  href="<? print site_url('profile'); ?>">Profile</a> |-->
      </div>
    </div>
    <? else:  ?>
    <a class="join"  href="<? print site_url('user-login'); ?>"></a>
    <? endif;  ?>
    <div class="caption">The No.1 meeting place for students!</div>
    <div class="header_user_box_in2" style="">
 <table border="0" cellpadding="5">
  <tr valign="middle">
<!--    <td><a href="http://facebook.com"><img src="<? print TEMPLATE_URL ?>images/ext/facebook-icon.png" height="16" /></a></td>
-->    

 <td><a href="<? print site_url(); ?>"><img src="http://www.surferport.com/img/anasayfa.jpg" height="16" /></a></td>
    <td>
     <style>
    #gsearch {
	color: #666;
	font-size: 11px;
 
	 width:96px;
	 height:15px; 
	 line-height:15px;
 
 	 
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
 
}
    
    </style>
    
    <form action="http://www.google.com" id="cse-search-box" target="_blank">

    <input type="hidden" name="cx" value="partner-pub-8538482414192391:7299964869" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" id="gsearch" title="Search the net" size="15" style="" />
    <!--    <input type="image" src="http://www.bgdev.org/firefox/google_search.png" name="sa" value="Search" />-->

</form>
<script type="text/javascript" src="http://www.google.com/cse/query_renderer.js"></script>
 
</td>
<!-- <td><a href="http://bbc.co.uk"><img src="<? print TEMPLATE_URL ?>images/ext/351stj9.png" height="16" /></a></td>
        <td><a href="http://wikipedia.com"><img src="<? print TEMPLATE_URL ?>images/ext/wikipedia-icon.png" height="16" /></a></td>
        
                <td><a href="http://music.yahoo.com/"><img src="<? print TEMPLATE_URL ?>images/ext/social_yahoo_box_lilac.png" height="16" /></a></td>

                        <td><a href="http://9gag.com/"><img src="<? print TEMPLATE_URL ?>images/ext/9gag-icon.png" height="16" /></a></td>
-->
        
<!--                <td><a href="http://gmail.com"><img src="<? print TEMPLATE_URL ?>images/ext/gmail-favicon.png" height="16" /></a></td>
-->

  </tr>
</table>
</div>
<!--    <div class="likethis"><a href="https://www.facebook.com/pages/Money2Studycom/231996293479013" target="_blank"><img src="<? print TEMPLATE_URL ?>images/likethis.jpg" alt="likehtis" /></a></div>
    <div class="like_box" style="overflow:hidden">
      <div class="fb-like" data-href="<? print site_url(); ?>" data-send="false" data-width="150" data-show-faces="false"></div>
    </div>-->
    <a class="buzz" href="<? print site_url('freechat'); ?>"><img src="<? print TEMPLATE_URL ?>images/buzz.jpg" alt="buzz" /></a> <a href="<? print site_url('freechat'); ?>" class="chat_text">Start chat with others online</a> </div>
</div>
<div class="top_line"></div>