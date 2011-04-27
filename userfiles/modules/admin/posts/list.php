<?


 
$posts = get_posts($params);
 //p($posts);
?>
<? if(!empty($posts)): ?>
<? $posts_list =$posts['posts'];  ?>
<? foreach($posts_list as $the_post):  ?>

<div class="post item" id="post_<? print $the_post['id'] ?>">
  <input type="checkbox" class="checkselector" onclick="posts_categorize(this);" />
  <a href="<? print ADMIN_URL ?>/action:post_edit/id:<? print $the_post['id'] ?>" class="img"> <img src="<? print thumbnail($the_post['id'], 150) ?>" /> </a>
  <h2><? print $the_post['content_title'] ?> </h2>
  
  
  
  
  
  <? $c = CI::model ( 'taxonomy' )->getTaxonomiesForContent($the_post['id'], $taxonomy_type = 'categories'); 
 print $the_post['content_description']  ;
 
 ?>
 
 <? $stats = CI::model ( 'stats' )->get_visits_by_url(post_link($the_post['id'])); ?>
 Views: <? print $stats ?>
 
  <? $comments = CI::model ( 'comments' )->commentsGetCountForContentId(($the_post['id'])); ?>
 Comments: <? print $comments ?>
 
 
  <? print $the_post['content_description'] ?> <a class="btn2" href="<? print  post_link($the_post['id']);  ?>" target="_blank">Read</a> <a class="btn2" href="<? print ADMIN_URL ?>/action:post_edit/id:<? print $the_post['id'] ?>">Edit</a> <a class="btn2" href="#" onclick="mw.content.del('<? print $the_post['id'] ?>','#post_<? print $the_post['id'] ?>');">Delete</a>
  <div class="post_info">
    <div class="post_title"><? print $the_post['content_title'] ?></div>
    <div class="post_id"><? print $the_post['id'] ?></div>
  </div>
</div>
<? endforeach; ?>

<? if($params['keyword'] == false):?>

<div class="paging">
  <? $i=1; foreach($posts['posts_pages_links']  as $paging): ?>
  <a href="<? print $paging; ?>" <?  if($posts['posts_pages_curent_page'] == $i): ?> class="active"  <? endif; ?>  ><? print $i ?></a>
  <? $i++; endforeach; ?>
</div>
<? endif; ?>


<? else: ?>
Nothing found
<? endif; ?>
