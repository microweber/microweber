<?php dbg(__FILE__); ?>
<?php $type = $this->core_model->getParamFromURL ( 'type' ); ?>

<?php $params = array();
 $params['keyword'] = 'inherit';
 $params['type'] = 'remove';
 //$params['type'] = 'inherit';
 $url = $this->core_model->urlConstruct($base_url = site_url('userbase'),$params);


//p($url);

?>




<div class="main">
  <div id="top-inner-title">
    <h2 class="title">Users</h2>
  </div>
  <div class="users-search-bar">
    <ul class="users-search-nav">
    
     <li  <?php if(($type == 'all') or (!$type)): ?> class="active" <?php endif; ?> ><a href="<?php print $url ?>">All users</a></li>
  
     <li  <?php if(($type == 'top-contributors') ): ?> class="active" <?php endif; ?> ><a href="<?php print $url ?>/type:top-contributors">Top contributors</a></li>
     
     <li  <?php if(($type == 'top-comentators') ): ?> class="active" <?php endif; ?> ><a href="<?php print $url ?>/type:top-comentators">Top comentators</a></li>

      
    </ul>
    <div class="c border-bottom">&nbsp;</div>
    <form action="<?php print site_url('/userbase/users_do_search') ; ?>" method="post" enctype="multipart/form-data" class="xform">
      
      <!--   <input class="cinput" type="text" value="Name"  onblur="this.value==''?this.value='Name':''" onfocus="this.value=='Name'?this.value='':''" />
      <input class="cinput" type="text" value="Other" onblur="this.value==''?this.value='Other':''" onfocus="this.value=='Other'?this.value='':''"  />
      <input class="cinput" type="text" value="Other" onblur="this.value==''?this.value='Other':''" onfocus="this.value=='Other'?this.value='':''"  />
      <a href="#" class="submit">Filter</a>-->
      
      <div id="tsearch">
        <input value="<?php print $search_by_keyword?>" name="search_by_keyword" type="text" class="type-text"  />
        <input type="submit" value="" class="type-submit" />
      </div>
    </form>
  </div>
  <!-- /.users-search-bar-->
  <?php foreach($users_list as $item): ?>
  <div class="post" style="padding-left:0"> <a href="<?php print site_url('userbase/action:profile/username:').$item['username'] ?>" class="eimg"> <span style="background-image: url('<?php print   $thumb = $this->users_model->getUserThumbnail($item['id'], 80); ?>');"></span> </a>
    <h2 class="post-title"><a href="<?php print site_url('userbase/action:profile/username:').$item['username'] ?>"><?php print $this->users_model->getPrintableName (  $item['id'], 'full' ); ?></a></h2>
    <p> <?php print (character_limiter(strip_tags( html_entity_decode($item['user_information'])), 300, '...')); ?></p>
  </div>
  <?php endforeach; ?>
  <?php if(!empty($content_pages_links)): ?>
  <div class="pad">
    <ul class="paging">
      <li><span class="paging-label">Browse pages:</span></li>
      <li class='isQuo'><a href='#' title="First page" class='quo laquo2'><span>&nbsp;</span></a></li>
      <li class='isQuo'><a href='#' title="Previous page" class='quo laquo'><span>&nbsp;</span></a></li>
      <li>
        <ul class="paging-content">
          <?php $i = 1; foreach($content_pages_links as $page_link) : ?>
          <li><a <?php if($content_pages_curent_page == $i) : ?>  class="active"  <?php endif; ?>  href="<?php print $page_link ;  ?>"   ><?php print $i ;  ?></a></li>
          <?php $i++; endforeach;  ?>
        </ul>
      </li>
      <li class='isQuo'><a href='#' title="Next page" class='quo raquo'><span>&nbsp;</span></a></li>
      <li class='isQuo'><a href='#' title="Last page" class='quo raquo2'><span>&nbsp;</span></a></li>
    </ul>
  </div>
  <?php endif; ?>
</div>
<!-- /.main-->
<div class="sidebar">
  <div class="pad border-bottom">
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_how_to_box.php') ?>
  </div>
  <div style="width:303px;margin:auto">
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_learning_center.php') ?>
     <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_products.php') ?>
  </div>
   </div>
<!-- /.sidebar -->
<?php //require (ACTIVE_TEMPLATE_DIR.'users/userbase/right_sidebar.php') ?>
<?php dbg(__FILE__, 1); ?>
