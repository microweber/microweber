
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

    <button type="submit" class="mw-ui-btn mw-ui-btn-green right">Publish</button>

</div>
</form>



<script>
    mw.require("content.js");
</script>
<script>
    $(document).ready(function(){
       var area = mwd.getElementById('quick_content');
       var editor = mw.tools.wysiwyg(area);
       editor.style.width = "100%";
       editor.style.height = "270px";
       mw.treeRenderer.appendUI('#mw-category-selector-<?php print $rand; ?>');
       mw.tools.tag({
          tagholder:'#mw-post-added-<?php print $rand; ?>',
          items: ".mw-ui-check",
          itemsWrapper: mwd.querySelector('#mw-category-selector-<?php print $rand; ?>'),
          method:'parse',
      });
      mw.$("#quick-tag-field").keydown(function(e){
         if(e.keyCode == 9){
            $(editor).contents().find("#mw-iframe-editor-area").focus();
         }
      });
      mw.$("#quickform-<?php print $rand; ?>").submit(function(){
        var el = this;
        var module =  $(mw.tools.firstParentWithClass(el, 'module'));
        var data = mw.serializeFields(el);
        module.addClass('loading');
        mw.content.save(data, {
          onSuccess:function(){
              module.removeClass('loading');
          },
          onError:function(){
              module.removeClass('loading');
              if(typeof this.title !== 'undefined'){
                mw.notification.error('Please type your title')
              }
              if(typeof this.content !== 'undefined'){
                mw.notification.error('Please type your content')
              }
          }
        })
        return false;
      });



    });
</script>