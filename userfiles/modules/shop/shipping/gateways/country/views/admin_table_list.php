<?php if (is_array($data) and !empty($data)): ?>
    <div class=" ">
        <?php if($active_or_disabled == 'active'): ?>
        <p class="pull-left p-t-10">List of enabled countries</p>
        <?php else: ?>
            <p class="pull-left p-t-10">List of disabled countries</p>
        <?php endif; ?>

        <div class="mw-shipping-items" id="shipping_to_country_holder">
            <?php foreach ($data as $item): ?>
                    <?php include __DIR__ . "/item_edit.php"; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
