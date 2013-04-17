
<div class="module-live-edit">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Paste video URL or Embed Code</label>
    <input name="title" class="mw-ui-field mw_option_field"  type="text"      data-reload="<? print $params['data-type'] ?>"  value="<?php print get_option('title', $params['id']) ?>" style="width:370px;" />
  </div>

  <?php /*<div class="mw-ui-field-holder">
    <label class="mw-ui-label"> Video Embed code</label>
    <input name="embed" class="mw-ui-field mw_option_field"  type="text"   data-reload="<? print $params['data-type'] ?>"   value="<?php print get_option('embed', $params['id']) ?>" />

  </div> */ ?>

</div>
