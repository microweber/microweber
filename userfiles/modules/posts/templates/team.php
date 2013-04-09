<?php

/*

type: layout

name: Team

description: Team

*/
?>


<? if (!empty($data)): ?>
<ul class="thumbnails thumbnails_3">
<? foreach ($data as $item): ?>
	<li class="span2">
		<div class="element">
			
			
			
			
			<? if($show_fields == false or in_array('thumbnail', $show_fields)): ?>
			<a href="<? print $item['link'] ?>">
				<figure class="img-polaroid"><img src="<?php print thumbnail(TEMPLATE_URL."img/team.jpg", 159); ?>" alt=""></figure>
			</a>
			<? endif; ?>
			<div class="team-item-info">
				<? if(!isset($show_fields) or $show_fields == false or in_array('created_on', $show_fields)): ?>
					<p><strong>
		                <small class="muted"><? print $item['created_on'] ?></small>
					</strong></p>
	           	<? endif; ?>
				<? if($show_fields == false or in_array('title', $show_fields)): ?>
				<a href="<? print $item['link'] ?>" class="lead"><? print $item['title'] ?></a><br>
				<? endif; ?>
				<? if($show_fields == false or in_array('description', $show_fields)): ?>
				<p><? print $item['description'] ?></p>
				<? endif; ?>
				<? if($show_fields == false or in_array('read_more', $show_fields)): ?>
				<a class="btn" href="<? print $item['link'] ?>"> <? $read_more_text ? print $read_more_text : print 'Read more...'; ?></a>
				<? endif; ?>
			</div>
		</div>
	</li>
<? endforeach; ?>
</ul>
<? endif; ?>

<? if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
<? print paging("num={$pages_count}&paging_param={$paging_param}") ?>
<? endif; ?>
