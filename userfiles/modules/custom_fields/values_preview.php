<?php
$field = false;

if (isset($params['field-id'])) {
    $field = get_custom_field_by_id($params['field-id']);
}
?>
<style>
    .mw-admin-custom-field-value-edit-inline-holder .delete-custom-fields {
        visibility: hidden;
    }

    .mw-admin-custom-field-value-edit-inline-holder:hover .delete-custom-fields {
        visibility: visible;
    }

    .mw-custom-fields-list-preview .mw-admin-custom-field-value-edit-inline-holder:not(.mw-admin-custom-field-checkbox) {
        min-width: 100px;
        position: relative;
        padding-right: 30px !important;
    }

    .mw-custom-fields-list-preview .mw-admin-custom-field-value-edit-inline-holder:not(.mw-admin-custom-field-checkbox) .mw-admin-custom-field-value-edit-inline:empty:before {
        content: 'Edit here';
        display: block;
        position: absolute;
        top: 5px;
        padding-right: 10px !important;
    }

    .mw-custom-fields-list-preview .mw-admin-custom-field-value-edit-inline-holder:not(.mw-admin-custom-field-checkbox):hover .mw-admin-custom-field-value-edit-inline:after {
        position: absolute;
        top: 5px;
        display: inline-block;
        font: normal normal normal 24px/1 "Material Design Icons";
        text-rendering: auto;
        line-height: inherit;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        content: '\F064F';
        font-size: 14px;
        right: 6px;
        opacity:;
    }
</style>
<script>
    mw.on.moduleReload('<?php print $params['id']; ?>', function () {
        mw.admin.custom_fields.initValues(document.getElementById('<?php print $params['id']; ?>').querySelectorAll('.mw-admin-custom-field-name-edit-inline, .mw-admin-custom-field-placeholder-edit-inline, .mw-admin-custom-field-value-edit-inline'));
    });
</script>

<?php if (isset($field['type']) and ($field['type'] == 'select' or $field['type'] == 'dropdown' or $field['type'] == 'checkbox' or $field['type'] == 'radio')): ?>
    <?php
    if (isset($field['values']) and is_array($field['values'])) {
        $vals = $field['values'];
    } elseif (isset($field['value'])) {
        $vals = $field['value'];
    } else {
        $vals = '';
    }
    if (is_string($vals)) {
        $vals = array($vals);
    }
    ?>

    <small class="custom-fields-values-holder d-flex flex-wrap">
        <?php echo implode(', ', $vals); ?>
    </small>


<?php elseif (isset($field['type']) and ($field['type'] == 'text' or $field['type'] == 'message' or $field['type'] == 'textarea' or $field['type'] == 'title')): ?>

    <?php print $field['value']; ?>

<?php elseif (isset($field['type']) and (($field['type'] == 'address') or $field['type'] == 'upload')): ?>
    <div style="width:100%; display:block; min-height:20px;" onclick="mw.admin.custom_fields.edit_custom_field_item('#mw-custom-fields-list-settings-<?php print $field['id']; ?>',<?php print $field['id']; ?>);"><?php print $field['values_plain']; ?></div>

<?php else: ?>
    <?php $vals = '';
    if ($field['values_plain'] != '') {
        $vals = $field['values_plain'];
    } elseif (is_string($field['value'])) {
        $vals = $field['value'];
    }
    ?>
    <span class="custom-fields-values-holder">
        <span class="d-inline-block px-3 py-1">
            <small class="px-1 py-1" data-id="<?php print $field['id']; ?>"><?php print $vals; ?></small>
        </span>
    </span>
<?php endif; ?>
