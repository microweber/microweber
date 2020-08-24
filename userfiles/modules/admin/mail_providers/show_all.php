<?php must_have_access(); ?>

<?php
$mail_providers = get_modules('type=mail_provider');
?>

<?php if (!empty($mail_providers)): ?>
    <?php foreach ($mail_providers as $key => $provider): ?>
        <div class="card style-1 mb-3 card-collapse">
            <div class="card-header no-border" data-toggle="collapse" data-target="#mail_provider-<?php echo $key; ?>">
                <h6 class="font-weight-bold"><?php print $provider['name'] ?></h6>
            </div>
            <div class="card-body collapse" id="mail_provider-<?php echo $key; ?>">
                <module type="<?php print $provider['module'] ?>" view="admin"/>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
