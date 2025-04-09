<div>
    <form>
        <div class="modal-header">
            <h5 class="modal-title">Edit User Subscription</h5>
            <button type="button" class="btn-close mw-process-close-modal-button" wire:click="$emit('closeModal')"></button>
        </div>
        <div class="modal-body" style="padding:35px;max-height: 500px;overflow-y: scroll">

            <div>
                <label>Email:</label>
                <div class="mt-2">
                    <b>
                        {{$userEmail}}
                    </b>
                </div>
            </div>

            <div class="mt-4">
                <label>Subscription Plan</label>
                <select wire:model="activeSubscriptionId" class="form-control">
                    <option value="">Select Subscription Plan</option>
                    <option value="free_trial">Turn back to FREE TRIAL</option>
                    <option value="no_plan">No Plan</option>
                    @foreach($subscriptionPlans as $subscriptionPlan)
                        <option value="{{ $subscriptionPlan->id }}">
                            {{ $subscriptionPlan->name }}
                            @if($subscriptionPlan->id == $activeSubscriptionId)
                                (Current)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <div class="mt-4">
                    <label>
                    <input type="checkbox" wire:model="autoActivateFreeTrialAfterDate">
                    Automatically activate free trial after date
                    </label>
                </div>
                <div class="mt-4" style="@if(!$autoActivateFreeTrialAfterDate) display:none; @endif">
                    Activate free trial after date:
                    <input type="date" wire:model="activateFreeTrialAfterDate" class="form-control">
                </div>
            </div>

            <div class="mt-4">
                <button type="button" wire:click="save" class="btn btn-outline-dark">Save</button>
            </div>

        </div>
    </form>
</div>
