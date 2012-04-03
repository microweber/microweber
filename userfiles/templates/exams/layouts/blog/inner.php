<div class="posts-list ">
  <div class="single-post">
    <h1   class="post-title"> <a   class="post-title"  href="<? print post_link($post['id']); ?>"  itemprop="name"><? print $post['content_title'] ?></a></h1>
    <div class="single-post-info"> <span class="single-post-author">by <span itemprop="author"><? print user_name($post['created_by']); ?></span></span> | <span class="single-post-date">published on <span itemprop="datePublished"><? print ($post['created_on']); ?></span></span> </div>
    <editable rel="post" field="content_body"></editable>
  </div>
  <br />
  <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
  <h2>Share this with your friends</h2>
  <br />
  <br />
  <div id="fb-root"></div>
  <script src="http://connect.facebook.net/en_US/all.js#appId=217556338283822&amp;xfbml=1"></script>
  <fb:like href="<? print post_link($post['id']); ?>" send="true" width="450" show_faces="true" font="verdana"></fb:like>
  <br />
  <br>
  
    <table border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td><div class="sidebar_subscribe_google">
          <!-- Place this tag where you want the +1 button to render -->
          <g:plusone size="standard" count="true" href="<? print post_link($post['id']); ?>" ></g:plusone></div></td>
        <td> <div class="sidebar_subscribe_tw"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="Microweber">Tweet</a>
          <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div></td>
          
          
            <td> <script src="http://www.stumbleupon.com/hostedbadge.php?s=2&r=<? print post_link($post['id']); ?>"></script></td>
            
            
             <td> <script type="text/javascript">
        (function() {
          var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
          s.type = 'text/javascript';
          s.async = true;
          s.src = 'http://widgets.digg.com/buttons.js';
          s1.parentNode.insertBefore(s, s1);
        })();
        </script>
        <a class="DiggThisButton DiggCompact"></a></td>
          
          
           <td>  <img src="http://l.yimg.com/hr/img/delicious.small.gif" height="10" width="10" alt="Delicious" />
<a href="http://www.delicious.com/save" onclick="window.open('http://www.delicious.com/save?v=5&noui&jump=close&url='+encodeURIComponent('<? print post_link($post['id']); ?>')+'&title='+encodeURIComponent(document.title), 'delicious','toolbar=no,width=550,height=550'); return false;"><small>Delicious</small></a></td>
      </tr>
    </table>
  
 
     <br />
    <br />
 
   <br />
 
  <h2>Tell us what you think</h2>
  <br />
  <br />
  
  <div id="fb-root"></div>
  <fb:comments href="<? print url(); ?>" num_posts="20" width="650"></fb:comments>
</div>
