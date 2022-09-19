<div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4 js-order-product-filter" wire:ignore>
    <label class="d-block">
        Product
    </label>

    <input wire:model.stop="filters.productId" id="js-filter-product" type="hidden" class="form-control">

    <div class="mb-3 mb-md-0 input-group">
        <div id="js-orders-search-by-products" style="z-index: 200"></div>
    </div>

    <script>mw.require('autocomplete.js')</script>

    <script>
        $(document).ready(function () {

            var filterProductElement = document.getElementById('js-filter-product');

            var searchOrdersByProduct = new mw.autoComplete({
                element: "#js-orders-search-by-products",
                placeholder: "",
                autoComplete:true,
                ajaxConfig: {
                    method: 'get',
                    url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&content_type=product&keyword=${val}&limit=10'
                },
                map: {
                    value: 'id',
                    title: 'title',
                    image: 'picture'
                }
            });
            $(searchOrdersByProduct).on("change", function (e, val) {
                filterProductElement.value = val[0].id;
                filterProductElement.dispatchEvent(new Event('input'));
            });
        });
    </script>
</div>
