<div>

    <div wire:loading>
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden"><?php _e("Loading") ?>...</span>
            </div>
        </div>
    </div>

    <div class="row py-0">
    <div class="col-md-12">
        <div class="bulk-actions-show-columns flex">


            @if(count($checked) > 0)
                <div class="flex">

                @if (count($checked) == count($orders->items()))
                    <div >
                        <?php _e("You have selected all") ?> {{ count($checked) }} <?php _e("items") ?>.
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll"><?php _e("Deselect All") ?></button>
                    </div>
                @else
                    <div>
                        <?php _e("You have selected") ?> {{ count($checked) }} <?php _e("items") ?>,
                        <?php _e("Do you want to Select All") ?> {{ count($orders->items()) }}?
                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="selectAll"><?php _e("Select All") ?></button>
                        <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deselectAll"><?php _e("Deselect All") ?></button>
                    </div>
                @endif

                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php _e("Bulk Actions") ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button" wire:click='$emit("openModal", "admin-orders-bulk-order-status", {{ json_encode(["ids" => $checked]) }})'><i class="fa fa-truck"></i> <?php _e("Change Order Status") ?></button></li>
                            <li><button class="dropdown-item" type="button" wire:click='$emit("openModal", "admin-orders-bulk-payment-status", {{ json_encode(["ids" => $checked]) }})'><i class="fa fa-money-bill"></i> <?php _e("Change Payment Status") ?></button></li>
                            <li><button class="dropdown-item" type="button" wire:click='$emit("openModal", "admin-orders-bulk-delete", {{ json_encode(["ids" => $checked]) }})'><i class="fa fa-times"></i> <?php _e("Delete") ?></button></li>
                        </ul>
                    </div>
                </div>

                </div>
            @endif

            <div class="row p-0">
                <div class="d-flex flex-wrap bulk-actions-show-columns mw-js-loading position-relative">

                @include('content::admin.content.livewire.components.display-as')

                <div class="col-md-7 col-12 d-flex justify-content-end align-items-center px-0 mw-filters-sorts-mobile">

                    <div class="ms-2" x-data="{ openSortDropdown: false }">
                            <span @click="openSortDropdown =! openSortDropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M479.788-192Q450-192 429-213.212q-21-21.213-21-51Q408-294 429.212-315q21.213-21 51-21Q510-336 531-314.788q21 21.213 21 51Q552-234 530.788-213q-21.213 21-51 21Zm0-216Q450-408 429-429.212q-21-21.213-21-51Q408-510 429.212-531q21.213-21 51-21Q510-552 531-530.788q21 21.213 21 51Q552-450 530.788-429q-21.213 21-51 21Zm0-216Q450-624 429-645.212q-21-21.213-21-51Q408-726 429.212-747q21.213-21 51-21Q510-768 531-746.788q21 21.213 21 51Q552-666 530.788-645q-21.213 21-51 21Z"/></svg>
                            </span>

                        <span class="table-blade-sortable-elements bg-light shadow-sm align-items-center justify-content-center p-3" style=" display: none;" x-show="openSortDropdown">

                          <div class="d-flex align-items-center mx-1">
                            <label class="d-xl-block d-none mx-2"><?php _e("Sort") ?></label>
                            <select x-on:change="openSortDropdown = false" class="form-select form-select-sm" wire:model.stop="filters.orderBy" >
                                <option value=""><?php _e("Any") ?></option>
                                <option value="id,desc"><?php _e("Id Desc") ?></option>
                                <option value="id,asc"><?php _e("Id Asc") ?></option>

                                <option value="created_at,desc"><?php _e("Date Desc") ?></option>
                                <option value="created_at,asc"><?php _e("Date Asc") ?></option>
                            </select>
                          </div>

                            <div class="d-flex align-items-center mx-1">
                                <label class="d-xl-block d-none mx-2"><?php _e("Limit") ?></label>
                                <select x-on:change="openSortDropdown = false" class="form-select form-select-sm" wire:model="paginationLimit">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                </select>
                            </div>
                        </span>
                    </div>


                    @if($displayType=='table')


                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle ms-2" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php _e("Columns") ?>
                            </button>
                            <div class="dropdown-menu p-3">
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.id">
                                        <span class="form-check-label"><?php _e("Id") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.image">
                                        <span class="form-check-label"><?php _e("Image") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.products">
                                        <span class="form-check-label"><?php _e("Products") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.customer">
                                        <span class="form-check-label"><?php _e("Customer") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.total_amount">
                                        <span class="form-check-label"><?php _e("Total Amount") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.shipping_method">
                                        <span class="form-check-label"><?php _e("Shipping Method") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.payment_method">
                                        <span class="form-check-label"><?php _e("Payment Method") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.payment_status">
                                        <span class="form-check-label"><?php _e("Payment Status") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.status">
                                        <span class="form-check-label"><?php _e("Status") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.created_at">
                                        <span class="form-check-label"><?php _e("Created At") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.updated_at">
                                        <span class="form-check-label"><?php _e("Updated At") ?></span>
                                    </label>
                                </div>
                                <div class="dropdown-item">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" wire:model="showColumns.actions">
                                        <span class="form-check-label"><?php _e("Actions") ?></span>
                                    </label>
                                </div>

                            </div>
                        </div>

                    @endif
                </div>

              </div>
            </div>

        </div>
    </div>
    </div>

    <div class="row py-0">
        <div class="col-md-12">
            @if($displayType == 'card')
                @include('order::admin.orders.livewire.display-types.card')
            @endif

            @if($displayType == 'table')
                @include('order::admin.orders.livewire.display-types.table')
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">

        <div style="width: 100%">
            <span class="text-muted">{{ $orders->total() }} <?php _e("results found") ?></span>
        </div>

        <div>
        {{ $orders->links() }}
        </div>
    </div>

</div>


