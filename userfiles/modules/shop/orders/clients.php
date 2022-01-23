<?php
must_have_access();
?>

<script type="text/javascript">
    function mw_delete_shop_client($email) {
        var r = confirm("<?php _ejs("Are you sure you want to delete this client"); ?>?");
        if (r == true) {

            var r1 = confirm("<?php _ejs("ATTENTION"); ?>!!!!!!\n<?php _ejs("ALL ORDERS FROM THIS CLIENT WILL BE DELETED"); ?>!\n\n<?php _ejs("CLICK CANCEL NOW"); ?>\n<?php _ejs("OR"); ?>\n<?php _e("THERE IS NO TURNING BACK"); ?>!")
            if (r1 == true) {
                $.post("<?php print api_link('delete_client') ?>", {email: $email}, function (data) {
                    mw.reload_module('shop/orders/clients');
                });
            }
        }
    }
</script>

<script>
    $(window).bind('load', function () {
        mw.on.hashParam('clients_search', function (pval) {
            var dis = pval;
            if (dis !== '') {
                mw.$('#<?php print $params['id'] ?>').attr("data-keyword", dis);

            } else {
                mw.$('#<?php print $params['id'] ?>').removeAttr("data-keyword");
                mw.url.windowDeleteHashParam('clients_search')
            }
            mw.reload_module('#<?php print $params['id'] ?>');
        })

        mw.responsive.table('#shop-orders', {
            breakPoints: {
                768: 4,
                600: 2,
                400: 1
            }
        })
    });
</script>


<?php
$keyword = '';
$keyword_search = '';
if (isset($params['keyword'])) {
    $keyword = strip_tags($params['keyword']);
    $keyword_search = '&keyword=' . $keyword;
}

$clients = array();
$orders = get_orders('order_by=created_at desc&groupby=email&is_completed=1' . $keyword_search);
$is_orders = get_orders('count=1');
?>

<?php if (isset($params['keyword']) and $params['keyword'] != false): ?>
    <script>
        $(function () {
            $('[autofocus]').focus(function () {
                this.selectionStart = this.selectionEnd = this.value.length;
            });

            $('[autofocus]:not(:focus)').eq(0).focus();
        });
    </script>
<?php endif; ?>

<div class="card style-1 bg-light mb-3">
    <div class="card-header">
        <h5><i class="mdi mdi-account-multiple text-primary mr-3"></i> <strong><?php _e("Clients List"); ?></strong></h5>
    </div>

    <div class="card-body pt-3">

        <div class="d-flex align-items-center justify-content-between mt-2 mb-4">
            <div>
                <div class="form-group mb-0">
                    <div class="input-group mb-0 prepend-transparent">
                        <div class="input-group-prepend bg-white">
                            <span class="input-group-text"><i class="mdi mdi-magnify mdi-20px"></i></span>
                        </div>
                        <input type="search" class="form-control" aria-label="Search" placeholder="<?php _e("Search in clients"); ?>" onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('clients_search',this.value)})" value="<?php print $keyword ?>" autofocus="autofocus" />
                    </div>
                </div>
            </div>
        </div>

        <?php if ($is_orders != 0 AND !empty($orders)): ?>
            <div class="table-responsive bg-white">
                <table cellspacing="0" cellpadding="0" class="table table-hover m-0" width="100%">
                    <tbody>
                    <?php foreach ($orders as $order) : ?>
                        <tr id="mw-admin-user-<?php print $order['id']; ?>">
                            <td style="vertical-align: middle;">
                                <?php if (user_picture($order['created_by'])): ?>
                                    <div class="img-circle-holder img-absolute w-60">
                                        <img src="<?php echo user_picture($order['created_by']); ?>">
                                    </div>
                                <?php else: ?>
                                    <div class="img-circle-holder img-absolute w-60">
                                        <img src="<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no-image.jpg"/>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td style="vertical-align: middle;">
                                <span class="d-block text-outline-primary font-weight-bold"><?php print $order['first_name'] . " " . $order['last_name']; ?></span>
                                <?php $total_ord = get_orders('count=1&email=' . $order['email'] . '&is_completed=1'); ?>
                                <small class="text-muted"><?php _e("Orders"); ?>:</small>
                                <span><?php print $total_ord; ?></span>
                            </td>

                            <td style="vertical-align: middle;">
                                <small class="text-muted d-block"><?php _e("Phone"); ?></small>
                                <span><?php print $order['phone']; ?></span></td>

                            <td style="vertical-align: middle;">
                                <small class="text-muted d-block"><?php _e("Email"); ?></small>
                                <?php print $order['email']; ?>
                            </td>

                            <td style="vertical-align: middle;">
                                <small class="text-muted d-block"><?php _e("Address"); ?></small>
                                <?php print $order['country']; ?>
                                <?php print $order['city']; ?>
                            </td>

                            <td style="vertical-align: middle;">
                                <span class="btn btn-outline-danger btn-sm del-row" title="<?php _e("Delete"); ?>" onclick="mw_delete_shop_client('<?php print ($order['email']) ?>');"><?php _e("Delete"); ?></span>
                                <a class="btn btn-outline-primary btn-sm" href="#?clientorder=<?php print $order['id']; ?>"><?php _e("View"); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <h3><?php _e("You don't have any clients"); ?></h3>
        <?php endif; ?>
    </div>
</div>
