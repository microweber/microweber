<?php if (! empty ( $quick_nav )) : ?>
<?php	
  $i = 0;
  foreach ( $quick_nav as $item ) : ?>

<a href="<?php print $item ['url']; ?>">
<?php	print ucwords ( $item ['title'] );	?>
</a>
<?php if ($quick_nav[$i+1] == true) : ?>
<? print $seperator; ?>
<?php	endif; ?>
<?php	$i++; endforeach; ?>
<?php	endif; ?>
