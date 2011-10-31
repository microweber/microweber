 <? $th = thumbnail($post['id'], 200); ?><meta property="og:title" content="<? print addslashes( $post['content_title']); ?>" />
<meta property="og:description" content="<? print  addslashes($post['content_body_nohtml']); ?>" />
<meta property="og:image" content="<? print  ($th); ?>" />
<div class="author-content2">
  <h2 class="post_title"> <a href="<? print  post_link($post['id']); ?>"><? print  $post['content_title']; ?></a></h2>
  <br>
  <? print  ($post['content_body']); ?> <br>
</div>
<h2 class="post_title">Предай нататък</h2>
<br>
<table border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#appId=277466575616612&xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like" data-href="<? print  post_link($post['id']); ?>" data-send="true" data-width="450" data-show-faces="true" data-font="trebuchet ms"></div></td>
<td ><script>var fbShare = {
url: '<? print  post_link($post['id']); ?>',
size: 'large',
badge_text: 'C0C0C0',
badge_color: 'bc6506',
google_analytics: 'false'
}</script>
<script src="http://widgets.fbshare.me/files/fbshare.js"></script></td>
    <td><a href="http://svejo.net/submit/?url=<? print  post_link($post['id']); ?>"
     data-url="<? print  post_link($post['id']); ?>"
     data-type="standard"
     id="svejo-button">Добави в Svejo</a>
<script type="text/javascript" src="http://svejo.net/javascripts/svejo-button.js"></script></td>
<td><script type="text/javascript">
 btntype = 2; 
 col1 = '#FFFFFF'; 
 blog_id = 0; 
 url = '<? print  post_link($post['id']); ?>'; 
</script> 
<script src="http://topbloglog.com/js/votebtn.js" type="text/javascript"></script></td>
<td><a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-via="bozhanov">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></td>
  </tr>
</table>
<br>




<h2 class="post_title">Ти какво мислиш? Сподели тук:</h2>
<br>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-comments" data-href="<? print  post_link($post['id']); ?>" data-num-posts="50" data-width="650"></div>
