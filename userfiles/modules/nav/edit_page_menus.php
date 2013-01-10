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
          <input id="menuid-<? print $item['id'] ?>" name="add_content_to_menu[]"  <? if(is_in_menu($item['id'],$content_id)): ?> checked="checked" <? endif; ?> value="<? print $item['id'] ?>" type="checkbox">
          <span></span><span class="mw-menuselector-menu-title"><? print ucwords(string_nice($item['title'])) ?></span></label>
      </li>
      <? endforeach ; ?>
    </ul>
    <? endif; ?>
  </div>
  <script>

    mw.removeFromMenu = function(el, id){
        $(el.parentNode).remove();
        mwd.getElementById('menuid-' + id).checked = false;
    }

          $(document).ready(function(){

            var inputs = mwd.querySelectorAll('.mw-page-menu-selector input:checked'), l = inputs.length, i = 0;


            if(l > 0){
              for( ; i<l; i++){
                 var label = mw.$('.mw-menuselector-menu-title', inputs[i].parentNode).html();
                 mw.$('#mw-selected-menus').append("<span id='id-"+inputs[i].value+"' class='mw-ui-btn mw-ui-btn-small'>"+label+"<span class='mw-ui-btnclose' onclick='mw.removeFromMenu(this, "+inputs[i].value+");'></span></span>");
              }
            }

            mw.$(".mw-page-menu-selector input").commuter(function(){
                var label = mw.$('.mw-menuselector-menu-title', this.parentNode).html();
                mw.$('#mw-selected-menus').append("<span id='id-"+this.value+"' class='mw-ui-btn mw-ui-btn-small'>"+label+"<span class='mw-ui-btnclose' onclick='mw.removeFromMenu(this, "+this.value+");'></span></span>");
            }, function(){

                 mw.$('#id-'+this.value, mwd.getElementById('mw-selected-menus')).remove();
            });

            mw.$(".mw-page-menu-selector").click(function(e){

                if($(e.target).hasClass('mw-page-menu-selector') || e.target.id == 'mw-selected-menus'){
                     mw.$("ul", this).toggle();
                }

            });

          });

          </script> 
</div>
