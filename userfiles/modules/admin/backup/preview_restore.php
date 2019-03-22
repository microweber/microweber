<?php only_admin_access(); ?>

<?php


$file_id = false;


if (isset($params['file'])) {
    $file_id = $params['file'];
}


if (!$file_id) {
    return;
}


$backup_file_preview_params = array();
$backup_file_preview_params['id'] = $file_id;
$backup_file_preview_params['preview_restore'] = true;


$backup_manager = new \Microweber\Utils\Backup();


$data_to_unzip = $backup_manager->exec_restore($backup_file_preview_params);


$arr_display_keys = array(
    'id',
    'title',
    'name',
    'content_type',
    'subtype',
    'parent_id',
    'rel_type',
    'rel_id',
    'option_key',
    'option_value',
    'value',
    'content',
    'content_body',
    'content_id',
);

?>
<?php if ($data_to_unzip) { ?>


    <?php foreach ($data_to_unzip as $group => $data) { ?>
        <?php if (is_array($data)) { ?>

            <h3 onclick="$('#table-group-toggle-<?php print $group ?>').toggle()"><?php print $group ?></h3>
            <?php $first_data_item = reset($data) ?>

            <table class="mw-ui-table" id="table-group-toggle-<?php print $group ?>">
                <thead>
                <tr>
                    <th scope="row"><?php print $group ?></th>

                    <?php $printed_col_n = 0; ?>
                    <?php foreach ($arr_display_keys as $dk) : ?>

                        <?php if (isset($first_data_item[$dk]) and $first_data_item[$dk]) : ?>
                            <th scope="col">
                                <?php print $dk ?>
                                <?php $printed_col_n++; ?>
                            </th>
                        <?php endif; ?>

                    <?php endforeach; ?>


                </tr>
                </thead>


                <tbody>


                <?php foreach ($data as $k => $item) : ?>


                    <?php if (isset($item['id'])) : ?>

                        <tr>
                            <th scope="row">
                                <input type="checkbox" name="table[<?php print $group ?>]"
                                       value="<?php print $item['id'] ?>"/></th>


                            <?php $printed_col_n = 0; ?>

                            <?php foreach ($arr_display_keys as $dk) : ?>

                                <?php if (isset($item[$dk]) and $item[$dk]) : ?>
                                    <td><?php print strip_tags_content($item[$dk]) ?></td>
                                <?php endif; ?>


                            <?php endforeach; ?>


                        </tr>


                    <?php endif; ?>


                <?php endforeach; ?>

                </tbody>
            </table>

        <?php } ?>

    <?php } ?>


<?php } ?>


preview_restore.php