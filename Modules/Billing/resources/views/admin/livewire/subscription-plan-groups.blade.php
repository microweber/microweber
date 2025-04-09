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
        <button class="btn btn-outline-success btn-sm" wire:click="$emit('openModal', 'billing::subscription-plan-group-edit-modal')">
            Add new Plan Group
        </button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
            {{-- <th><input type="checkbox" wire:click="selectAll"></th>--}}
            <th>#</th>
            <th>Name</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($subscriptionPlanGroups as $subscriptionPlanGroup)
                <tr>
                    {{--  <td>
                          <input wire:model="selectedIds" type="checkbox" value="{{ $subscriptionPlan->id }}" />
                      </td>--}}
                    <td>{{$subscriptionPlanGroup->id}}</td>
                    <td>{{$subscriptionPlanGroup->name}}</td>
                    <td>

                        @if($this->deleteConfirm == $subscriptionPlanGroup->id)
                            <button class="btn btn-outline-danger btn-sm" wire:click="deleteExecute('{{$subscriptionPlanGroup->id}}', true)">
                                Are you sure want to delete?
                            </button>
                        @else
                            <button class="btn btn-outline-danger btn-sm" wire:click="delete('{{$subscriptionPlanGroup->id}}')">
                                <i class="fa fa-times"></i>  Delete Plan Group
                            </button>
                        @endif

                        <button class="btn btn-outline-success btn-sm" wire:click="$emit('openModal', 'billing::subscription-plan-group-edit-modal', {{ json_encode(["subscriptionPlanGroupId" => $subscriptionPlanGroup->id]) }})">
                            <i class="fa fa-edit"></i> Edit Plan Group
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
