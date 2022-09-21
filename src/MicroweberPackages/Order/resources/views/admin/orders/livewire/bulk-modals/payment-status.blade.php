@if($paymentStatusModal)
    <template id="js-bulk-change-payment-status">
        Payment Status
        <select class="form-control js-bulk-change-payment-status-select">
            <option value="1">Paid</option>
            <option value="0">Unpaid</option>
        </select>
        <button type="button" class="btn btn-success mt-3 js-bulk-change-payment-status-change">Change</button>
    </template>
    <script>
        $(document).ready(function () {
            window.paymentStatusModal = mw.dialog({
                content: $('#js-bulk-change-payment-status').html(),
                title: 'Payment Status',
                height: 'auto'
            });
            $('.js-bulk-change-payment-status-change').click(function () {
                var status = $('.js-bulk-change-payment-status-select').val();
                window.livewire.emit('paymentStatusExecute', status);
            });
        });
    </script>
@endif
<script>
    window.livewire.on('paymentStatusModal', status => {
        if (!status) {
            if (window.paymentStatusModal) {
                window.paymentStatusModal.remove();
            }
        }
    });
</script>
