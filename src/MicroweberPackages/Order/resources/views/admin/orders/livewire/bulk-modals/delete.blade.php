@if($deleteModal)
    <div class="js-admin-orders-bulk-delete-modal">
        <script>
            mw.tools.confirm("Are you sure you want to delete the selected data?", function () {
                window.livewire.emit('deleteExecute');
            }, function () {
                window.livewire.emit('hideDeleteModal');
            });
        </script>
    </div>
@endif
