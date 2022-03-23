<?php if ($settings['multiple']): ?>
    <script type="text/javascript">
        mw.lib.require('chosen');
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-mw-select-<?php echo $data['id']; ?>").chosen({width: '100%'});
        });
    </script>
<?php endif; ?>

<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mw-text-start mb-3">

        <?php if($settings['show_label']): ?>
        <label class="control-label my-3">
            <?php echo $data['name']; ?>
            <?php if ($settings['required']): ?>
                <span style="color: red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>

        <select <?php if ($settings['multiple']): ?>multiple="multiple"<?php endif; ?> class="form-control js-mw-select-<?php echo $data['id']; ?>" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name_key']; ?>"/>

        <?php if (!empty($data['placeholder'])): ?>
            <option disabled selected value><?php echo $data['placeholder']; ?></option>
        <?php endif; ?>

        <?php foreach ($data['values'] as $key => $value): ?>
            <option data-custom-field-id="<?php print $data["id"]; ?>" value="<?php echo $value; ?>">
                <?php echo $value; ?>
            </option>
        <?php endforeach; ?>
        </select>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
    </div>
</div>
