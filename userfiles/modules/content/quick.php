
<?php
    $rand = uniqid();
?>





<form method="post" action="<?php print site_url(); ?>api/save_content" id="quickform-<?php print $rand; ?>">

<input type="hidden" name="id"  value="0" />
<input type="hidden" name="is_active"  value="y" />
<input type="hidden" name="subtype"  value="<?php print $params['subtype']; ?>" />
<input type="hidden" name="content_type"  value="post" />


<div class="mw-ui-field-holder">
<input
      type="text"
      name="title"
      class="mw-ui-field mw-title-field mw-ui-field-full"
      style="border-left: 1px solid #E6E6E6"
      placeholder="Create new <?php print $params['subtype']; ?>"
      autofocus required />

</div>

<div class="mw-ui-field-holder">

        <div>
       <div class="mw-ui-field mw-tag-selector mw-ui-field-dropdown mw-ui-field-full" id="mw-post-added-<?php print $rand; ?>">
			<input type="text" class="mw-ui-invisible-field" placeholder="<?php _e("Click here to add to categories and pages"); ?>." style="width: 280px;" id="quick-tag-field" />
		</div>
        <div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector" id="mw-category-selector-<?php print $rand; ?>" >
            <module
                    type="categories/selector"
                    for="content" />
        </div>
        </div>

</div>
<div class="mw-ui-field-holder">

<textarea class="semi_hidden" name="content" id="quick_content"></textarea>

</div>

<?php if($params['subtype'] == 'product'){ ?>




  <div class="mw-ui-field-holder">
  <module
          type="custom_fields/admin"
          for="content"
          default-fields="price"
          content-id="0"
          content-subtype="<?php print $params['subtype'] ?>" />
  </div>




<?php } ?>

<div class="mw-ui-field-holder">

      <span class="mw-ui-link relative" id="quick-add-gallery" onclick="mw.$('#quick_init_gallery').show();$(this).hide();">Create Gallery</span>
      <div id="quick_init_gallery" class="mw-o-box" style="display: none;margin-bottom:20px;">
          <div  class="mw-o-box-content"><module type="pictures/admin" content_id="0" rel="content" rel_id="0" /></div>
      </div>

    <button type="submit" class="mw-ui-btn mw-ui-btn-green right">Publish</button>
</div>
</form>



<div class="quick_done_alert" style="display: none">
    <h2><span style="text-transform: capitalize"><?php print $params['subtype'] ?></span> has been created.</h2>
    <a href="javascript:;" class="mw-ui-link">Go to <?php print $params['subtype'] ?></a>
    <span class="mw-ui-btn" onclick="$(mw.tools.firstParentWithClass(this, 'mw-inline-modal')).remove();">Create New</span>
</div>

<script>
    mw.require("content.js");
    mw.require("files.js");
</script>
<script>



    add_cats = function(){
      var names = [];
      var inputs = mwd.getElementById('mw-category-selector-<?php print $rand; ?>').querySelectorAll('input[type="checkbox"]'), i=0, l = inputs.length;
      for( ; i<l; i++){
        if(inputs[i].checked === true){
           names.push(inputs[i].value);
        }
      }
      if(names.length > 0){
        mw.$('#mw_cat_selected_for_post').val(names.join(',')).trigger("change");
      } else {
        mw.$('#mw_cat_selected_for_post').val('__EMPTY_CATEGORIES__').trigger("change");
      }
    }



    $(document).ready(function(){
       var area = mwd.getElementById('quick_content');
       editor = mw.tools.wysiwyg(area);
       editor.style.width = "100%";
       editor.style.height = "270px";
       mw.treeRenderer.appendUI('#mw-category-selector-<?php print $rand; ?>');
       mw.tools.tag({
          tagholder:'#mw-post-added-<?php print $rand; ?>',
          items: ".mw-ui-check",
          itemsWrapper: mwd.querySelector('#mw-category-selector-<?php print $rand; ?>'),
          method:'parse',
          onTag:function(){
             add_cats()
          },
          onUntag:function(){
             add_cats()
          }
      });
      mw.$("#quickform-<?php print $rand; ?>").submit(function(){
        var el = this;
        var module =  $(mw.tools.firstParentWithClass(el, 'module'));
        var data = mw.serializeFields(el);
        module.addClass('loading');
        mw.content.save(data, {
          onSuccess:function(){
              el.reset();
              $(editor).contents().find("#mw-iframe-editor-area").empty();
              mw.$(".quick_done_alert a").attr("href", mw.settings.site_url + "?content_id=" + this);
              mw.reload_module("pictures/admin", function(){
                  module.removeClass('loading');
                  mw.tools.inlineModal({
                    element: mw.$(".quick-add-module"),
                    content: $(".quick_done_alert")
                  });
              });
          },
          onError:function(){
              module.removeClass('loading');
              if(typeof this.title !== 'undefined'){
                mw.notification.error('Please enter title');
              }
              if(typeof this.content !== 'undefined'){
                mw.notification.error('Please enter content');
              }
              if(typeof this.error !== 'undefined'){
                mw.session.checkPause = false;
                mw.session.checkPauseExplicitly = false;
                mw.session.logRequest();
              }
          }
        })
        return false;
      });
    });
</script>