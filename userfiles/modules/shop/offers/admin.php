<?php only_admin_access(); ?>

<?php
$from_live_edit = false;
if(isset($params["live_edit"]) and $params["live_edit"] ){
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="module-live-edit-settings">

    <script>
        mw.lib.require('jqueryui');
        mw.require("<?php print $config['url_to_module'];?>css/main.css");
    </script>

	<div id="tabsnav">
		<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
			<a href="javascript:;" class="mw-ui-btn active tabnav">Offers</a>

            <?php if($from_live_edit) : ?>
			<a href="javascript:;" class="mw-ui-btn tabnav">Skin/Template</a>

            <?php endif; ?>
		</div>
		<div class="mw-ui-box">
			<div class="mw-ui-box-content tabitem">

				<div>
					<a class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline js-add-new-offer"
						href="javascript:;"><span> <span class="mai-plus"></span> Add new offer </span></a>
				</div>

				<hr>

				<div>
					<module type="shop/offers/edit_offers" />
				</div>

			</div>
            <?php if($from_live_edit) : ?>

			<div class="mw-ui-box-content tabitem" style="display: none">
				<module type="admin/modules/templates" />
			</div>
            <?php endif; ?>

		</div>
	</div>
</div>


<script>
	function editOffer(offer_id = false) {
	    var data = {};
	    var mTitle = (offer_id ? 'Edit offer' : 'Add new offer');
	    data.offer_id = offer_id;
	    editModal = mw.tools.open_module_modal('shop/offers/edit_offer', data, {overlay: true, skin: 'simple', title: mTitle})
	}

    function deleteOffer(offer_id) {
        var confirmUser = confirm('<?php _e('Are you sure you want to delete this offer?'); ?>');
        if (confirmUser == true) {
            $.ajax({
                    url: '<?php print api_url('offer_delete');?>',
                    data: 'offer_id=' + offer_id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (typeof(reload_offer_after_save) != 'undefined') {
                        	reload_offer_after_save();
                        }
                    }
            });
        }
    }

	function reload_offer_after_save() {
        mw.reload_module_parent('#<?php print $params['id'] ?>');
        mw.reload_module('shop/offers/edit_offers');
        window.parent.$(window.parent.document).trigger('shop.offers.update');
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

        $(".js-add-new-offer").click(function(){
        	editOffer(false);
    	});
    });
</script>