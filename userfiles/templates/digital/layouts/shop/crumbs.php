		   <ul class="crumbs">
	
	      <? foreach ($active_categories as $cat) : ?>
          <li><a href="<? print category_url($cat); ?>"><? print category_name($cat); ?></a></li>
          
          <? endforeach; ?>
</ul>