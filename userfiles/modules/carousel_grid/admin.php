<div class="mw-ui-box-content" >

<?php
  $items_number = get_option('items_number', $params['id']);
  if(!$items_number){
    $items_number = 10;
  }
?>
  <style scoped="scoped">

    .tab{
      display: none;
    }

    </style>

    <script>

    $(mwd).ready(function(){
        CatTabs = mw.tabs({
          nav:'.mw-ui-btn-nav-tabs a',
          tabs:'.tab',
          onclick:function(){
            //mw.$('.mw-module-category-manager').show();
          }
        });
        CatTabs.set(0)
    });
    </script>
      <nav class="mw-ui-btn-nav mw-ui-btn-nav-tabs">

        <a class="mw-ui-btn" href="javascript:;">Images</a>
        <a class="mw-ui-btn" href="javascript:;">Options</a>



      </nav>

      <div class="mw-ui-box">
        <div class="tab">
          <div class="mw-ui-box-content">
            <module type="pictures/admin" rel_id="<?php print $params['id']; ?>"   />
          </div>
        </div>
        <div class="tab">
          <div class="mw-ui-box-content">
            <div class="mwui-field-holder">
              <label class="mw-ui-label">Items per slide</label>
              <input type="number" class="mw-ui-field mw_option_field" name="items_number" value="<?php print $items_number; ?>" />
            </div>
          </div>
        </div>
      </div>
    </div>