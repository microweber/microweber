<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-user-filter" wire:ignore >
    <label class="d-block">
        Shipping Type
    </label>

    <select wire:model.stop="filters.shipping.service" class="form-control">
        <option value="">Any</option>
        @foreach(app()->shipping_manager->getShippingModules() as $shippingModule)
        <option value="{{$shippingModule['module']}}">{{$shippingModule['name']}}</option>
        @endforeach
    </select>

</div>
