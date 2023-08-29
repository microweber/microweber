<?php must_have_access() ?>
<?php
$template_config = mw()->template->get_config();

$cur_template = get_option('template', $params['parent-module-id']);
if ($cur_template == false) {
    if(isset($params['template'])){
        $cur_template = $params['template'];
    }
}





if (isset($template_config['layouts_css_classes'])) {
    $css_classes = $template_config['layouts_css_classes'];
} else {
    include('default_layout_classes.php');
}

$padding_top = get_option('padding-top', $params['id']);
if ($padding_top === null OR $padding_top === false OR $padding_top == '') {
    $padding_top = false;
}

$padding_bottom = get_option('padding-bottom', $params['id']);
if ($padding_bottom === null OR $padding_bottom === false OR $padding_bottom == '') {
    $padding_bottom = false;
}
?>

<div class="mw-flex-row">


    <?php if (isset($css_classes) and !empty($css_classes)): ?>
    <div class="mw-flex-col-xs-12 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Padding Top"); ?></label>
            <select name="padding-top" class="form-select  mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>">
                <option value="" <?php if (!$padding_top) {echo 'selected';} ?>><?php _e("No Selected"); ?></option>
                <?php if (isset($css_classes['padding-top'])): ?>
                    <?php foreach ($css_classes['padding-top'] as $key => $option): ?>
                        <option value="<?php print $key; ?>"<?php if ($padding_top == $key) {echo 'selected';} ?>><?php _e("Padding Top") . ' '; ?><?php if($key == 'none'): ?>0<?php else: ?><?php echo $key; ?><?php endif; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-12 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _e("Padding Bottom"); ?></label>
            <select name="padding-bottom" class="form-select  mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>">
                <option value="" <?php if (!$padding_bottom) {echo 'selected';} ?>><?php _e("No Selected"); ?></option>
                <?php if (isset($css_classes['padding-bottom'])): ?>
                    <?php foreach ($css_classes['padding-bottom'] as $key => $option): ?>
                        <option value="<?php print $key; ?>"<?php if ($padding_bottom == $key) {echo 'selected';} ?>><?php _e("Padding Bottom") . ' '; ?><?php if($key == 'none'): ?>0<?php else: ?><?php echo $key; ?><?php endif; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <?php endif; ?>


    <?php if($cur_template){ ?>

    <module type="admin/modules/templates_settings" id="<?php print $params['parent-module-id'] ?>" parent-module-id="<?php print $params['parent-module-id'] ?>" parent-module="<?php print $params['parent-module'] ?>" parent-template="<?php print $cur_template ?>"/>


    <?php } ?>



</div>
