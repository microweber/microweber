<div>
    <div class="modal-header">
        <h5 class="modal-title">Bulk Order Status Change</h5>
        <button type="button" class="btn btn-link" wire:click="$emit('closeModal')">
            <i class="fa fa-times text-muted"></i>
        </button>
    </div>
    <div class="modal-body">
        <div>
            Order Status<br/>
            <select class="form-control" wire:model="orderStatus">
                <option>Select order status</option>
                @foreach($orderStatuses as $key=>$value)
                <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" wire:click="$emit('closeModal')">Cancel</button>
        <button type="button" class="btn btn-success" wire:click="change">Change</button>
    </div>
</div>

