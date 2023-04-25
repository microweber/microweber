<div class="mx-2 my-2">
    <?php if (!$terms) { ?>
    <?php _e('You don\'t have any accepted terms and conditions'); ?>
<?php } else { ?>
    <table class="mw-ui-table mw-ui-table-basic">
        <?php foreach ($terms as $term): ?>
        <tr>
            <td>
                <strong title="<?php print ($term['tos_name']); ?>"><?php print titlelize($term['tos_name']); ?></strong>
                <?php _e('is accepted on'); ?> <?php print ($term['created_at']); ?>  <?php _e('from ip'); ?> <?php print ($term['user_ip']); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php } ?>
</div>
