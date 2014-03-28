<?php

if (!isset($data['id'])) {
    include('empty_field_vals.php');
}

$is_required = (isset($data['options']) == true and is_array($data['options']) and in_array('required', $data['options']) == true);
$skips = array();
if (isset($params['skip-fields']) and $params['skip-fields'] != '') {
    $skips = explode(',', $params['skip-fields']);
    $skips = array_trim($skips);
}

if (!is_array($data['custom_field_values'])) {
    $default_data = array('country' => 'Country', 'city' => 'City', 'zip' => 'Zip/Post code', 'state' => 'State/Province', 'address' => 'Address');
    $data['custom_field_values'] = $default_data;
}

if (!isset($data['input_class']) and isset($params['input-class'])) {
    $data['input_class'] = $params['input-class'];
} elseif (!isset($data['input_class']) and  isset($params['input_class'])) {
    $data['input_class'] = $params['input_class'];
} else {
    $data['input_class'] = 'form-control';

}
?>

<?php if (is_array($data['custom_field_values'])) : ?>
    <div class="control-group form-group mw-custom-field-address-control">

        <?php if (isset($data['name']) == true and $data['name'] != ''): ?>


        <?php elseif (isset($data['custom_field_name']) == true and $data['custom_field_name'] != ''): ?>



        <?php else : ?>
        <?php endif; ?>
        <?php if (isset($data['help']) == true and $data['help'] != ''): ?>
            <small class="mw-custom-field-help"><?php print $data['help'] ?></small>
        <?php endif; ?>
        <?php foreach ($data['custom_field_values'] as $k => $v): ?>
            <?php if (is_array($skips)
                and (!in_array($data['custom_field_name'], $skips)
                and !in_array($k, $skips))
            ) : ?>

                <?php if (is_string($v)) {
                    $kv = $v;
                } else {
                    $kv = $v[0];
                }
                if ($kv == '') {
                    $kv = ucwords($k);
                }
                ?>
                <div class="control-group">
                <label><?php print ($kv); ?></label>
                <input
                    type="text"  <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>
                    name="<?php print $data['custom_field_name'] ?>[<?php print ($k); ?>]" <?php if ($is_required) { ?> required <?php } ?>
                    data-custom-field-id="<?php print $data["id"]; ?>"/>
                    </div>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>

<?php endif; ?>
