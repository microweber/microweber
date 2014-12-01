<?php

/*

type: layout

name: Blog

description: Blog

*/
?>




<div class="post-list mw-post-list-blog">
<style scoped>
   .mw-post-list-blog-content .lead{
     margin-top: 0;
   }

</style>

  <?php if (!empty($data)): ?>
  <?php foreach ($data as $item): ?>
  <div class="well clearfix post-single" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
      <div class="row">
          <?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
            <div class="col-xs-3">
                <a  href="<?php print $item['link'] ?>" itemprop="url"><img src="<?php print thumbnail($item['image'], 270); ?>" alt="<?php print addslashes($item['title']); ?>" itemprop="image" ></a>
            </div>
          <?php endif; ?>
          <div class="col-xs-9">
              <div class="mw-post-list-blog-content">
                  <div class="post-single-title-date">
                      <?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                        <h2 class="lead"><a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h2>
                      <?php endif; ?>
                      <?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
                        <small class="muted"><?php _e("Date"); ?>: <?php print $item['created_on'] ?></small>
                      <?php endif; ?>
                  </div>
                  <?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                    <p class="description" itemprop="headline"><?php print $item['description'] ?></p>
                  <?php endif; ?>

                  <?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                      <a href="<?php print $item['link'] ?>" class="btn btn-default">
                          <?php $read_more_text ? print $read_more_text : _e("Continue Reading"); ?>
                      </a>
                  <?php endif; ?>
              </div>
          </div>
      </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
    
 <?php endif; ?>
