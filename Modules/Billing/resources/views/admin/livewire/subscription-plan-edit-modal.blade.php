<div>
    <form>
        <div class="modal-header">
            <h5 class="modal-title">Edit Subscription Plan</h5>
            <button type="button" class="btn-close mw-process-close-modal-button" wire:click="$emit('closeModal')"></button>
        </div>
        <div class="modal-body" style="padding:20px;max-height: 500px;overflow-y: scroll">

            <div class="mt-2">
                Name <br/>
                <input type="text" required="required" class="form-control" wire:model="state.name">
            </div>


            <div class="mt-2">
                Description <br/>
                <textarea class="form-control" rows="10" cols="50"  wire:model="state.description"></textarea>
            </div>

            <div class="mt-2">
                SKU <br/>
                <input type="text" required="required" class="form-control" wire:model="state.sku">
            </div>

            <div class="mt-2">
                Group <br/>
                <select wire:model="state.group_id" required="required" class="form-control">
                    @foreach($groups as $group)
                        <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-2">
                Remote Provider <br/>
                <input type="text" required="required" class="form-control" wire:model="state.remote_provider">
            </div>

            <div class="mt-2">
                Remote Provider Price Id<br/>
                <input type="text" required="required" class="form-control" wire:model="state.remote_provider_price_id">
            </div>

            <div class="mt-2">
                Display Price <br/>
                <input type="text" required="required" class="form-control" wire:model="state.display_price">
            </div>

            <div class="mt-2">
                Discount Price <br/>
                <input type="text" required="required" class="form-control" wire:model="state.discount_price">
            </div>

            <div class="mt-2">
                Save Price <br/>
                <input type="text" required="required" class="form-control" wire:model="state.save_price">
            </div>

            <div class="mt-2">
                Save Price Badge <br/>
                <input type="text" required="required" class="form-control" wire:model="state.save_price_badge">
            </div>

            <div class="mt-2">
                Billing Interval <br/>
                <select wire:model="state.billing_interval" required="required" class="form-control">
                    <option value="">Select Interval</option>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="semiannually">Semiannually</option>
                    <option value="annually">Annually</option>
                    <option value="biennially">Biennially</option>
                    <option value="triennially">Triennially</option>
                    <option value="lifetime">
                        Lifetime
                    </option>
                </select>
            </div>

            <div class="mt-2">
                Alternative Annual Plan <br/>
                <select wire:model="state.alternative_annual_plan_id" required="required" class="form-control">
                    <option value="">Select Interval</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan['id'] }}">{{ $plan['name'] }}</option>
                    @endforeach
                </select>
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" wire:click="save" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
