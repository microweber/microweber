<?php

/*

type: layout

name: Team

description: Team

*/
?>


<?php if (!empty($data)): ?>
<div class="thumbnails thumbnails_3">
<?php foreach ($data as $item): ?>
	<div class="span2 col-sm-2 col-md-2">
		<div class="content-item-team">
			<?php if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
			<a href="<?php print $item['link'] ?>">
				<figure class="img-polaroid"><img src="<?php print thumbnail(TEMPLATE_URL."img/team.jpg", 159); ?>" alt=""></figure>
			</a>
			<?php endif; ?>
			<div class="team-item-info">
				<?php if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
					<p><strong>
		                <small class="muted"><?php print $item['created_on'] ?></small>
					</strong></p>
	           	<?php endif; ?>
				<?php if($show_fields == false or in_array('title', $show_fields)): ?>
				<a href="<?php print $item['link'] ?>" class="lead"><?php print $item['title'] ?></a><br>
				<?php endif; ?>
				<?php if($show_fields == false or in_array('description', $show_fields)): ?>
				<p><?php print $item['description'] ?></p>
				<?php endif; ?>
				<?php if($show_fields == false or in_array('read_more', $show_fields)): ?>
				<a class="btn btn-default" href="<?php print $item['link'] ?>"> <?php $read_more_text ? print $read_more_text : print _e("Read more", true) . '...'; ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&curent_page={$curent_page}") ?>
<?php endif; ?>
