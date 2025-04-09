<div>

    @if(!empty($selectedIds))
        <div class="mt-4 mb-4">
            <b>{{count($selectedIds)}} items are selected</b>

            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Actions
                </button>
                <ul class="dropdown-menu">
                    <li><button wire:click="bulkCancel" class="dropdown-item"><i class="fa fa-clock"></i> Cancel</button></li>
                </ul>
            </div>

        </div>
    @endif


    <div class="mt-2 mb-2">
        <button class="btn btn-outline-success btn-sm" wire:click="$emit('openModal', 'billing::subscription-plan-edit-modal')">
            Add new Plan
        </button>
        <button class="btn btn-outline-success btn-sm" wire:click="syncPricesFromStripe">
            Sync Plans from Stripe
        </button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
           {{-- <th><input type="checkbox" wire:click="selectAll"></th>--}}
            <th>#</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Display Price</th>
            <th>Billing Interval</th>
            <th>Remote Provider</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($subscriptionPlans as $subscriptionPlan)
                <tr>
                  {{--  <td>
                        <input wire:model="selectedIds" type="checkbox" value="{{ $subscriptionPlan->id }}" />
                    </td>--}}
                    <td>{{$subscriptionPlan->id}}</td>
                    <td>{{$subscriptionPlan->name}}</td>
                    <td>{{$subscriptionPlan->sku}}</td>
                    <td>{{$subscriptionPlan->display_price}}</td>
                    <td>{{ucfirst($subscriptionPlan->billing_interval)}}</td>
                    <td>{{ucfirst($subscriptionPlan->remote_provider)}}</td>
                    <td>
                       {{-- <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button wire:click="cancel" class="dropdown-item"><i class="fa fa-clock"></i> Cancel</button></li>
                                </ul>
                            </div>
                        </div>--}}

                        <button class="btn btn-outline-success btn-sm" wire:click="$emit('openModal', 'billing::subscription-plan-edit-modal', {{ json_encode(["subscriptionPlanId" => $subscriptionPlan->id]) }})">
                            <i class="fa fa-edit"></i> Edit Plan
                        </button>

                        @if($this->deleteConfirm == $subscriptionPlan->id)
                            <button class="btn btn-outline-danger btn-sm" wire:click="deleteExecute('{{$subscriptionPlan->id}}', true)">
                                Are you sure want to delete?
                            </button>
                        @else
                            <button class="btn btn-outline-danger btn-sm" wire:click="delete('{{$subscriptionPlan->id}}')">
                              <i class="fa fa-times"></i>  Delete Plan
                            </button>
                        @endif


                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
