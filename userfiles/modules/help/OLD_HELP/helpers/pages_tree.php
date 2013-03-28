<? $this_field_name = (!isset($field_name) or !$field_name) ? "content_id":$field_name;  ?>
<select name="<? print $this_field_name ?>"    class="mw-exec-option"  >
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
              </select>
              
 
