<? $menus = get_menu(); ?>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Add to Navigation"); ?>
  </label>
  <div class="mw-ui-select mw-page-menu-selector">
    <div id="mw-selected-menus">
        <input
              onfocus="mw.form.dstatic(event);"
              onblur="mw.form.dstatic(event);"
              onkeyup="mw.controllers.addToMenu.autoComplete(this);"
              data-default="Click here to add to navigation"
              value="Click here to add to navigation" />
    </div>
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

    mw.controllers.addToMenu = {
        remove: function(el, id){
            $(el.parentNode).remove();
            mwd.getElementById('menuid-' + id).checked = false;
        },
        init:function(){
            var inputs = mwd.querySelectorAll('.mw-page-menu-selector input:checked'), l = inputs.length, i = 0;
            if(l > 0){
              for( ; i<l; i++){
                 var label = mw.$('.mw-menuselector-menu-title', inputs[i].parentNode).html();
                 mw.$('#mw-selected-menus').prepend("<span id='id-"+inputs[i].value+"' class='mw-ui-btn mw-ui-btn-small'>"+label+"<span class='mw-ui-btnclose' onclick='mw.controllers.addToMenu.remove(this, "+inputs[i].value+");'></span></span>");
              }
            }
        },
        commute:function(){
            mw.$(".mw-page-menu-selector input").commuter(function(){
                mw.$('#mw-selected-menus label').remove();
                var label = mw.$('.mw-menuselector-menu-title', this.parentNode).html();
                mw.$('#mw-selected-menus').prepend("<span id='id-"+this.value+"' class='mw-ui-btn mw-ui-btn-small'>"+label+"<span class='mw-ui-btnclose' onclick='mw.controllers.addToMenu.remove(this, "+this.value+");'></span></span>");
            }, function(){

                 mw.$('#id-'+this.value, mwd.getElementById('mw-selected-menus')).remove();
                 if(mw.$('#mw-selected-menus').html()==''){
                        mw.$('#mw-selected-menus').html('<label class="mw-ui-label">&nbsp;&nbsp;<small>Click here to add to navigation</small></label>');
                 }
            });
        },
        autoComplete:function(el){
          var val = el.value.toLowerCase();
          if(val==''){
            mw.$(".mw-page-menu-selector .mw-ui-check").css('display','inline-block');
            return false;
          }
          mw.tools.search(val, ".mw-page-menu-selector .mw-ui-check", function(found){
            if(found){
              this.style.display = 'inline-block';
            }
            else{
               this.style.display = 'none';
            }
          });
        }
    }



          $(document).ready(function(){



            mw.controllers.addToMenu.init();
            mw.controllers.addToMenu.commute();


            mw.$(".mw-page-menu-selector").click(function(e){

                if($(e.target).hasClass('mw-page-menu-selector') || e.target.id == 'mw-selected-menus' || e.target.tagName === 'INPUT'){
                     mw.$("ul", this).toggle();
                     if(e.target.tagName !== 'INPUT'){
                        $(this).find('input').focus();
                     }

                }


            });

          });

          </script> 
</div>
