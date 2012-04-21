<?php   // p($data); ?>
<?php $author = $this->users_model->getUserById($data['user_id']); ?>
<?php $thumb = $this->users_model->getUserThumbnail( $data['user_id'], 70); ?>
<?php //$data =  $this->core_model->getById($data['to_table'],$data['to_table_id']); ?>
<?php
 $addtinal_style = '';
  if(($data['to_table'] == 'table_votes') and ($data['to_table'] == 'table_followers')){
  $addtinal_style = "display:none;";
 //$this->users_model->dataDeleteById($data['id']);
  }
  
   if(($data['to_table'] == 'table_votes') and ($data['to_table'] == 'table_comments')){
  $addtinal_style = "display:none;";
 //$this->users_model->dataDeleteById($data['id']);
  }
  
   if(($data['to_table'] == 'table_votes') and ($data['to_table'] == 'table_users_statuses')){
  $addtinal_style = "display:none;";
// $this->users_model->dataDeleteById($data['id']);
  }
  
  if(($data['to_table'] == 'table_votes') and ($data['to_table'] == 'table_users')){
  $addtinal_style = "display:none;";
// $this->users_model->dataDeleteById($data['id']);
  }
  
   if(($data['to_table'] == 'table_votes') and ($data['to_table'] == 'table_votes')){
  $addtinal_style = "display:none;";
// $this->users_model->dataDeleteById($data['id']);
  }
  

  
  
  
   if(($data['to_table'] == 'table_options')){
	$addtinal_style = "display:none;";
 //$this->users_model->dataDeleteById($data['id']);
   }
   
    if(($data['to_table'] == 'table_users_statuses')){
	//$addtinal_style = "display:none;";
  //$this->users_model->dataDeleteById($data['id']);
   }
   
   
   
 ?>
<? 
$log_check = get_log_item($data['id']);
 

if(!empty($log_check )): ?>
 

<li <? print $addtinal_style ?> id="log_item_<? print $log_check['id'] ?>"> <a href="<?php print site_url('userbase/action:profile/username:'.$author['username']) ?>" class="user_photo" style="background-image: url('<?php print $thumb; ?>')"></a> <a href="<?php print site_url('userbase/action:profile/username:'.$author['username']) ?>" class="mw_blue_link"><? print user_name($author['id']); ?></a>
  <? get_dashboard_action($data['id']); ?>
  <div class="user_activity_bar">
       <date><?php print ago($log_check['created_on']) ?>
       
 
       <? if($log_check['created_by'] == user_id()): ?>
 <a class="comment_delete" href="javascript:mw.users.log_delete(<? print $log_check['id'] ?>, '#log_item_<? print $log_check['id'] ?>');">delete</a>

 

   <? endif; ?>
       </date>
       
       
       
   
   
   

    
    
    
    
    
    
    
    <div>  <a href="#" class="user_activity_comments"><strong><? print comments_count($log_check['rel_table_id'], false); ?></strong><span></span><strong>Comments</strong></a> <a  class="user_activity_likes right"  href="<? print voting_link($log_check['to_table_id'], '#post-likes-'.$log_check['to_table_id'], $log_check['to_table']); ?>"><strong id="post-likes-<? print ($log_check['to_table_id']); ?>"><? print votes_count($log_check['to_table_id'], false,$log_check['to_table'] ); ?></strong> Love</a> </div>
  </div>
  <? $update_element = md5(serialize($log_check));
  $this->template ['comments_update_element'] = $update_element;
	$this->load->vars ( $this->template );
  ?>
  <? comment_post_form($log_check['rel_table_id'],'dashboard/index_item_comments.php', $log_check['rel_table'])  ?>
  <div id="<? print $update_element ?>">
    <? comments_list($log_check['rel_table_id'], 'dashboard/index_item_comments_list.php', $log_check['rel_table'])  ?>
  </div>
  <!--<div class="comment_area">
    <div class="comment_area_content">
      <form method="post" action="#">
        <a href="#" class="user_photo" style="background-image: url(<?php print TEMPLATE_URL; ?>/static/img/demo/userbox.jpg)"></a>
        <textarea>Write a comment...</textarea>
        <a href="#" class="submit">Submit</a>
      </form>
    </div>
  </div>
  <br />
  <span class="user_like"></span><a href="#" class="mw_blue_link">You</a>, <a href="#" class="mw_blue_link">Krasimira Avramova</a> and <a href="#" class="mw_blue_link">Dimitar Bozhanov</a> like this. <br />
  <br />
  <div class="comment"> <a href="#" class="user_photo" style="background-image: url(<?php print TEMPLATE_URL; ?>/static/img/demo/userbox.jpg)"></a> <a href="#" class="mw_blue_link">Peter Most</a> said:<br />
    Lorem Ipsum is simply dummy text of the printing and
    typesetting industry. Lorem Ipsum has been the
    industry's standard dummy text ever since the 1500s </div>-->
</li>
<? endif; ?>
