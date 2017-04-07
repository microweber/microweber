<fieldset class="inputs">
    <legend><span><?php _e("Parallax settings"); ?></span></legend>
    <div class="form-group">
        <label class="control-label" for="parallax"><?php _e('Parallax image URL'); ?></label>
        <input name="parallax" data-refresh="ants"  class="mw_option_field"   type="text" value="<?= get_option('parallax', $params['id']) ?>" id="parallax">
    </div>

    <div class="form-group">
        <label class="control-label" for="title"><?php _e('Title'); ?></label>
        <input name="title" data-refresh="ants"  class="mw_option_field"   type="text" value="<?= get_option('title', $params['id']) ?>" id="title">
    </div>

    <div class="form-group">
        <label class="control-label" for="text"><?php _e('Text'); ?></label>
        <textarea name="text" data-refresh="ants" class="mw_option_field" id="text" cols="10"><?= get_option('text', $params['id']) ?></textarea>
    </div>

    <div class="form-group">
        <label class="control-label" for="info-image"><?php _e('Info image'); ?></label>
        <input name="info-image" data-refresh="ants"  class="mw_option_field"   type="text" value="<?= get_option('info-image', $params['id']) ?>" id="info-image">
    </div>

    <div class="form-group">
        <label class="control-label" for="button-text"><?php _e('Button text'); ?></label>
        <input name="button-text" data-refresh="ants"  class="mw_option_field"   type="text" value="<?= get_option('button-text', $params['id']) ?>" id="button-text">
    </div>

    <div class="form-group">
        <label class="control-label" for="button-url"><?php _e('Button URL'); ?></label>
        <input name="button-url" data-refresh="ants"  class="mw_option_field"   type="text" value="<?= get_option('button-url', $params['id']) ?>" id="button-url">
    </div>

</fieldset>
