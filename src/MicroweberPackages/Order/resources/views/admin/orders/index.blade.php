<div id="manage-orders-menus">
    <div class="card style-1">
        <div class="card-header d-flex align-items-center col-12">
            <div class="col-md-7 d-flex justify-content-md-start justify-content-center align-items-center px-0">
                <h5><i class="mdi mdi-post-outline text-primary mr-3"></i><strong><?php _e("List of orders"); ?></strong></h5>
                <button onclick="mw_admin_add_order_popup()" class="btn btn-sm btn-outline-success ml-2"><?php _e('Add new order'); ?></button>
            </div>
               <div class="col-5 d-flex justify-content-md-end justify-content-center my-md-0 mt-2 pr-0">
                   @include('order::admin.orders.partials.order_search')
               </div>
        </div>
        <div class="card-body pt-3 pb-0">
            @include('order::admin.orders.partials.order_filtering')


            <div class="row d-flex justify-content-between align-content-end">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
            <div class="form-group">
                <div class="input-group mb-0">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?php _e("Sort By"); ?></span>
                    </div>
                    <select class="form-control" onchange="location = this.value;">

                        <option disabled="disabled"><?php _e("Select sorting"); ?></option>

                        <option <?php if($orderBy == 'created_at' && $orderDirection == 'desc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=desc"><?php _e("Order date"); ?> <?php _e("[New > Old]"); ?></option>
                        <option <?php if($orderBy == 'created_at' && $orderDirection == 'asc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=created_at&orderDirection=asc"><?php _e("Order date"); ?> <?php _e("[Old > New]"); ?></option>

                        {{-- <option <?php if($orderBy == 'order_status' && $orderDirection == 'desc'): ?>selected="selected"
                                 <?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=desc"><?php _e("Status"); ?> <?php _e("[NEW]"); ?></option>
                         <option <?php if($orderBy == 'order_status' && $orderDirection == 'asc'): ?>selected="selected"
                                 <?php endif;?> value="{{route('admin.order.index')}}?orderBy=order_status&orderDirection=asc"><?php _e("Status"); ?> <?php _e("[OLD]"); ?></option>
    --}}

                        <option <?php if($orderBy == 'amount' && $orderDirection == 'desc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=desc"><?php _e("Amount"); ?> <?php _e("[High > Low]"); ?></option>
                        <option <?php if($orderBy == 'amount' && $orderDirection == 'asc'): ?>selected="selected"
                                <?php endif;?> value="{{route('admin.order.index')}}?orderBy=amount&orderDirection=asc"><?php _e("Amount"); ?> <?php _e("[Low > High]"); ?></option>


                    </select>
                </div>
            </div>
            </div>
            </div>



            <?php if (count($orders) > 0): ?>
            <label class="control-label mb-3 mt-3"><?php _e('All orders'); ?></label>
            <?php foreach ($orders as $order): ?>
                @include('order::admin.orders.order_card')
            <?php endforeach;?>
             <?php endif; ?>

            <?php if (($filteringResults == true) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_orders.svg'); ">
                            <h4><?php _e("No orders found for this query filtering"); ?></h4>
                            <p><?php _e("Try with other filters"); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (($filteringResults == false) && (count($orders) == 0)): ?>
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_orders.svg'); ">
                            <h4><?php _e("You don’t have any orders yet"); ?></h4>
                            <p><?php _e("Here you can track your orders"); ?></p>
                            <br/>
                            <a href="javascript:mw_admin_add_order_popup()" class="btn btn-primary btn-rounded"><?php _e("Add order"); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex">
                <div class="mx-auto">
                    <?php echo $orders->links("pagination::bootstrap-4"); ?>
                </div>
            </div>
        </div>
    </div>
</div>

@include('order::admin.orders.partials.javascripts')
