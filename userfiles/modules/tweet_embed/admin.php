<?php only_admin_access(); ?>

<?php
$option_group = $params['id'];

if (isset($params['option-group'])) {
    $option_group = $params['option-group'];
}

$twitter_url = get_option('twitter_url', $option_group);
?>


<div class="module-live-edit-settings">
    <style scoped="scoped">
        .module-tweet-embed-settings-table .mw-ui-inline-label {
            margin-right: 0;
            padding-right: 3px;
            width: 120px;
            text-align: right;
        }
    </style>

    <table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic module-tweet-embed-settings-table">
        <thead>
        <tr>
            <th><?php _e('URL'); ?></th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>
                <label class="mw-ui-inline-label">Tweet Post URL</label>
                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="twitter_url" value="<?php print $twitter_url; ?>"/>
            </td>
        </tr>
        </tbody>
    </table>
</div>