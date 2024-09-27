<?php only_admin_access() ?>


<?php

$columns = get_option('columns', $params['id']);
if ($columns === null OR $columns === false OR $columns == '') {
    $columns = 'col-12 col-sm-6 col-md-4 col-lg-3 col-lg-3';
}

$columns_xs = get_option('columns-xs', $params['id']);
if ($columns_xs === null OR $columns_xs === false OR $columns_xs == '') {
    $columns_xs = 'col-12';
}

$columns_sm = get_option('columns-sm', $params['id']);
if ($columns_sm === null OR $columns_sm === false OR $columns_sm == '') {
    $columns_sm = 'col-sm-6';
}

$columns_md = get_option('columns-md', $params['id']);
if ($columns_md === null OR $columns_md === false OR $columns_md == '') {
    $columns_md = 'col-md-4';
}

$columns_lg = get_option('columns-lg', $params['id']);
if ($columns_lg === null OR $columns_lg === false OR $columns_lg == '') {
    $columns_lg = 'col-lg-3';
}

$columns_xl = get_option('columns-lg', $params['id']);
if ($columns_xl === null OR $columns_xl === false OR $columns_xl == '') {
    $columns_xl = 'col-lg-3';
}


$custom_classes = get_option('custom-classes', $params['id']);
if ($custom_classes === null OR $custom_classes === false OR $custom_classes == '') {
    $custom_classes = 'additional-class';
}
?>

<script>
    $(document).ready(function () {
        $('select[data-columns], input[data-classes]').on('change', function () {
            var selectedOptions = '';
            $('select[data-columns]').each(function () {
                var selectedOption = $(this).find('option:selected').val();
                selectedOptions = selectedOptions + ' ' + selectedOption;
            });

            $('input[data-classes]').each(function () {
                var selectedOption = $(this).val();
                selectedOptions = selectedOptions + ' ' + selectedOption;
            });

            $('input[name="columns"]').val(selectedOptions).trigger('change');
        });
    });
</script>

<div class="mw-flex-row m-t-30">
    <div class="mw-flex-col-xs-4 ">
        <h3>Posts Grid Settings</h3>
    </div>
</div>

<div class="mw-flex-row">
    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Extra Small &lt; 576px</label>
            <select name="columns-xs" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="xs">
                <option value="col-12" <?php if ($columns_xs == 'col-12'): ?>selected<?php endif; ?>>1 column</option>
                <option value="col-6" <?php if ($columns_xs == 'col-6'): ?>selected<?php endif; ?>>2 columns</option>
                <option value="col-4" <?php if ($columns_xs == 'col-4'): ?>selected<?php endif; ?>>3 columns</option>
                <option value="col-3" <?php if ($columns_xs == 'col-3'): ?>selected<?php endif; ?>>4 columns</option>
                <option value="col-2" <?php if ($columns_sm == 'col-2'): ?>selected<?php endif; ?>>6 columns</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Small ≥ 576px</label>
            <select name="columns-sm" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="sm">
                <option value="col-sm-12" <?php if ($columns_sm == 'col-sm-12'): ?>selected<?php endif; ?>>1 column</option>
                <option value="col-sm-6" <?php if ($columns_sm == 'col-sm-6'): ?>selected<?php endif; ?>>2 columns</option>
                <option value="col-sm-4" <?php if ($columns_sm == 'col-sm-4'): ?>selected<?php endif; ?>>3 columns</option>
                <option value="col-sm-3" <?php if ($columns_sm == 'col-sm-3'): ?>selected<?php endif; ?>>4 columns</option>
                <option value="col-sm-2" <?php if ($columns_sm == 'col-sm-2'): ?>selected<?php endif; ?>>6 columns</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Medium ≥ 768px</label>
            <select name="columns-md" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="md">
                <option value="col-md-12" <?php if ($columns_md == 'col-md-12'): ?>selected<?php endif; ?>>1 column</option>
                <option value="col-md-6" <?php if ($columns_md == 'col-md-6'): ?>selected<?php endif; ?>>2 columns</option>
                <option value="col-md-4" <?php if ($columns_md == 'col-md-4'): ?>selected<?php endif; ?>>3 columns</option>
                <option value="col-md-3" <?php if ($columns_md == 'col-md-3'): ?>selected<?php endif; ?>>4 columns</option>
                <option value="col-md-2" <?php if ($columns_md == 'col-md-2'): ?>selected<?php endif; ?>>6 columns</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Large ≥ 992px</label>
            <select name="columns-lg" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="lg">
                <option value="col-lg-12" <?php if ($columns_lg == 'col-lg-12'): ?>selected<?php endif; ?>>1 column</option>
                <option value="col-lg-6" <?php if ($columns_lg == 'col-lg-6'): ?>selected<?php endif; ?>>2 columns</option>
                <option value="col-lg-4" <?php if ($columns_lg == 'col-lg-4'): ?>selected<?php endif; ?>>3 columns</option>
                <option value="col-lg-3" <?php if ($columns_lg == 'col-lg-3'): ?>selected<?php endif; ?>>4 columns</option>
                <option value="col-lg-2" <?php if ($columns_lg == 'col-lg-2'): ?>selected<?php endif; ?>>6 columns</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Extra large ≥ 1200px</label>
            <select name="columns-lg" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="xl">
                <option value="col-lg-12" <?php if ($columns_xl == 'col-lg-12'): ?>selected<?php endif; ?>>1 column</option>
                <option value="col-lg-6" <?php if ($columns_xl == 'col-lg-6'): ?>selected<?php endif; ?>>2 columns</option>
                <option value="col-lg-4" <?php if ($columns_xl == 'col-lg-4'): ?>selected<?php endif; ?>>3 columns</option>
                <option value="col-lg-3" <?php if ($columns_xl == 'col-lg-3'): ?>selected<?php endif; ?>>4 columns</option>
                <option value="col-lg-2" <?php if ($columns_xl == 'col-lg-2'): ?>selected<?php endif; ?>>6 columns</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Custom Classes</label>
            <input name="custom-classes" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" value="<?php print $custom_classes; ?>" data-classes=""/>
        </div>
    </div>
</div>

<div class="mw-flex-row hidden">
    <div class="mw-flex-col-xs-12 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Columns</label>
            <input name="columns" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" value="<?php print $columns; ?>"/>
        </div>
    </div>
</div>


