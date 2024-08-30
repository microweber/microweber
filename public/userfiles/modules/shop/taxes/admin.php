<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>


<?php
$defined_taxes = mw()->tax_manager->get();
?>


<div class="card">
    <div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
       <div class="row">


           <div class="card-header d-flex align-items-center justify-content-between px-0">
               <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
               <?php
               if (!empty($defined_taxes)):
               ?>
               <a class="btn btn-primary btn-rounded" href="javascript:mw_admin_edit_tax_item_popup(0)"><?php _e('Add new tax'); ?></a>
               <?php
               endif;
               ?>
           </div>

           <?php
           if (!empty($defined_taxes)):
           ?>

            <div x-data="{taxIsEnabled:<?php if (get_option('enable_taxes', 'shop') == '1'): ?>true<?php else: ?>false<?php endif; ?> }"
                 class="d-flex align-items-center justify-content-center py-2" style="background:#e1edf9;border-radius:5px;padding:5px;display:inline;width:150px">
                <label class="form-check form-check-single form-switch ps-0">
                    <input type="checkbox" x-model="taxIsEnabled" name="enable_taxes" class="mw_option_field form-check-input" id="enable_taxes" data-option-group="shop" data-value-checked="1" data-value-unchecked="0" <?php if (get_option('enable_taxes', 'shop') == '1'): ?>checked<?php endif; ?> /
                </label> &nbsp; 
                <span x-show="taxIsEnabled"><?php _e('Enabled'); ?></span>
                <span x-show="!taxIsEnabled"><?php _e('Disabled'); ?></span>
            </div>

           <?php
           endif;
           ?>

           <script>
               mw_admin_edit_tax_item_popup_modal_opened = null

               function mw_admin_edit_tax_item_popup(tax_item_id) {
                   if (!!tax_item_id) {
                       var modalTitle = '<?php _e('Edit tax item'); ?>';
                   } else {
                       var modalTitle = '<?php _e('Add tax item'); ?>';
                   }

                   mw_admin_edit_tax_item_popup_modal_opened = mw.dialog({
                       content: '<div id="mw_admin_edit_tax_item_module"></div>',
                       title: modalTitle,
                       id: 'mw_admin_edit_tax_item_popup_modal'
                   });

                   var params = {}
                   params.tax_item_id = tax_item_id;
                   mw.load_module('shop/taxes/admin_edit_tax_item', '#mw_admin_edit_tax_item_module', null, params);
               }

               function mw_admin_delete_tax_item_confirm(tax_item_id) {
                   var r = confirm("<?php _ejs('Are you sure you want to delete this tax?'); ?>");
                   if (r == true) {
                       var url = mw.settings.api_url + 'shop/delete_tax_item';
                       $.post(url, {id: tax_item_id})
                           .done(function (data) {
                               mw_admin_after_changed_tax_item();
                           });
                   }
               }

               function mw_admin_after_changed_tax_item() {
                   mw.notification.success("<?php _ejs('Taxes are updated'); ?>");
                   // mw.reload_module('#mw_admin_shop_taxes_items_list');
                   mw.reload_module('shop/taxes');
                   mw.reload_module_everywhere('shop/taxes/admin')
                   mw.reload_module_everywhere('shop/taxes/admin_list_taxes')
                   mw.reload_module_everywhere('shop/cart')
               }

               $(document).ready(function () {
                   $(window).on("mw.admin.shop.tax.edit.item.saved", function () {
                       if (typeof('mw_admin_edit_tax_item_popup_modal_opened') != 'null') {
                           mw_admin_edit_tax_item_popup_modal_opened.remove();
                       }
                       mw_admin_after_changed_tax_item();
                   });
               });
           </script>

           <script type="text/javascript">
               $(document).ready(function () {
                   mw.options.form('.<?php print $config['module_class'] ?>', function () {
                       mw.notification.success("<?php _ejs("Saved"); ?>.");
                   });
               });
           </script>

           <div class="mt-3">
               <module type="shop/taxes/admin_list_taxes" id="mw_admin_shop_taxes_items_list"/>
           </div>
       </div>
    </div>

</div>


