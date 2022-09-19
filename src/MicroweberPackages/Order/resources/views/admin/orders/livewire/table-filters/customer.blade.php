<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4" wire:ignore>
    <label class="d-block">
        Customer
    </label>

    <input wire:model.stop="filters.customer" id="js-filter-customer" type="hidden" class="form-control">

    <div class="mb-3 mb-md-0 input-group">

        <div id="js-filter-customer-select"></div>

        <script>mw.require('autocomplete.js')</script>

        <script>
            document.addEventListener('livewire:load', function () {
                var customerId = $("#js-filter-customer").val();
                var filterCustomerFiled = new mw.autoComplete({
                    element: "#js-filter-customer-select",
                    ajaxConfig: {
                        method: 'get',
                        url: mw.settings.api_url + 'users/search_authors?kw=${val}&limit=10',
                        cache: true
                    },
                    map: {
                        value: 'id',
                        title: 'display_name',
                        image: 'picture'
                    },
                    selected: [
                        {
                            id: customerId,
                            display_name: customerId
                        }
                    ]
                });
                $(filterCustomerFiled).on("change", function (e, val) {
                    $("#js-filter-customer").val(val[0].id).trigger('change')
                    document.getElementById('js-filter-customer').dispatchEvent(new Event('input'));
                })
            });
        </script>
    </div>
</div>
