<div class="mw-ui-box-content" >

<?php
  $items_number = get_option('items_number', $params['id']);
  $maxRowHeight = get_option('max_row_height', $params['id']);
  $rowHeight = get_option('row_height', $params['id']);
  if(!$maxRowHeight){
    $maxRowHeight = 250;
  }
  if(!$rowHeight){
    $rowHeight = 120;
  }
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
        CatTabs.set(0);

        $('[data-type="pictures/admin"]').on('change', function(){
          window.parent.mw.reload_module('#<?php print $params['id']; ?>')
        })
    });
    </script>
      <nav class="mw-ui-btn-nav mw-ui-btn-nav-tabs">

        <a class="mw-ui-btn" href="javascript:;">Images</a>
        <a class="mw-ui-btn" href="javascript:;">Options</a>



      </nav>

      <div class="mw-ui-box">
        <div class="tab">
          <div class="mw-ui-box-content">
            <module type="pictures/admin" rel_id="<?php print $params['id']; ?>" id="pa<?php print $params['id']; ?>"   />
          </div>
        </div>
        <div class="tab">
          <div class="mw-ui-box-content">
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Items per slide</label>
              <input type="number" class="mw-ui-field mw_option_field" name="items_number" value="<?php print $items_number; ?>" />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Minimum Row height</label>
              <input type="number" class="mw-ui-field mw_option_field" name="row_height" placeholder="120" value="<?php print $rowHeight; ?>" />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Max Row height</label>
              <input type="number" class="mw-ui-field mw_option_field" name="max_row_height" placeholder="250" value="<?php print $maxRowHeight; ?>" />
            </div>
          </div>
        </div>
      </div>
    </div>