<script>
    window.livewire.on('statusModal', status => {
        if (!status) {
            if (window.statusModal) {
                window.statusModal.remove();
            }
        }
    });
</script>

@if($statusModal)
    <script>
        $(document).ready(function () {
            window.statusModal = mw.dialog({
                content: $('#js-bulk-change-status').html(),
                title: 'Order Status',
                height: 'auto'
            });
            $('.js-bulk-change-status-change').click(function () {
                var status = $('.js-bulk-change-status-select').val();
                window.livewire.emit('statusExecute', status);
            });
        });
    </script>
    <template id="js-bulk-change-status">
        Order Status
        <select class="form-control js-bulk-change-status-select">
            <option value="1">Paid</option>
            <option value="0">Unpaid</option>
        </select>
        <button type="button" class="btn btn-success mt-3 js-bulk-change-status-change">Change</button>
    </template>
@endif
