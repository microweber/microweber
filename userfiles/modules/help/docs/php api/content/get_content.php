
<div class="demo" id="demo-one">
  <div class="description">
    <h2  class="fn">get_content(<strong>$params</strong>)</h2>
    <p>Function to get content items by <strong>params</strong></p>
  </div>
  <div class="options">
    <div class="description">
      <h3>Available Options </h3>
    </div>
    <!-- .description -->
    <div class="table-wrap">
      <table width="100%" class="options-table mw-exec" data-api-function="get_content"   data-target="#get_content_output" cellpadding="0" cellspacing="0">
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
            <td><select name="id"    class="mw-exec-option"  >
                <option     value="">false</option>
                <?
$pt_opts = array();
$pt_opts['link'] = "({id}) {title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['depth'] = 1;
 
$pt_opts['active_code_tag'] = '   selected="selected"  ';
 pages_tree($pt_opts);

  ?>
              </select></td>
            <td>The id of the content</td>
          </tr>
          <tr>
            <td>parent </td>
            <td><select name="parent"    class="mw-exec-option"  >
                <option     value="">false</option>
                <?
$pt_opts = array();
$pt_opts['link'] = "({id}) {title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['depth'] = 1;
 
$pt_opts['active_code_tag'] = '   selected="selected"  ';
 pages_tree($pt_opts);

  ?>
              </select></td>
            <td>The id of the parent page</td>
          </tr>
          <tr>
            <td>category</td>
            <td><select name="category"    class="mw-exec-option"  >
                <option     value="">false</option>
                <?
$pt_opts = array();
$pt_opts['link'] = " <option value='{id}'>({id}) {title}</option>";
$pt_opts['list_tag'] = " ";
 
 category_tree($pt_opts);

  ?>
              </select></td>
            <td>The id of the category</td>
          </tr>
          <tr>
            <td>keyword</td>
            <td><input name="keyword"   type="search"  class="mw-exec-option search-query"  ></td>
            <td>Search content by keyword</td>
          </tr>
          <tr>
            <td>limit</td>
            <td><input name="limit"    type="number" class="mw-exec-option"  ></td>
            <td>Results limit</td>
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
            <td>have</td>
            <td>
             <? $db_in_table =db_get_tables_list();
			//d($db_in_table );  ?>
              <? if(isarr($db_in_table )): ?>
              <select name="in_table"    class="mw-exec-option"  >
               <option  selected="selected" value="" >false</option>
  <? foreach($db_in_table  as $item): ?> 
  <option  value="<? print $item ?>" ><? print db_get_assoc_table_name($item)  ?></option>
   <? endforeach ; ?>
<? endif; ?>
</select>
             </td>
            <td>
           
            
            have</td>
          </tr>
          <tr>
            <td><a id="get_content_custom_fields_t" href="#messages">Custom fields</a></td>
            <td>      <div   style="display:none" id="get_content_custom_fields" class="mw-exec-custom-fields">
            
            <? $cfs = custom_field_names_for_table('content'); ?>
            <? if(isarr($cfs )): ?>
  <? foreach($cfs  as $item): ?> 
<label>
<? print $item['custom_field_name'] ?> 

<input name="custom_field_<? print $item['custom_field_name'] ?>"    type="text" class="mw-exec-option"  ></label>
 <? endforeach ; ?>
<? endif; ?>
            
            </div>
 </td>
            <td> </td>
          </tr>
        </tbody>
      </table>
                   

    </div>
  </div>
  <div class="bs-docs-example">
    <ul class="nav nav-tabs" id="myTabs_get_content">
      <li class="active"><a href="#get_content_output">Results</a></li>
      <li><a href="#get_content_output_src">PHP Code</a></li>
      <li><a href="#messages">Messages</a></li>
      <li><a href="#settings">Settings</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="get_content_output"></div>
      <div class="tab-pane" id="get_content_output_src"></div>
      <div class="tab-pane" id="settings">xccc.</div>
    </div>
    <script>
 

  $(function () {
	  $('#get_content_custom_fields_t').click(function (e) {
  e.preventDefault();
  $('#get_content_custom_fields').toggle();
})
     
	  
	  $('#myTabs_get_content a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
     
  })
</script> 
  </div>
  
  <!-- .panels --> 
</div>
<!-- .demo --> 
