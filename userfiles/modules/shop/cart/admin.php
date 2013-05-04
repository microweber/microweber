 <div class="mw_simple_tabs mw_tabs_layout_simple">
	<ul style="margin: 0;" class="mw_simple_tabs_nav">
		<li><a class="active" href="javascript:;">Settings</a></li>
		<li><a href="javascript:;">Skin/Template</a></li>
	</ul>
	<div class="tab">
		<div class="mw-ui-label">
			<strong>Checkout link enabled?</strong>
		</div>
		<div class="mw-ui-select" style="width: 100%;">
			<?php $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']); ?>
			<select name="data-checkout-link-enabled"  class="mw_option_field"  >
				<option    value="y"  <?php if(('n' != strval($checkout_link_enanbled))): ?>   selected="selected"  <?php endif; ?>>Yes</option>
				<option    value="n"  <?php if(('n' == strval($checkout_link_enanbled))): ?>   selected="selected"  <?php endif; ?>>No</option>
			</select>
		</div>
		<div class="mw-ui-label">
			<strong>Use Checkout Page From</strong>
		</div>
		<?php $selected_page=get_option('data-checkout-page', $params['id']); ?>
		<div class="mw-ui-select" style="width: 100%;">
			<select name="data-checkout-page"  class="mw_option_field"  >
				<option    value="default"  <?php if((0 == intval($selected_page)) or ('default' == strval($selected_page))): ?>   selected="selected"  <?php endif; ?>>Default</option>
				<?
					$pt_opts = array();
					$pt_opts['link'] = "{title}";
					$pt_opts['list_tag'] = " ";
					$pt_opts['is_shop'] = "y";
					$pt_opts['list_item_tag'] = "option";
					$pt_opts['active_ids'] = $selected_page;
					$pt_opts['active_code_tag'] = '   selected="selected"  ';
					pages_tree($pt_opts);
				?>
			</select>
		</div>
		<div class="mw-ui-label">
			<strong>Show Shipping to Country</strong>
		</div>
		<label class="mw-ui-check"><input value="y" <?php  if(get_option('show_shipping', $params['id']) == 'y'):  ?> checked="checked" <?php  endif ?> class="mw_option_field" name="show_shipping" type="checkbox"><span></span><span>Show shipping price into the shopping cart</span></label>
	</div>
	<div class="tab semi_hidden">
		<module type="admin/modules/templates"  />
	</div>
</div>
