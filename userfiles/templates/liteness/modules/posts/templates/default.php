<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="clearfix container module-posts-template-columns">

		<?php if (!empty($data)): ?>
        <?php $len = count($data); ?>
		<?php $count = 0; foreach ($data as $item): ?>
        <?php
            $count++;
            if($count == 1 or  ($count-1) % 3 == 0){
              print '<div class="row">';
			 }
        ?>

		<div class="col-sm-4" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
			<?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
			<a itemprop="url" class="module-posts-image" href="<?php print $item['link'] ?>"> <span class="valign"><span class="valign-cell"><img src="<?php print thumbnail($item['image'], 320,320); ?>" alt="<?php print addslashes($item['title']); ?> - <?php _e("image"); ?>" title="<?php print addslashes($item['title']); ?>" itemprop="image" /></span></span> </a>
			<?php endif; ?>
			<div class="module-posts-head">
				<?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
				<h3 itemprop="name"><a class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
				<?php endif; ?>
				<?php if(!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
				<small class="muted">Posted on: <span itemprop="dateCreated"><?php print $item['created_at'] ?></span></small>
				<?php endif; ?>
			</div>
			<?php if(!isset($show_fields) or  ($show_fields == false or in_array('description', $show_fields))): ?>
			<p class="description" itemprop="headline"><?php print $item['description'] ?></p>
			<?php endif; ?>
			<?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
			<div class="blog-post-footer"> <a href="<?php print $item['link'] ?>" class="btn btn-default pull-fleft" itemprop="url">
				<?php $read_more_text ? print $read_more_text : print _e('Continue Reading', true); ?>
				<i class="icon-chevron-right"></i></a> </div>
			<?php endif; ?>
		</div>

        <?php
            if(($count%3)==0 or  $len == $count){
			   print '</div>';
             }
         ?>
		<?php endforeach; ?>
		<?php endif; ?>

	<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
	<?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
	<?php endif; ?>
</div>
