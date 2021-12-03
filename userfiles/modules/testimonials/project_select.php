<?php
if (!isset($params['option-group'])) {
    return;
}

$testimonials_data = get_testimonials("group_by=project_name");
$selected = get_option('show_testimonials_per_project', $params['option-group']);

if (empty($selected)) {
    $selected = '';
    if (isset($params['project_name']) && !empty($params['project_name'])) {
        $selected = $params['project_name'];
    }
}
$data = [];
$data[] = [
    'project_key'=>false,
    'project_name'=>'All projects',
];
if (!empty($testimonials_data)) {
    foreach($testimonials_data as $testimonial) {
        $data[] = [
            'project_key'=>$testimonial['project_name'],
            'project_name'=>$testimonial['project_name'],
        ];
    }
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#<?php print $params['id'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
            mw.reload_module("testimonials/admin");
        });
    });
</script>
<?php if (!empty($data)): ?>
    <select class="form-control mw_option_field" name="show_testimonials_per_project" option-group="<?php print $params['option-group'] ?>">
        <?php foreach ($data as $item): ?>
            <?php if ($item['project_name'] != ''): ?>
                <option <?php if ($item['project_name'] == $selected): ?> selected="selected" <?php endif; ?> value="<?php print $item['project_key'] ?>"><?php print mw()->format->titlelize($item['project_name']) ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
<?php endif; ?>
