<? $menus = get_menu(); ?>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Add to Navigation"); ?>
  </label>
  <div class="mw-ui-select mw-page-menu-selector">
    <div id="mw-selected-menus"></div>
    <?
$content_id = false;
 if(isset($params['content_id'])){
	 $content_id = $params['content_id'];
 }

$menu_name = false;
 if(isarr($menus )): ?>
    <ul>
      <? foreach($menus  as $item): ?>
      <li>
        <label class="mw-ui-check">
          <input name="add_content_to_menu[]"  <? if(is_in_menu($item['id'],$content_id)): ?> checked="checked" <? endif; ?> value="<? print $item['id'] ?>" type="checkbox">
          <span></span><span class="mw-menuselector-menu-title"><? print ucwords(string_nice($item['title'])) ?></span></label>
      </li>
      <? endforeach ; ?>
    </ul>
    <? endif; ?>
  </div>
  <script>

          $(document).ready(function(){
            mw.$(".mw-page-menu-selector input").commuter(function(){
                var label = mw.$('.mw-menuselector-menu-title', this.parentNode).html();
                mw.$('#mw-selected-menus').append("<span id='id-"+this.value+"' class='mw-ui-btn mw-ui-btn-small'>"+label+"<span class='mw-ui-btnclose'></span></span>");
            }, function(){

                 mw.$('#id-'+this.value, mwd.getElementById('mw-selected-menus')).remove();
            });

            mw.$(".mw-page-menu-selector").click(function(e){

                if($(e.target).hasClass('mw-page-menu-selector')){
                     mw.$("ul", this).toggle();
                }

            });

          });

          </script> 
</div>
