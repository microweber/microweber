<div>

    <?php


    $title = 'My Module';

    if(!isset($editorSettings['config'])){
        $editorSettings['config'] = [];
    }

    if (isset($editorSettings['config']['title'])) {
        $title = $editorSettings['config']['title'];
    }
    $title = _e($title, true);

    $icon = 'mdi mdi-account-group';
    if (isset($editorSettings['config']['icon'])) {
        $icon = $editorSettings['config']['icon'];
    }

    $addButtonIconSvg = '<svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg>';
    if (isset($editorSettings['config']['addButtonIconSvg'])) {
        $addButtonIconSvg = $editorSettings['config']['addButtonIconSvg'];
    }

    $editButtonIconSvg = '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M530-481 332-679l43-43 241 241-241 241-43-43 198-198Z"/></svg>';
    if (isset($editorSettings['config']['editButtonIconSvg'])) {
        $editButtonIconSvg = $editorSettings['config']['editButtonIconSvg'];
    }

    $deleteButtonIconSvg = '<svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>';
    if (isset($editorSettings['config']['deleteButtonIconSvg'])) {
        $deleteButtonIconSvg = $editorSettings['config']['deleteButtonIconSvg'];
    }

    $backButtonIconSvg = '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M400-240 160-480l241-241 43 42-169 169h526v60H275l168 168-43 42Z"/></svg>';
    if (isset($editorSettings['config']['backButtonIconSvg'])) {
        $backButtonIconSvg = $editorSettings['config']['backButtonIconSvg'];
    }

    $addButtonText = 'Add Item';
    if (isset($editorSettings['config']['addButtonText'])) {
        $addButtonText = $editorSettings['config']['addButtonText'];
    }
    $addButtonText = _e($addButtonText, true);

    $editButtonText = 'Edit Item';
    if (isset($editorSettings['config']['editButtonText'])) {
        $editButtonText = $editorSettings['config']['editButtonText'];
    }
    $editButtonText = _e($editButtonText, true);

    $deleteButtonText = 'Delete Item';
    if (isset($editorSettings['config']['deleteButtonText'])) {
        $deleteButtonText = $editorSettings['config']['deleteButtonText'];
    }
    $deleteButtonText = _e($deleteButtonText, true);

    $sortItems = true;
    if (isset($editorSettings['config']['sortItems'])) {
        $sortItems = $editorSettings['config']['sortItems'];
    }


    ?>


    <div>

        <div x-data="{
showEditTab: 'main'
}" x-init="() => {
    window.livewire.on('switchToMainTab', () => {
        showEditTab = 'main'
    })

     window.livewire.on('editItemById' , (itemId) => {

        showEditTab = 'tabs-nav-tab-' +  itemId
    })


      window.livewire.on('showConfirmDeleteItemById' , (itemId) => {

        Livewire.emit('onShowConfirmDeleteItemById', itemId);
    })
}">


            @if(!empty($items))

                <div style="overflow: hidden">


                    <div x-show="showEditTab=='main'" x-transition:enter="tab-pane-slide-left-active">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card shadow-none">


                                    <div class="mt-2">
                                        <x-microweber-ui::button-animation type="button" class="mt-2"
                                                                           x-on:click="showEditTab = 'tabs-nav-tab-new-item'">
                                                <?php print $addButtonText ?>
                                        </x-microweber-ui::button-animation>
                                    </div>
                                    @include('microweber-live-edit::module-items-editor-list-items')
                                </div>
                            </div>
                        </div>

                    </div>


                    <div x-show="showEditTab=='tabs-nav-tab-new-item'"
                         x-transition:enter="tab-pane-slide-right-active">




                        <div id="add-new-item-holder">
                            <livewire:microweber-live-edit::module-items-editor-edit-item
                                wire:key="newItem2{{$moduleId}}" :moduleId="$moduleId"
                                :moduleType="$moduleType" :editorSettings="$editorSettings"/>

                        </div>
                    </div>

                    @if($items)
                        @foreach($items as $item)

                            @php
                                $itemId = false;

                                if(isset($item['itemId'])){
                                    $itemId = $item['itemId'];
                                }else if(isset($item['id'])){
                                    $itemId = $item['id'];
                                } else {
                                    continue;
                                }
                            @endphp


                            <div
                                id="tabs-nav-tab-{{ $itemId  }}"
                                x-show="showEditTab=='tabs-nav-tab-{{ $itemId  }}'"

                                x-transition:enter-end="tab-pane-slide-right-active"
                                x-transition:enter="tab-pane-slide-right-active">



                                <div>

                                    <livewire:microweber-live-edit::module-items-editor-edit-item :moduleId="$moduleId"
                                                                                                  :moduleType="$moduleType"
                                                                                                  wire:key="item-edit-{{ $itemId }}"
                                                                                                  :itemId="$itemId"
                                                                                                  :editorSettings="$editorSettings"/>

                                </div>


                            </div>
                        @endforeach
                    @endif

                </div>

            @else

                <div class="alert-info">

                    <div id="add-new-item-holder">
                        <livewire:microweber-live-edit::module-items-editor-edit-item wire:key="newItem{{$moduleId}}"
                                                                                      :moduleId="$moduleId"
                                                                                      :moduleType="$moduleType"
                                                                                      :editorSettings="$editorSettings"/>

                    </div>
                </div>

            @endif
        </div>
    </div>

    <div>
        <x-microweber-ui::dialog-modal wire:key="areYouSureDeleteModalOpened" wire:model="areYouSureDeleteModalOpened">

            <x-slot name="title">
                <?php _e('Are you sure?'); ?>
            </x-slot>
            <x-slot name="content">
                <?php _e('Are you sure want to delete this?'); ?>
            </x-slot>

            <x-slot name="footer">
                <x-microweber-ui::button-animation wire:click="$toggle('areYouSureDeleteModalOpened')" wire:loading.attr="disabled">
                    <?php _e('Cancel'); ?>
                </x-microweber-ui::button-animation>
                <x-microweber-ui::button-animation class="text-danger" wire:click="confirmDeleteSelectedItems()" wire:loading.attr="disabled">
                    <?php _e('Delete'); ?>
                </x-microweber-ui::button-animation>
            </x-slot>
        </x-microweber-ui::dialog-modal>

    </div>


</div>
