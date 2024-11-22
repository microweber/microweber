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
    $rand = md5($moduleId . rand() . time())
    ?>


    @if (isset($editorSettings['schema']) and isset($items) and is_array($items) and !empty($items))
        <div class="list-group list-group-flush list-group-hoverable"
             id="js-sortable-items-holder-{{ $rand }}" wire:key="js-sortable-items-holder-{{ $rand }}">

            @foreach ($items as $item)
                @php
                    $itemId = false;

                    if(isset($item['itemId'])){
                        $itemId = $item['itemId'];
                    }else if(isset($item['id'])){
                        $itemId = $item['id'];
                    }

                @endphp

                @if(!$itemId)
                    @continue
                @endif

                <div class="list-group-item js-sortable-item p-2" sort-key="{{ $itemId }}"
                     wire:key="item-list-edit-{{ $itemId  }}"
                     wire:mouseover="$dispatch('mouseoverItemId', '{{ $itemId }}')"
                     wire:mouseout="$dispatch('mouseoutItemId', '{{ $itemId }}')"
                     id="item-list-id-{{ $itemId }}">
                    <div class="row align-items-center" id="item-list-row-{{ $itemId }}">
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
                            <div class="col text-truncate"
                                 @click="$dispatch('editItemById', '{{ $itemId }}')">
                                <div class="d-flex align-items-center gap-2">
                                    @foreach ($editorSettings['config']['listColumns'] as $columnKey => $columnLabel)
                                        @if (isset($item[$columnKey]))

                                            @if (\Modules\Media\Repositories\MediaManager::guessMediaTypeFromUrl($item[$columnKey]) == 'picture')
                                                <img src="{{ thumbnail($item[$columnKey], 100, 100, true) }}"
                                                     style="border-radius:3px;width: 40px;"
                                                     alt="{{ $item[$columnKey] }}"/>
                                            @elseif($columnKey == 'icon')

                                                {!! $item[$columnKey] !!}

                                            @else
                                                <label class="d-block cursor-pointer">
                                                    {{ str_limit($item[$columnKey], 22) }}
                                                </label>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        @endif


                        <div class="col-auto d-flex align-items-center">


                            <x-microweber-ui::button-action wire:key="item-list-delete-btn-{{ $itemId  }}" type="button"
                                                            :tooltip="$deleteButtonText"
                                                            @click="$dispatch('onShowConfirmDeleteItemById', {itemId: '{{ $itemId  }}'})">
                                    <?php print $deleteButtonIconSvg ?>
                            </x-microweber-ui::button-action>

                            <x-microweber-ui::button-action wire:key="item-list-edit-btn-{{ $itemId  }}" type="button"
                                                            @click="$dispatch('editItemById', '{{ $itemId }}')">
                                    <?php print $editButtonIconSvg ?>
                            </x-microweber-ui::button-action>

                        </div>

                        @if(isset($additionalButtonsView) && $additionalButtonsView)
                            <div class="mt-2">
                                @include($additionalButtonsView)
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif


    <div id="sort-script{{ $rand }}">


        <script>
            window.mw.items_editor_sort{{ $rand }} = function () {

                var checkIfExist = document.getElementById('js-sortable-items-holder-{{ $rand }}');
                if (!checkIfExist) {

                    return;
                }
                if (mw.$("#js-sortable-items-holder-{{ $rand }}").hasClass("ui-sortable")) {
                    mw.$("#js-sortable-items-holder-{{ $rand  }}").sortable('destroy');
                    mw.$("#js-sortable-items-holder-{{ $rand }}").removeClass("ui-sortable");
                }

                //if (!mw.$("#js-sortable-items-holder-{{ $rand }}").hasClass("ui-sortable")) {
                mw.$("#js-sortable-items-holder-{{ $rand  }}").sortable({
                    items: '.list-group-item',
                    axis: 'y',
                    handle: '.sortHandle',
                    stop: function () {
                        var obj = {itemIds: []};
                        var sortableItems = document.querySelectorAll('#js-sortable-items-holder-{{$rand}} .js-sortable-item');

                        sortableItems.forEach(function (item) {
                            var id = item.getAttribute('sort-key');
                            obj.itemIds.push(id);
                        });
                        window.mw.notification.success('Reordering items...');

                        window.Livewire.dispatch('onReorderListItems', {'order': obj});

                        // setTimeout(function () {
                        //
                        //     window.location.reload();
                        // }, 500);


                    },

                    scroll: false
                });
                //   }
            }
            $(document).ready(function () {
                window.mw.items_editor_sort{{$rand}}();
            });

            window.addEventListener('livewire:init', function () {

                //window.mw.items_editor_sort{{$rand}}();

                Livewire.hook('component.init', ({component, cleanup}) => {
                })

                Livewire.hook('request', ({uri, options, payload, respond, succeed, fail}) => {

                    respond(({status, response}) => {
                        setTimeout(function () {
                            window.mw.items_editor_sort{{$rand}}();
                        }, 500);

                    })


                })


                Livewire.hook('morph.updated', ({el, component}) => {

                })


            });


        </script>

    </div>
</div>
