











<?php

    if(is_logged() == false){
      return mw()->url_manager->redirect(login_url());
    }

    $orders_params = array( 'created_by' => user_id(), 'order_by' => 'updated_on desc');
    $orders = get_orders($orders_params);

?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>


<script>

$(document).ready(function(){
    mw.on.hashParam('section', function(){
        if(this == false){
            mw.url.windowHashParam('section', 'profile');
        }
        else{
            $(".profile-tab").removeClass('active');
            $(".profile-tab-" + this).addClass('active');
            $(".ptab").hide();
            $(".ptab-" + this).show();
        }
    });
    var section = mw.url.getHashParams(window.location.hash).section;
    if(typeof section === 'undefined'){
        mw.url.windowHashParam('section', 'profile');
    }
});

</script>
<div class="mw-wrapper">
    <div class="mw-ui-row">
        <div class="mw-ui-col" style="width: 20%">
            <div class="mw-ui-col-container">
                <div class="mw-ui-btn-vertical-nav user-profile-menu">
                    <a class="mw-ui-btn profile-tab profile-tab-profile" href="#section=profile">Profile</a>
                    <a class="mw-ui-btn profile-tab profile-tab-orders" href="#section=orders">My orders</a>

                    <a class="mw-ui-btn" href="<?php print logout_url(); ?>">Logout</a>
                </div>
            </div>
        </div>
        <div class="mw-ui-col" >
            <div class="mw-ui-col-container">
                <div class="item-box pad my-order-items">







    <div class="ptab ptab-profile">
     <h2 class="icon-section-title"><span class="mw-icon-user"></span>Profile</h2>
    <div class="mw-ui-box mw-ui-box-content profile-box" id="user-data">

    <script>

        saveuserdata = function(){
            var data = mw.serializeFields('#user-data');
            if(data.password != data.password2){
                mw.$('#errnotification').html('Passwords do not match').show();
                return false;
            }
            else{
                mw.$('#errnotification').hide();
                if(data.password == ''){
                  delete data.password;
                  delete data.password2;
                }
            }
            mw.tools.loading('#user-data')
           $.post("<?php print api_url(); ?>save_user", data, function(){
                mw.tools.loading('#user-data', false);
           });
        }

    </script>
     <style>

     .profile-box > .mw-ui-row{
       margin-bottom: 24px;
     }

     </style>

     <?php
        $user = get_user_by_id(user_id());
     ?>

    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <label class="mw-ui-label">Username</label>
                <input type="text" name="username" value="<?php print $user['username']; ?>" class="mw-ui-field w100" />
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <label class="mw-ui-label">Email</label>
                <input type="text" name="email" class="mw-ui-field w100"  value="<?php print $user['email']; ?>" />
            </div>
        </div>
    </div>
    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <label class="mw-ui-label">First Name</label>
                <input type="text" name="first_name" class="mw-ui-field w100" value="<?php print $user['first_name']; ?>" />
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <label class="mw-ui-label">Last Name</label>
                <input type="text" name="last_name" class="mw-ui-field w100" value="<?php print $user['last_name']; ?>" />
            </div>
        </div>
    </div>


    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <label class="mw-ui-label">New Password</label>
                <input type="text" name="password" class="mw-ui-field w100" />
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <label class="mw-ui-label">Confirm Password</label>
                <input type="text" name="password2" class="mw-ui-field w100" />
            </div>
        </div>
    </div>
    <hr>
    <div class="mw-ui-box mw-ui-box-important mw-ui-box-content" id="errnotification" style="display: none;margin-bottom: 12px;"></div>
    <span class="mw-ui-btn mw-ui-btn-invert pull-right" onclick="saveuserdata()">Save</span>

</div></div>
















<?php if(isset($orders) and is_array($orders)): ?>
                    <div class="ptab ptab-orders">
                    <h2 class="icon-section-title"><span class="sm-icon-bag2"></span>Orders</h2>
                    <?php foreach($orders as $order){ ?>


                         <?php  $cart = get_cart('order_id='.$order['id']); ?>
                         <?php if(is_array($cart) and !empty($cart)): ?>
<div class="mw-ui-box mw-ui-box-content my-order">
                                <span class="my-order-status">Status:
                                    <?php if($order['order_status'] == 'completed'){ ?>
                                        <span class="my-order-status-completed">Completed</span>
                                    <?php } else{ ?>
                                        <span class="my-order-status-pending">Pending</span>
                                    <?php } ?>
                                </span>
                                <h4>Order #<?php print $order['id']; ?> - created on <?php print $order['created_on']; ?></h4>
                                <table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic">
                                    <thead>
                                        <tr>
                                          <th>Image </th>
                                          <th>Title</th>
                                          <th>Quantity</th>
                                          <th>Price</th>
                                          <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                           <?php foreach($cart as $product){ ?>
                                           <?php $theproduct = get_content_by_id($product['rel_id']); ?>
                                             <tr>
                                                <td><img src="<?php print get_picture($theproduct['id']); ?>" width="70" alt="" /> </td>
                                                <td><?php print $theproduct['title']; ?></td>
                                                <td><?php print $product['qty']; ?></td>
                                                <td><?php print $product['price']; ?></td>
                                                <td><?php print (intval($product['qty']) * intval($product['price'])); ?></td>
                                              </tr>
                                           <?php } ?>
                                    </tbody>
                                </table>
                            </div>
						<?php endif; ?>
                            
                    <?php } ?>
                    </div>

                   <?php else: ?>
                   <div class="mw-ui-box mw-ui-box-content my-order ptab ptab-orders">


                    <h2 class="icon-section-title text-center"><span class="sm-icon-bag2"></span>You have no orders</h2>

                   </div>
                   <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>