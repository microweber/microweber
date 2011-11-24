<?php $more_dates = false;

if(empty($more)){
$more = $post['custom_fields'];

}

// var_dump($more);
if(!empty($more)){

asort($more);


foreach($more as $k => $v){
	if(stristr($k, 'date_')){
		$more_dates[] = $v;

	}

}
}
if(!empty($more_dates)){
	//$more_dates = '&dates='.base64_encode(serialize($more_dates));
} else {
$more_dates = false;

}

//var_dump($more_dates);

?>
<?php if(!empty($more_dates)) : ?>
<div class="dates">

<strong class="label">Дати на заминаване</strong>
<ul>
<?php foreach($more_dates as $d) { ?>


  <li><?php echo date('j.m.Y',strtotime($d));  ?></li>



<?php }  ?>
</ul>
</div>
<?php endif; ?>