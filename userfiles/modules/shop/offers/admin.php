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

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <script>
            mw.lib.require('jqueryui');
            mw.require("<?php print $config['url_to_module'];?>css/main.css");
        </script>

        <script>
            function editOffer(offer_id = false) {
                var data = {};
                var mTitle = (offer_id ? 'Edit offer' : 'Add new offer');
                data.offer_id = offer_id;
                editModal = mw.tools.open_module_modal('shop/offers/edit_offer', data, {overlay: true, skin: 'simple', title: mTitle})
            }

            function reload_offer_after_save() {
                mw.reload_module_parent('#<?php print $params['id'] ?>');
                mw.reload_module('shop/offers/edit_offers');
                window.parent.$(window.parent.document).trigger('shop.offers.update');
                if (typeof(editModal) != 'undefined' && editModal.modal) {
                    editModal.modal.remove();
                }
            }

            function deleteOffer(offer_id) {
                var confirmUser = confirm('<?php _e('Are you sure you want to delete this offer?'); ?>');
                if (confirmUser == true) {
                    $.ajax({
                        url: '<?php print route('api.offer.delete');?>',
                        data: 'offer_id=' + offer_id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            mw.notification.success('Price is deleted')
                            if (typeof(reload_offer_after_save) != 'undefined') {
                                reload_offer_after_save();
                            }
                            mw.reload_module('#<?php print $params['id'] ?>')
                            mw.reload_module_parent('custom_fields')

                        }
                    });
                }
            }


            $(document).ready(function () {

                $(".js-add-new-offer").click(function () {
                    editOffer(false);
                });
            });
        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('List of Offers'); ?></a>
            <?php if ($from_live_edit) : ?>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
            <?php endif; ?>

        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <div class="mb-3">
                    <a class="btn btn-primary btn-rounded js-add-new-offer" href="javascript:;"><?php _e('Add new offer'); ?></a>
                </div>

                <module type="shop/offers/edit_offers"/>
            </div>

            <?php if ($from_live_edit) : ?>
                <div class="tab-pane fade" id="templates">
                    <module type="admin/modules/templates"/>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
