<?php if (! empty ( $quick_nav )) : ?>

<ul class="breadcrumb">
  <?php	foreach ( $quick_nav as $item ) : ?>
  <li><a href="<?php print $item ['url']; ?>">
    <?php	print ucwords ( $item ['title'] );	?>
    </a></li>
  <?php	endforeach; ?>
</ul>
<?php	endif; ?>
