<? $menus = get_menu(); ?>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label">
    <?php _e("Add to Navigation"); ?>
  </label>
  <div class="mw-ui-select mw-page-menu-selector">
    <div id="mw-selected-menus">
        <input
              id="mw-page-menu-selector-field"
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
            <span></span><span class="mw-menuselector-menu-title"><? print ucwords(string_nice($item['title'])) ?></span>
          </label>
      </li>
      <? endforeach ; ?>
    </ul>
    <? endif; ?>
  </div>
  <script>

    mw.controllers.addToMenu = {
        field: mwd.getElementById('mw-page-menu-selector-field'),
        remove: function(el, id){
            if(!!el) { $(el).remove(); }
            if(!!id) { mwd.getElementById('menuid-' + id).checked = false };
        },
        init:function(){
            var inputs = mwd.querySelectorAll('.mw-page-menu-selector input:checked'), l = inputs.length, i = 0;
            if(l > 0){
              for( ; i<l; i++){
                 var label = mw.$('.mw-menuselector-menu-title', inputs[i].parentNode).html();
                 var html = "<span id='id-"+inputs[i].value+"' class='mw-ui-btn mw-ui-btn-small'>"+label+"<span class='mw-ui-btnclose' onclick='mw.controllers.addToMenu.remove(this.parentNode, "+inputs[i].value+");'></span></span>";
                 if(mw.$('#mw-selected-menus .mw-ui-btn').length==0){
                   mw.$('#mw-selected-menus').prepend(html);
                 }
                 else{
                    mw.$('#mw-selected-menus .mw-ui-btn:last').after(html)
                 }

              }
            }
            mw.controllers.addToMenu.fieldInit();
        },
        commute:function(){
            var field = mw.$(mw.controllers.addToMenu.field);
            mw.$(".mw-page-menu-selector input").commuter(function(){
                mw.$('#mw-selected-menus label').remove();
                var label = mw.$('.mw-menuselector-menu-title', this.parentNode).html();
                //field.val("");
                var html = "<span id='id-"+this.value+"' class='mw-ui-btn mw-ui-btn-small'>"+label+"<span class='mw-ui-btnclose' onclick='mw.controllers.addToMenu.remove(this.parentNode, "+this.value+");'></span></span>";
                if(mw.$('#mw-selected-menus .mw-ui-btn').length==0){
                   mw.$('#mw-selected-menus').prepend(html);
                 }
                 else{
                    mw.$('#mw-selected-menus .mw-ui-btn:last').after(html)
                 }
            }, function(){
                 mw.controllers.addToMenu.remove('#id-'+this.value);
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
        },
        fieldInit:function(){
            $(mw.controllers.addToMenu.field).focus(function(e){
               mw.form.dstatic(e);
            });
            $(mw.controllers.addToMenu.field).blur(function(e){
                if(mw.$("#mw-selected-menus .mw-ui-btn").length === 0){
                   mw.form.dstatic(e);
                }
                if(mw.$(".mw-page-menu-selector.hover").length == 0){
                  $(".mw-page-menu-selector .mw-ui-check").css('display','inline-block');
                  if(mw.$("#mw-selected-menus .mw-ui-btn").length > 0){
                      mw.$(mw.controllers.addToMenu.field).val("");
                  }
                  else{
                      mw.$(mw.controllers.addToMenu.field).val($(mw.controllers.addToMenu.field).dataset('default'));
                  }
                }
            });
        }
    }



          $(document).ready(function(){



            mw.controllers.addToMenu.init();
            mw.controllers.addToMenu.commute();

            mw.$(".mw-page-menu-selector").hover(function(){
              $(this).addClass('hover');
            }, function(){
              $(this).removeClass('hover');
            });

            $(mwd.body).mousedown(function(){
              if(mw.$(".mw-page-menu-selector.hover").length == 0){
                mw.$(".mw-page-menu-selector ul").hide();

              }
            });

            mw.$(".mw-page-menu-selector").click(function(e){
                     mw.$("ul", this).show();
                     if(e.target.tagName !== 'INPUT'){
                        $(this).find('input').focus();
                     }
            });

          });

          </script> 
</div>
