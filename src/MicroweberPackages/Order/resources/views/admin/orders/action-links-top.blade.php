<div class="col-auto d-flex justify-content-md-end justify-content-end px-0 ms-md-0 ms-3">
    <a href="{{route('admin.order.abandoned')}}" class="btn btn-outline-dark me-2">
        {{_e('Abandoned')}} <span class="d-sm-block d-none ms-sm-1">{{_e('Carts')}}</span>
    </a>
    <a href="javascript:mw_admin_add_order_popup()" class="btn btn-dark me-2">
        <svg fill="currentColor" class="me-sm-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>
        <span class="d-sm-block d-none">{{_e("New Order")}}</span>
    </a>
</div>
