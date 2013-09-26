 <div class="mw-template-settings" id="mw-template-settings-holder" style="display: none">
    <div id="mw-template-settings">
	 
      <label class="mw-ui-label">Div Border Radius</label>

      <input type="text" class="mw-ui-field tpl-field"
             data-selector="body div"
             value = "<?php if(isset($arr['bodybg']) and isset($arr['bodybg']['value'])){print $arr['bodybg']['value'];} ?>"
             name="bodybg"
             data-property="border-radius,-webkit-border-radius,-moz-border-radius" />

      <label class="mw-ui-label">Body Font</label>
      <input type="text" class="mw-ui-field tpl-field"
             data-selector="body span"
             value="<?php if(isset($arr['bodyfont']) and isset($arr['bodyfont']['value'])){print $arr['bodyfont']['value'];} ?>"
             name="bodyfont"
             data-property="font-family">
      <button onclick="mw.tpl.save();" class="mw-ui-btn">Save</button>
    </div>
  </div>