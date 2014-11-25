<script type="text/javascript">
    mw.require("style_editors.js");
    zoommap = function(val){
        mw.$("#zoom_level").val(val).trigger("change");
    }
</script>
<style type="text/css">
#val_slider {
	width: 200px;
}
#addr {
	width: 368px;
}
</style>

<div class="module-live-edit-settings">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Address"); ?>
    </label>
    <input
      name="data-address"
      class="mw-ui-field mw_option_field"
      id="addr"
      type="text"
      value="<?php print get_option('data-address', $params['id']) ?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label" style="padding-bottom: 20px;">
      <?php _e("Zoom Level"); ?>
    </label>
    <?php /* <div
    data-type=""
    data-custom="zoommap"
    data-value="<?php print get_option('data-zoom', $params['id']) ?>"
    data-max="19"
    data-min="0"
    id="val_slider"
    class="ed_slider val-slider es_item left ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"
    aria-disabled="false">
    <a href="#" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 0%;"></a>
</div>
*/ ?>
    <input
    name="data-zoom"
    class="mw-ui-field-range mw_option_field"
    max="19"
    min="0"
    type="range" id="zoom_level"
    value="<?php print get_option('data-zoom', $params['id']) ?>" />
  </div>
</div>
