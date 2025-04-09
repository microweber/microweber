<div>

    <div class="mb-4 mt-2">
        Keyword: <br />
        <input type="text" class="form-control" wire:model="keyword">
    </div>

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

    <div class="">
        <table class="table">
            <thead>
            <th><input type="checkbox" wire:click="selectAll"></th>
            <th>UserId#</th>
            <th>Email</th>
            <th>Subscription</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        <input wire:model="selectedIds" type="checkbox" value="{{ $user->id }}" />
                    </td>
                    <td>{{$user->id}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                    @php
                        $activeSubscription = getUserActiveSubscriptionPlanBySKU($user->id, 'hosting');
                        if($activeSubscription) {
                            echo "<b>" . $activeSubscription['name'] . "</b>";
                        } else {
                            echo '<span class="text-danger">No active subscription</span>';
                        }
                    @endphp
                    </td>

                    <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item" wire:click="$emit('openModal', 'billing::user-subscription-edit-modal', {{ json_encode(["userId" => $user->id]) }})">
                                            <i class="fa fa-edit"></i> Edit Subscription
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{$users->links('livewire::bootstrap')}}
    </div>
</div>
