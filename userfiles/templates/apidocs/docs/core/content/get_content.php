<? include TEMPLATE_DIR. "header.php"; ?>
<div class="demo" id="demo-one">
  <div class="description">
    <h2  class="fn">get_content(<strong>$params</strong>)</h2>
    <p>Function to get content items by <strong>params</strong></p>
  </div>
  <div class="options options-table mw-exec" data-api-function="get_content"   data-target="#get_content_output">
    <div class="description">
      <h3>Available Parameters</h3>
    </div>
    <div class="row-fluid">
      <div class="span7"> <!-- .description -->
        <div class="table-wrap">
          <table width="100%"  cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <th width="20%">Key</th>
                <th width="18%">Default value</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>id</td>
                <td><? $field_name='id'; include($helpers_path.'pages_tree.php'); ?></td>
                <td>The id of the content</td>
              </tr>
              <tr>
                <td>parent </td>
                <td><? $field_name='parent'; include($helpers_path.'pages_tree.php'); ?></td>
                <td>The id of the parent page</td>
              </tr>
              <tr>
                <td>category</td>
                <td><? $field_name='category'; include($helpers_path.'category.php'); ?></td>
                <td>The id of the category</td>
              </tr>
              <tr>
                <td>content_type</td>
                <td><select name="content_type"    class="mw-exec-option"  >
                    <option  selected="selected" value="" >false</option>
                    <option  value="page" >page</option>
                    <option  value="post" >post</option>
                  </select></td>
                <td>content type</td>
              </tr>
              <tr>
                <td>is_shop</td>
                <td><select name="is_shop"    class="mw-exec-option"  >
                    <option  selected="selected" value="" >false</option>
                    <option  value="y" >y</option>
                    <option  value="n" >n</option>
                  </select></td>
                <td>is_shop</td>
              </tr>
              <tr>
                <td>subtype</td>
                <td><select name="subtype"    class="mw-exec-option"  >
                    <option  selected="selected" value="" >false</option>
                    <option  value="static" >static</option>
                    <option  value="dynamic" >dynamic</option>
                  </select></td>
                <td>subtype</td>
              </tr>
              <tr>
                <td>is_home</td>
                <td><select name="is_home"    class="mw-exec-option"  >
                    <option  selected="selected" value="" >false</option>
                    <option  value="y" >y</option>
                    <option  value="n" >n</option>
                  </select></td>
                <td>Is the content homepage</td>
              </tr>
              <tr>
                <td><a id="get_content_custom_fields_t" href="#messages">Custom fields</a></td>
                <td><div   style="display:none" id="get_content_custom_fields" class="mw-exec-custom-fields">
                    <? $field_name='content'; include($helpers_path.'custom_fields.php'); ?>
                  </div></td>
                <td></td>
              </tr>
            </tbody>
          </table>
          <?   include($helpers_path.'get_common.php'); ?>
        </div>
      </div>
      <div class="span5">
                            <? $field_name='get_content_output'; include($helpers_path.'output.php'); ?>



      </div>
    </div>
  </div>

  <!-- .panels -->
</div>
<!-- .demo -->
<? include TEMPLATE_DIR. "footer.php"; ?>