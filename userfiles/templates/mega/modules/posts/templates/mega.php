<?php

/*

type: layout

name: Mega

description: Mega

*/
?>

<div class="mw-blog">
	<?php if (!empty($data)): ?>
	<?php foreach ($data as $item): ?>
	<div class="bbox mw-blog-single-post">
		<div class="bbox-content">
			<?php if(!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
			<h3 class="blue"><a class="blue" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
			<?php endif; ?>
			<div class="post-info">
				<?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
				<small class="muted">
				<?php
                              $date = new DateTime($item['created_on']);
                              print $date->format('F d, Y');
                            ?>
				</small>
				<?php $author = get_user($item['created_by']);  ?>
				<span class="post-author"> <img src="<?php print thumbnail($author['thumbnail'], 19, 19); ?>" alt="" /> <span><?php print user_name($item['created_by']); ?> </span> </span>
				<?php $cats = mw('category')->get_for_content($item['id']);  ?>
				<?php
                             if(is_array($cats)){
                                 $html = '<span class="post-cats"><i class="icon-tag"></i>';
                                 foreach($cats as $cat){
                                    $html .= '<a class="muted" href="'.category_link($cat['id']).'">'.$cat['title'].'</a>, ';
                                 }
                                 $html .= '</span>';
                                 print $html;
                              }
                          ?>
				<?php endif; ?>
			</div>
			<?php if(!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
			<a href="<?php print $item['link'] ?>"><img src="<?php print $item['image']; ?>" alt="<?php print $item['title'] ?>" title="<?php print $item['title'] ?>" ></a>
			<?php endif; ?>
			<?php if(!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
			<p class="description"><?php print $item['description'] ?></p>
			<?php endif; ?>
			<?php if(!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
			<a href="<?php print $item['link'] ?>" class="btn-link pull-right">
			<?php $read_more_text ? print $read_more_text : _e("Continue Reading"); ?>
			</a>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<?php print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
<?php endif; ?>
