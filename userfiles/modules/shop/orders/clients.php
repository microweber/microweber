<?php
only_admin_access();

?>
<script type="text/javascript">
    function mw_delete_shop_client($email) {
        var r = confirm("<?php _e("Are you sure you want to delete this client"); ?>?");
        if (r == true) {

            var r1 = confirm("<?php _e("ATTENTION"); ?>!!!!!!\n<?php _e("ALL ORDERS FROM THIS CLIENT WILL BE DELETED"); ?>!\n\n<?php _e("CLICK CANCEL NOW"); ?>\n<?php _e("OR"); ?>\n<?php _e("THERE IS NO TURNING BACK"); ?>!")
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


        mw.on.hashParam('clients_search', function () {


            var dis = this;
            if (dis !== '') {
                mw.$('#<?php print $params['id'] ?>').attr("data-keyword", dis);

            }
            else {
                mw.$('#<?php print $params['id'] ?>').removeAttr("data-keyword");
                mw.url.windowDeleteHashParam('clients_search')
            }
            mw.reload_module('#<?php print $params['id'] ?>');


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


$orders = get_orders('order_by=created_at desc&group=email&is_completed=1' . $keyword_search);


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

<div class="section-header">
    <h2 class="pull-left"><span class="mw-icon-users"></span>
        <?php _e("Clients List"); ?>
    </h2>
    <input type="text" class="mw-ui-searchfield pull-right active" placeholder="<?php _e("Search in clients"); ?>"
           onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('clients_search',this.value)})" value="<?php print $keyword ?>" autofocus="autofocus"/>
</div>
<hr>
<?php if ($is_orders != 0) { ?>
    <div class="table-responsive">
        <table class="mw-ui-table mw-order-table" id="shop-orders" cellpadding="0" cellspacing="0" width="960">
            <thead>
            <tr>
                <th><?php _e("Name & Number"); ?></th>
                <th><?php _e("Email"); ?></th>
                <th><?php _e("Client's Phone"); ?></th>
                <th><?php _e("Country"); ?></th>
                <th><?php _e("City"); ?></th>
                <th><?php _e("Orders #"); ?></th>
                <th><?php _e("View & Delete"); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td><?php _e("Name & Number"); ?></td>
                <td><?php _e("Email"); ?></td>
                <td><?php _e("Client's Phone"); ?></td>
                <td><?php _e("Country"); ?></td>
                <td><?php _e("City"); ?></td>
                <td><?php _e("Orders #"); ?></td>
                <td><?php _e("View & Delete"); ?></td>
            </tr>
            </tfoot>
            <tbody>
            <?php if (!empty($orders)): foreach ($orders as $order) : ?>
                <tr>
                    <td><?php print $order['first_name'] . " " . $order['last_name']; ?></td>
                    <td><?php print $order['email']; ?></td>
                    <td><?php print $order['phone']; ?></td>
                    <td><?php print $order['country']; ?></td>
                    <td><?php print $order['city']; ?></td>
                    <td><?php $total_ord = get_orders('count=1&email=' . $order['email'] . '&is_completed=1'); ?>
                        <?php print $total_ord; ?></td>
                    <td width="115">

                      <a class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-small" style="white-space: nowrap" href="#?clientorder=<?php print $order['id']; ?>">
                            <?php _e("View client"); ?>
                      </a>
                      <br><br>
                      <span class="show-on-hover mw-ui-btn mw-ui-btn-warn mw-ui-btn-small">
                        <span class="mw-icon-bin" onclick="mw_delete_shop_client('<?php print ($order['email']) ?>');"></span>
                      </span>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="mw-ui-box mw-ui-box-content info-box">
        <h2>
            <?php _e("You don't have any clients"); ?>
        </h2>
    </div>
<?php } ?>
