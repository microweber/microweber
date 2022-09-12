<div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4" wire:ignore>
    <label class="d-block">
        Tags
    </label>

    <input  wire:model.stop="filters.tags" id="js-filter-tags" type="hidden" />

        <input type="text" value="" id="js-tags-autocomplete" class="form-control">

        <script>
            function initTagsFilter() {

                let tagsSelected = [];
                let tagsElement = document.getElementById('js-filter-tags');

                if (tagsElement.value != '') {
                    tagsSelected = tagsElement.value.split(",");
                }

                mw.log($('#js-tags-autocomplete'));

                var tagsSelect = new mw.select({
                    element: '#js-tags-autocomplete',
                    multiple: false,
                    autocomplete: true,
                    tags: false,
                    placeholder: '',
                    ajaxMode: {
                        paginationParam: 'page',
                        searchParam: 'keyword',
                        endpoint: mw.settings.api_url + 'tagging_tag/autocomplete',
                        method: 'get'
                    }
                });

                $(tagsSelect).on("change", function (event, tag) {

                    tagsSelect.val = ''; 

                    tagsSelected.push(tag.title);
                    let tagsSelectedSeperated = tagsSelected.join(",");
                    tagsElement.value = tagsSelectedSeperated;
                    tagsElement.dispatchEvent(new Event('input'));

                });

            }

            initTagsFilter();
        </script>


</div>
