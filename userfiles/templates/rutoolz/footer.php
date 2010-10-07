<? if($no_content == false): ?>

<div id="RU-footer">
  <div class="pad3"></div>
  <div class="foot-content"> Dev: <a href="<? print site_url('users/user_action:posts/'); ?>" title="This Manager will helps you to create the perfect sales page">Pages manager</a> 
    <!-- Copyright to or somekind of navigation here--> </div>
</div>
<!--END CONTAINER-->
</div>
<div id="footer-scripts">
  <? include (ACTIVE_TEMPLATE_DIR.'footer_stats_scripts.php') ?>
  <?  // if(is_file(ACTIVE_TEMPLATE_DIR.'users/users_login_register_form.php') == true){ include (ACTIVE_TEMPLATE_DIR.'users/users_login_register_form.php'); } ?>
</div>
<div id="helper" style="width: 449px;"> <span class="hideHelp" onclick="hideHelp()">Hide this panel</span>
  <h2>See how to choose color theme</h2>
  <div class="c">&nbsp;</div>
  <object width="449" height="272">
    <param name="movie" value="http://www.youtube.com/v/vIgWmRsQLpk&hl=en_US&fs=1&">
    </param>
    <param name="allowFullScreen" value="true">
    </param>
    <param name="allowscriptaccess" value="always">
    </param>
    <embed src="http://www.youtube.com/v/vIgWmRsQLpk&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="449" height="272"></embed>
  </object>
  <h3>FAQ'S</h3>
  <ul class="help-list">
    <li><a href="#">How to buy other modules?</a></li>
    <li><a href="#">How to add new templates on the list?</a></li>
    <li><a href="#">May I use something different?</a></li>
  </ul>
  <a href="#" class="view-all-faqs">View all FAQ'S</a> </div>
<!-- /#helper --> 

<script type="text/javascript">

$(document).ready(function() {
    if($("#player").length>0){
	    flowplayer("player", "<? print TEMPLATE_URL; ?>videos/flowplayer/flowplayer-3.2.0.swf");
	}


$(window).load(function(){
   windowLoaded = true;

   setTimeout(function(){

        $("#loadingOverlay").animate({"opacity":"hide"});

   }, 1000)
});


});
  var divs = document.getElementsByTagName('div');
  for(var i=0; i<divs.length;i++){
      if(divs[i].className.indexOf('tab')!=-1){
          divs[i].id = divs[i].id + '-tab';
      }
  }

</script> 
<!-- this will install flowplayer inside previous A- tag. -->

<div id="loadingOverlay">&nbsp;</div>
<div class="shadow">&nbsp;</div>
<? endif; ?>
</body></html>