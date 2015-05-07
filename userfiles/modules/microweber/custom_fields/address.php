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

if (!is_array($data['values'])) {
    $default_data = array('country' => 'Country', 'city' => 'City', 'zip' => 'Zip/Post code', 'state' => 'State/Province', 'address' => 'Address');
    $data['values'] = $default_data;
}

if (!isset($data['input_class']) and isset($params['input-class'])) {
    $data['input_class'] = $params['input-class'];
} elseif (!isset($data['input_class']) and  isset($params['input_class'])) {
    $data['input_class'] = $params['input_class'];
} else {
    $data['input_class'] = 'form-control';

}
if (!isset($data['options']) or !is_array($data['options']) or empty($data['options'])) {

    $data['options'] = array(
        'country' => _e('Country',true),
        'city' => _e('City',true),
        'address' => _e('Address',true),
        'state' => _e('State/Province',true),
        'zip' => _e('Zip/Postal Code',true)
    );
}

?> 
<?php if (is_array($data['values'])) : ?>
    <div class="mw-ui-field-holder">
        <?php if (isset($data['name']) == true and $data['name'] != ''): ?>
            <label class="mw-ui-label mw-address-label"><?php _e($data['name']) ?></label>
        <?php elseif (isset($data['name']) == true and $data['name'] != ''): ?>
        <?php else : ?>
        <?php endif; ?>
        <?php if (isset($data['help']) == true and $data['help'] != ''): ?>
            <small class="mw-ui-label"><?php print $data['help'] ?></small>
        <?php endif; ?>
        <?php foreach ($data['values'] as $k => $v): ?>
            <?php if (!in_array($k, $skips))  : ?>



                <?php if (is_string($v)) {
                    $kv = $v;
                } elseif (is_array($v)) {
                    $kv = $v[0];
                } else {
                    $kv = $k;
                }
                if ($kv == '') {
                    $kv = ucwords($k);
                }
                ?>




                <div class="control-group">
                    <label class="mw-ui-label">
                        <small><?php print ($kv); ?></small>
                    </label>
                    <input
                        type="text" class="mw-ui-field"
                        name="<?php print $data['name'] ?>[<?php print ($k); ?>]" <?php if ($is_required) { ?> required <?php } ?>
                        data-custom-field-id="<?php print $data["id"]; ?>"/>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
