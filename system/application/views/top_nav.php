<div class="lefts">
<ul>
	<li <?php
	if ($className == 'index') :
		?> class="active" 
	<?php endif;
	?>><a
		href="<?php print site_url ()?>">1</a></li>
	<li <?php
	if ($className == 'me' and $functionName == 'login') :
		?>
		class="active" 
	<?php endif;
	?>><a href="<?php print site_url ( 'me/login' )?>">login</a></li>
	<li <?php
	if ($className == 'me' and $functionName == 'register') :
		?>
		class="active" 
	<?php endif;
	?>><a
		href="<?php print site_url ( 'me/register' )?>">register</a></li>
	<li
		<?php
		if ($className == 'me' and $functionName == 'registdgdfer') :
			?>
		class="active" 
		<?php endif;
		?>><a
		href="<?php print site_url ( 'index/test' )?>">test</a></li>




</ul>
</div>