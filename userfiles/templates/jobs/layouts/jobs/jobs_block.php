<? foreach($posts as $post): ?>

<div class="searched_job_blk">
  <div class="searched_job_tit"><a href="<? print post_link($post['id']) ?>"><? print $post['content_title'] ?></a></div>
  <div class="searched_job_desc"><? print $post['content_body_nohtml'] ?></div>
  <div class="searched_job_add_buts">
    <div class="searched_job_location"> Date Posted:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $post['created_on'] ?></span><br />
      <?  $v = cf_val($post['id'], $field_name = 'Location') ;?>
      <? if($v != false): ?>
      Location:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $v; ?> </span> <br />
      <? endif; ?>
      <?  $v = cf_val($post['id'], $field_name = 'sallary-range') ;?>
      <? if($v != false): ?>
      Salary:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $v; ?></span> <br />
      <? endif; ?> 
      
          <? // $c = $this->taxonomy_model->getTaxonomiesForContent($post['id'], $taxonomy_type = 'categories'); 
 
 
 ?>
 <? if(!empty($c )): ?>

 Categories: 
<? $i = 0; foreach($c as $cx): ?>
<? $cx1 = get_category($cx);  ?>
 <? if(stristr($cx1['taxonomy_value'], 'jobs') == false): ?>


<a class="blue" href="<? print category_url($cx1['id'] ) ?>"><? print $cx1['taxonomy_value'] ?></a>,
  
<? endif; ?>
<? endforeach; ?>
 
<? endif; ?>



       
       
      
      
    </div>
    <div class="readmore_but"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/readmore_but.jpg" alt="read more" border="0" /></a></div>
    <div class="apply_but"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/apply_but.jpg" alt="apply" border="0" /></a></div>
  </div>
</div>
<? endforeach;  ?>
