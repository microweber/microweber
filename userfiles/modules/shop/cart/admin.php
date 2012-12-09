 <strong>checkout link enabled?</strong>
 
 <?php $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']); ?>
 <select name="data-checkout-link-enabled"  class="mw_option_field"  >
  <option    value="y"  <? if(('n' != strval($checkout_link_enanbled))): ?>   selected="selected"  <? endif; ?>>Yes</option>
    <option    value="n"  <? if(('n' == strval($checkout_link_enanbled))): ?>   selected="selected"  <? endif; ?>>No</option>

  </select>
 

<br />
<br />


<?
$selected_page=get_option('data-checkout-page', $params['id']);

?>


<strong>Checkout page layout</strong>
<select name="data-checkout-page"  class="mw_option_field"  >
  <option    value="default"  <? if((0 == intval($selected_page)) or ('default' == strval($selected_page))): ?>   selected="selected"  <? endif; ?>>Default</option>
    <?
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['is_shop'] = "y";
$pt_opts['list_item_tag'] = "option";
$pt_opts['actve_ids'] = $selected_page;
$pt_opts['active_code_tag'] = '   selected="selected"  ';
pages_tree($pt_opts);
 ?>
</select>


<br />
<br />
<br />
<br />


<strong>Skin/Template</strong>
<module type="admin/modules/templates"  />
