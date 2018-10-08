<?php only_admin_access(); ?>

<div class="module-live-edit-settings">

    <script>
        mw.require('ui.css');
        mw.lib.require('jqueryui');
        mw.require("<?php print $config['url_to_module'];?>css/main.css");
    </script>

	<div id="tabsnav">
		<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
			<a href="javascript:;" class="mw-ui-btn active tabnav">Coupons</a>
			<a href="javascript:;" class="mw-ui-btn tabnav">Skin/Template</a>
		</div>
		<div class="mw-ui-box">
			<div class="mw-ui-box-content tabitem">

				<div>
					<a class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline js-add-new-coupon"
						href="#"><span> <span class="mai-plus"></span> Add new coupon </span></a>
				</div>

				<hr>
				
				<div>
					<module type="shop/coupons/edit_coupons" />
				</div>
				
			</div>

			<div class="mw-ui-box-content tabitem" style="display: none">
				<module type="admin/modules/templates" />
			</div>

		</div>
	</div>
</div>


<script>
	function editCoupon(coupon_id = false) {
	    var data = {};
	    data.coupon_id = coupon_id;
	    editModal = mw.tools.open_module_modal('shop__coupons/edit_coupon', data, {overlay: true, skin: 'simple'})
	}

    function deleteCoupon(coupon_id) {
        var confirmUser = confirm('<?php _e('Are you sure to delete this coupon permanently?'); ?>');
        if (confirmUser == true) {
            $.ajax({
                    url: '<?php print api_url('coupon_delete');?>',
                    data: 'coupon_id=' + coupon_id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (typeof(reload_coupon_after_save) != 'undefined') {
                        	reload_coupon_after_save();
                        }
                    }
            });
        }
    }
	
	function reload_coupon_after_save() {
        mw.reload_module_parent('#<?php print $params['id'] ?>');
        mw.reload_module('shop/coupons/edit_coupons');
        window.parent.$(window.parent.document).trigger('shop.coupons.update');
        if (typeof(editModal) != 'undefined' && editModal.modal) {
            editModal.modal.remove();
        }
    }
    
    $(document).ready(function () {
        // Add tabs
        mw.tabs({
            nav: '#tabsnav  .tabnav',
            tabs: '#tabsnav .tabitem'
        });
        
        $(".js-add-new-coupon").click(function(){
        	editCoupon(false);
    	});
    });
</script>