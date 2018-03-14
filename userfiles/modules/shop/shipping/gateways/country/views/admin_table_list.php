<?php if (is_array($data) and !empty($data)): ?>
    <div class=" m-t-20">
        <?php if ($active_or_disabled == 'active'): ?>
            <p class="disabled-and-enabled-label">List of enabled countries</p>
        <?php else: ?>
            <p class="disabled-and-enabled-label">List of disabled countries</p>
        <?php endif; ?>

        <div class="mw-shipping-items" id="shipping_to_country_holder">
            <?php foreach ($data as $item): ?>
                <?php include __DIR__ . "/item_edit.php"; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
