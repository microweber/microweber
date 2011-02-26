<h1><?=t('Reminders')?></h1>
<?php if(isset($special)) { ?><h2 class="special"><?=ucfirst($special)?></h2><?php } ?>

<?php include('_listing.php'); ?>
