<div>

    <?php
    if (!isset($editorSettings['config'])) {
        $editorSettings['config'] = [];
    }


    if (!isset($title)) {
        $title = 'My Module';


        if (isset($editorSettings['config']['title'])) {
            $title = $editorSettings['config']['title'];
        }
        $title = _e($title, true);
    }
    if (!isset($icon)) {
        $icon = 'mdi mdi-account-group';
        if (isset($editorSettings['config']['icon'])) {
            $icon = $editorSettings['config']['icon'];
        }
    }
    if (!isset($addButtonIconSvg)) {
        $addButtonIconSvg = '<svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>';
        if (isset($editorSettings['config']['addButtonIconSvg'])) {
            $addButtonIconSvg = $editorSettings['config']['addButtonIconSvg'];
        }
    }
    if (!isset($editButtonIconSvg)) {
        $editButtonIconSvg = '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M530-481 332-679l43-43 241 241-241 241-43-43 198-198Z"/></svg>';
        if (isset($editorSettings['config']['editButtonIconSvg'])) {
            $editButtonIconSvg = $editorSettings['config']['editButtonIconSvg'];
        }
    }
    if (!isset($deleteButtonIconSvg)) {
        $deleteButtonIconSvg = '<svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>';
        if (isset($editorSettings['config']['deleteButtonIconSvg'])) {
            $deleteButtonIconSvg = $editorSettings['config']['deleteButtonIconSvg'];
        }
    }
    if (!isset($backButtonIconSvg)) {
        $backButtonIconSvg = '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M400-240 160-480l241-241 43 42-169 169h526v60H275l168 168-43 42Z"/></svg>';
        if (isset($editorSettings['config']['backButtonIconSvg'])) {
            $backButtonIconSvg = $editorSettings['config']['backButtonIconSvg'];
        }
    }
    if (!isset($addButtonText)) {
        $addButtonText = 'Add Item';
        if (isset($editorSettings['config']['addButtonText'])) {
            $addButtonText = $editorSettings['config']['addButtonText'];
        }
        $addButtonText = _e($addButtonText, true);
    }
    if (!isset($editButtonText)) {
        $editButtonText = 'Edit Item';
        if (isset($editorSettings['config']['editButtonText'])) {
            $editButtonText = $editorSettings['config']['editButtonText'];
        }
        $editButtonText = _e($editButtonText, true);
    }
    if (!isset($deleteButtonText)) {
        $deleteButtonText = 'Delete Item';
        if (isset($editorSettings['config']['deleteButtonText'])) {
            $deleteButtonText = $editorSettings['config']['deleteButtonText'];
        }
        $deleteButtonText = _e($deleteButtonText, true);
    }
    if (!isset($sortItems)) {
        $sortItems = true;
        if (isset($editorSettings['config']['sortItems'])) {
            $sortItems = $editorSettings['config']['sortItems'];
        }
    }

    if (!isset($additionalButtonsView)) {
        $additionalButtonsView = false;
        if (isset($editorSettings['config']['additionalButtonsView'])) {
            $additionalButtonsView = $editorSettings['config']['additionalButtonsView'];
        }
    }

    ?>



                @if (isset($editorSettings['schema']) and isset($items) and is_array($items) and !empty($items))
                    <div class="list-group list-group-flush list-group-hoverable"
                         id="js-sortable-items-holder-{{md5($moduleId)}}">

                        @foreach ($items as $item)
                            @php
                            $itemId = false;

                            if(isset($item['itemId'])){
                                $itemId = $item['itemId'];
                            }else if(isset($item['id'])){
                                $itemId = $item['id'];
                            }

                            @endphp

                            @if(!$itemId))
                                @continue;
                            @endif
                            <div class="list-group-item js-sortable-item p-2" sort-key="{{ $itemId }}"
                                 id="item-list-id-{{ $itemId }}">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="sortHandle">
                                            <div>
                                                <svg class="mdi-cursor-move cursor-grab ui-sortable-handle"
                                                     fill="currentColor"
                                                     xmlns="http://www.w3.org/2000/svg" height="24"
                                                     viewBox="0 96 960 960" width="24">
                                                    <path
                                                        d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    @if (isset($editorSettings['config']) && isset($editorSettings['config']['listColumns']))
                                        <div class="col text-truncate "
                                             x-on:click="showEditTab = 'tabs-nav-tab-{{ $itemId }}'">
                                            @foreach ($editorSettings['config']['listColumns'] as $columnKey => $columnLabel)

                                                @if (isset($item[$columnKey]))
                                                    <label
                                                        class="text-reset d-block cursor-pointer">{{ $item[$columnKey] }}</label>
                                                @endif
                                            @endforeach

                                        </div>
                                    @endif
                                    <div class="col-auto d-flex align-items-center">


                                        @if(isset($additionalButtonsView) && $additionalButtonsView)
                                            @include($additionalButtonsView)
                                        @endif

                                        <x-microweber-ui::button-action type="button" :tooltip="$deleteButtonText"
                                                                         wire:click="$emit('showConfirmDeleteItemById', '{{ $itemId }}')">
                                                <?php print $deleteButtonIconSvg ?>
                                        </x-microweber-ui::button-action>

                                        <x-microweber-ui::button-action type="button" :tooltip="$editButtonText"
                                                                        wire:click="$emit('editItemById', '{{ $itemId }}')">


                                                <?php print $editButtonIconSvg ?>
                                        </x-microweber-ui::button-action>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif




    <div wire:ignore>
        <script>
            window.mw.items_editor_sort{{md5($moduleId)}} = function () {
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

                        scroll: false
                    });
                }
            }
            $(document).ready(function () {
                window.mw.items_editor_sort{{md5($moduleId)}}();
            });

            window.addEventListener('livewire:load', function () {
                window.mw.items_editor_sort{{md5($moduleId)}}();
            });
        </script>

    </div>
</div>
