<?php $pages = get_content('content_type=page&subtype=dynamic&limit=1000'); ?>
<?php $posts_parent_page = get_option('data-content-id', $params['id']); ?>
<div class="module-live-edit-settings">
        <script>

        $(document).ready(function(){
            mw.tabs({
              nav:".mw-ui-btn-nav-tabs .mw-ui-btn",
              tabs:".tab",
            })
        });

        </script>
        <style scoped="scoped">
            .tab{
              display: none;
            }

        </style>
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <span class="mw-ui-btn active"><?php _e("Options"); ?></span>
            <span class="mw-ui-btn "><?php _e("Skin/Template"); ?></span>
        </div>

      <div class="mw-ui-box mw-ui-box-content">
          <div class="tab" style="display: block">
              <label class="mw-ui-label"><?php _e("Search in page"); ?></label>

                  <select name="data-content-id" class="mw-ui-field mw_option_field">
                      <option
                          value="0"   <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>
                          title="<?php _e("None"); ?>"><?php _e("All pages"); ?>
                      </option>
                      <?php
                      $pt_opts = array();
                      $pt_opts['link'] = "{empty}{title}";
                      $pt_opts['list_tag'] = " ";
                      $pt_opts['list_item_tag'] = "option";
                      $pt_opts['active_ids'] = $posts_parent_page;
                      $pt_opts['active_code_tag'] = '   selected="selected"  ';
                      pages_tree($pt_opts);
                      ?>
                  </select>

          </div>
          <div class="tab">
              <module type="admin/modules/templates"/>
          </div>
      </div>
</div>