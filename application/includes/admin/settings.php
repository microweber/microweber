<?  $option_groups = get_option_groups(); 

//d( );
?>
<ul>
<? foreach($option_groups as $item): ?>
<li><? print $item ?></li>

<? endforeach; ?>
</ul>