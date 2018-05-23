

<script>
//    $(document).ready(function () {
//        $('.toggle-item', '#<?php //print $params['id'] ?>//' ).on('click', function (e) {
//             alert(1);
//            if ($(e.target).hasClass('toggle-item') || (e.target).nodeName == 'TD') {
//                $(this).find('.hide-item').toggleClass('hidden');
//                $(this).closest('.toggle-item').toggleClass('closed-fields');
//            }
//        });
//    });
</script>

<?php if (is_array($data) and !empty($data)): ?>
    <div class="mw-ui-row m-t-20">
        <?php if ($active_or_disabled == 'active'): ?>
            <p class="disabled-and-enabled-label"><?php print _e('List of enabled countries'); ?></p>
        <?php else: ?>
            <p class="disabled-and-enabled-label"><?php print _e('List of disabled countries'); ?></p>
        <?php endif; ?>

        <div class="mw-shipping-items" id="shipping_to_country_holder">
            <?php foreach ($data as $item): ?>
                <?php include __DIR__ . "/item_edit.php"; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
