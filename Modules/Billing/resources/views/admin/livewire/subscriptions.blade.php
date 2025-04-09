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

    <div class="table-responsive">
        <table class="table">
            <thead>
            <th><input type="checkbox" wire:click="selectAll"></th>
            <th>#</th>
            <th>Name</th>
            <th>User</th>
            <th>Stripe Id</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($subscriptions as $subscription)
                <tr>
                    <td>
                        <input wire:model="selectedIds" type="checkbox" value="{{ $subscription->id }}" />
                    </td>
                    <td>{{$subscription->id}}</td>
                    <td>{{$subscription->name}}</td>
                    <td>{{user_name($subscription->owner()->first()->user_id)}}</td>
                    <td>{{$subscription->stripe_id}}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button wire:click="cancel" class="dropdown-item"><i class="fa fa-clock"></i> Cancel</button></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
