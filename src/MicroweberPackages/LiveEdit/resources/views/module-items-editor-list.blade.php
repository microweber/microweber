<div>




    <div>

        <div wire:ignore>
            <script>
                window.mw.items_editor_sort = function () {
                    if (!mw.$("#js-sortable-items-holder-{{md5($moduleId)}}").hasClass("ui-sortable")) {
                        mw.$("#js-sortable-items-holder-{{md5($moduleId)}}").sortable({
                            items: '.list-group-item',
                            axis: 'y',
                            handle: '.sortHandle',
                            update: function () {

                                setTimeout(function () {
                                    var obj = {itemIds: []};
                                    var sortableItems = document.querySelectorAll('#js-sortable-items-holder-{{md5($moduleId)}} .js-sortable-item');

                                    sortableItems.forEach(function (item) {
                                        var id = item.getAttribute('sort-key');
                                        obj.itemIds.push(id);
                                    });


                                    Livewire.emit('onReorderListItems', obj);
                                }, 300);


                            },
                            start: function (a, ui) {
                                $(this).height($(this).outerHeight());
                                $(ui.placeholder).height($(ui.item).outerHeight())
                                $(ui.placeholder).width($(ui.item).outerWidth())
                            },
                            scroll: false
                        });
                    }
                }
                $(document).ready(function () {
                    window.mw.items_editor_sort();
                });

                window.addEventListener('livewire:load', function () {
                    window.mw.items_editor_sort();
                });
            </script>

        </div>


        @if(!empty($items))

            <div x-data="{
showEditTab: 'main'
}" x-init="() => {
    window.livewire.on('switchToMainTab', () => {
        showEditTab = 'main'
    })
}">
                <div style="overflow: hidden">


                    <div x-show="showEditTab=='main'" x-transition:enter="tab-pane-slide-left-active">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><?php _e('Teamcard items') ?></h3>
                                        <x-microweber-ui::button class="ms-2" type="button"
                                                                 x-on:click="showEditTab = 'tabs-nav-tab-new-item'">
                                            @lang('Add new')
                                        </x-microweber-ui::button>

                                    </div>

                                    @if (isset($editorSettings['schema']))
                                        <div class="list-group list-group-flush list-group-hoverable" id="js-sortable-items-holder-{{md5($moduleId)}}">
                                            @foreach ($items as $item)
                                                <div class="list-group-item js-sortable-item" sort-key="{{ $item['itemId'] }}" id="item-list-id-{{ $item['itemId'] }}">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="sortHandle">move</div>
                                                        </div>
                                                        <div class="col-auto">
                                                            @if (isset($item['file']))
                                                                <a href="#"><span class="avatar" style="background-image: url('{{ $item['file'] }}')"></span></a>
                                                            @endif
                                                        </div>
                                                        <div class="col text-truncate">
                                                            @foreach ($editorSettings['config']['listColumns'] as $columnKey => $columnLabel)
                                                                @if (isset($item[$columnKey]))
                                                                    <a href="#" class="text-reset d-block">{{ $item[$columnKey] }}</a>
                                                                @endif
                                                            @endforeach

                                                        </div>
                                                        <div class="col-auto">
                                                            <x-microweber-ui::button class="ms-2" type="button" x-on:click="showEditTab = 'tabs-nav-tab-{{ $item['itemId'] }}'">
                                                                @lang('Edit')
                                                            </x-microweber-ui::button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif


                                </div>
                            </div>
                        </div>
                    </div>


                    <div x-show="showEditTab=='tabs-nav-tab-new-item'"
                         x-transition:enter="tab-pane-slide-right-active">
                        <button x-on:click="showEditTab = 'main'" type="button">@lang('Back')</button>
                        <div id="add-new-item-holder">
                            <livewire:microweber-live-edit::module-items-editor-edit-item wire:key="newItem{{$moduleId}}" :optionGroup="$moduleId"
                                                                            :module="$moduleType" :editorSettings="$editorSettings" />

                        </div>
                    </div>

                    @if($items)
                        @foreach($items as $item)
                            <div
                                id="tabs-nav-tab-{{ $item['itemId']  }}"
                                x-show="showEditTab=='tabs-nav-tab-{{ $item['itemId']  }}'"

                                x-transition:enter-end="tab-pane-slide-right-active"
                                x-transition:enter="tab-pane-slide-right-active">
                                <button x-on:click="showEditTab = 'main'">@lang('Back')</button>

                                <div>

                                    <livewire:microweber-live-edit::module-items-editor-edit-item :optionGroup="$moduleId"
                                                                                    :module="$moduleType"
                                                                                    :wire:key="$item['itemId']"
                                                                                    :itemId="$item['itemId']" :editorSettings="$editorSettings" />

                                </div>


                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        @else

            <div class="alert-info">
                No items
                <x-microweber-ui::button class="ms-2" type="button"
                                         x-on:click="showEditTab = 'tabs-nav-tab-new-item'">
                    @lang('Add new')
                </x-microweber-ui::button>
            </div>

        @endif


    </div>


</div>
