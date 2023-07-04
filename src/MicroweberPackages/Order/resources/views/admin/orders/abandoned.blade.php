@extends('admin::layouts.app')

@section('content')


<div id="manage-orders-menus" class="col-xxl-10 col-lg-11 col-12 mx-auto">
    <div class="d-flex justify-content-between mb-4 align-items-center">
        <h3 class="main-pages-title mb-0"><?php _e("List of orders"); ?></h3>
        <button onclick="mw_admin_add_order_popup()" class="btn btn-outline-dark ml-2">

            <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>
            <?php _e('New order'); ?></button>
    </div>
    <div class="card ">

        <div class="  pb-0">

            @include('order::admin.orders.partials.abandoned_filtering')

            <?php if ($orders->count() > 0): ?>
                <?php foreach ($orders as $order): ?>
                @include('order::admin.orders.abandoned_card', ['order'=>$order])`
                <?php endforeach;?>
            <?php endif; ?>

            <?php if (($filteringResults == false) && (count($orders) == 0)): ?>
            <div class="no-items-found orders ">
                <div class="row ">
                    <div class="col-12">
                        <div class="no-items-box d-flex justify-content-center align-items-center text-center">
                            <div>
                                <label class="form-label font-weight-bold"><?php _e("You don’t have any orders yet"); ?></label>
                                <small class="text-muted"><?php _e("No abandoned carts found"); ?></small>
                            </div>
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


@endsection
