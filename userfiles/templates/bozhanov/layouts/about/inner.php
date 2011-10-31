 <? $th = thumbnail($post['id'], 200); ?><meta property="og:title" content="<? print addslashes( $post['content_title']); ?>" />
<meta property="og:description" content="<? print  addslashes($post['content_body_nohtml']); ?>" />
<meta property="og:image" content="<? print  ($th); ?>" />
<div class="author-content2">
  <h2 class="post_title"> <a href="<? print  post_link($post['id']); ?>"><? print  $post['content_title']; ?></a></h2>
  <br>
  <? print  ($post['content_body']); ?> <br>
</div>
  
  
  
          <div class="img">

                </div>
                
                    <h2 class="title">Пиши ми</h2>
                    
                     
                   <br /><br />

<? include TEMPLATE_DIR. "form.php"; ?>         
