<?php

$uid = user_id();
if (isset($params['user_id']) and is_admin()) {
    $uid = $params['user_id'];
}
$terms = false;
if ($uid) {
    $terms_params = array();
    $terms_params['user_id'] = $uid;

    $terms = new \Microweber\Providers\Users\TosManager();
    $terms = $terms->get($terms_params);


}


?>

<?php if (!$terms) { ?>

    You don't have any accepted terms and conditions
<?php } else { ?>

    <table class="mw-ui-table mw-ui-table-basic">
        <?php foreach ($terms as $term): ?>
            <tr>
                <td>



<strong title="<?php print ($term['tos_name']); ?>"><?php print titlelize($term['tos_name']); ?></strong>
                    is accepted on <?php print ($term['created_at']); ?> from ip <?php print ($term['user_ip']); ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>


<?php } ?>

