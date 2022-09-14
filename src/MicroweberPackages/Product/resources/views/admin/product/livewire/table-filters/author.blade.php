<div class=" col-12 col-sm-6 col-md-3 col-lg-3 mb-4 ">
    <label class="d-block">
        Author
    </label>

    <input wire:model.stop="filters.author" id="js-filter-author" type="hidden" class="form-control">

    <div class="mb-3 mb-md-0 input-group">

        <div id="js-filter-author-select"></div>

        <script>mw.require('autocomplete.js')</script>

        <script>
            $(document).ready(function () {
                var filterAuthorFiled = new mw.autoComplete({
                    element: "#js-filter-author-select",
                    ajaxConfig: {
                        method: 'get',
                        url: mw.settings.api_url + 'users/search_authors?kw=${val}',
                        cache: true
                    },
                    map: {
                        value: 'id',
                        title: 'display_name',
                        image: 'picture'
                    },
                    selected: [
                        {
                            id: '',
                            display_name: ''
                        }
                    ]
                });
                $(filterAuthorFiled).on("change", function (e, val) {
                    $("#js-filter-author").val(val[0].id).trigger('change')
                    document.getElementById('js-filter-author').dispatchEvent(new Event('input'));
                })
            });
        </script>
    </div>
</div>
