 <div class="module-live-edit-settings">
  <style scoped="scoped">

  .tab{
    display: none;
  }

  </style>

  <script>

  $(document).ready(function(){
     mw.tabs({
        nav:'.mw-ui-btn-nav-tabs a',
        tabs:'.tab'
     });
  });

  </script>

 <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
		<a class="mw-ui-btn active" href="javascript:;"><?php _e("Settings"); ?></a>
		<a class="mw-ui-btn" href="javascript:;"><?php _e("Skin/Template"); ?></a>
	</div>
    <div class="mw-ui-box mw-ui-box-content">


	<div class="tab" style="display: block;">


<div class="mw-ui-row-nodrop">
    <div class="mw-ui-col">		<div class="mw-ui-label">
			<strong><?php _e("Checkout link enabled"); ?>?</strong>
		</div>

			<?php $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']); ?>
			<select name="data-checkout-link-enabled"  class="mw-ui-field mw_option_field"  >
				<option  value="y"  <?php if(('n' != strval($checkout_link_enanbled))): ?>  selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
				<option  value="n"  <?php if(('n' == strval($checkout_link_enanbled))): ?>  selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
			</select></div>
    <div class="mw-ui-col">

  		<div class="mw-ui-label">
  			<strong><?php _e("Use Checkout Page From"); ?></strong>
  		</div>
  		<?php $selected_page=get_option('data-checkout-page', $params['id']); ?>

  			<select name="data-checkout-page"  class="mw-ui-field mw_option_field"  >
  				<option  value="default"  <?php if((0 == intval($selected_page)) or ('default' == strval($selected_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>
  				<?php
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
</div>






		
		 
	</div>
	<div class="tab">
		<module type="admin/modules_manager/templates"  />
	</div>
    </div>
</div>
