@if($statusModal)
    <script>
        $(document).ready(function () {
            var dialog = mw.dialog({
                content: $('#js-bulk-change-status').html(),
                title: 'Order Status',
                height: 'auto',
            });
        });
    </script>
    <template id="js-bulk-change-status">
        Order Status
        <select class="form-control" wire:model="bulkStatus">
            <option value="1">New</option>
            <option value="0">Pending</option>
        </select>
        <button type="button" class="btn btn-success mt-3" wire:click="statusExecute">Change</button>
    </template>
@endif
