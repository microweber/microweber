<div>
    <div class="mt-4 p-5 w-full">
        <div>
            <div class="d-flex justify-content-between mb-3">
                <div>
                <label class="form-label font-weight-bold">
                    {{__('Your fields')}}
                </label>
                <small class="d-block mb-2 text-muted">
                    {{__('List of your added custom fields')}}
                </small>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" onclick="Livewire.emit('openMwTopDialogIframe', 'custom-field-add-modal', {{ json_encode(['relId'=>$relId,'relType'=>$relType]) }})" class="d-flex align-items-center btn btn-outline-dark js-add-custom-field">
                        <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"></path></svg><span>Add new field</span>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <div class="mw-responsive-table-wrapper">
                    <table class="table card-table table-vcenter mw-mobile-table" style="min-width: 270px;">
                        <thead>
                        <tr>
                            <th style="width:5px">
                                <small>#</small>
                            </th>
                            <th>
                                <small>Type</small>
                            </th>
                            <th>
                                <small>Name</small>
                            </th>
                            <th>
                                <small>Value</small>
                            </th>
                            <th class="text-center">
                                <small>Settings</small>
                            </th>
                            <th class="text-center">
                                <small>Delete</small>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="js-sortable-items-holder-{{$this->id}}">

                        @if($customFields->count() == 0)
                            <tr>
                                <td colspan="6">
                                    {{__('No custom fields added')}}
                                </td>
                            </tr>
                        @endif

                        @foreach($customFields as $customField)
                        <tr class="js-sortable-item" sort-key="{{ $customField->id }}">
                            <td>
                                <div class="js-sort-handle">
                                    <div>
                                        <svg class="mdi-cursor-move cursor-grab ui-sortable-handle"
                                             fill="#8e8e8e"
                                             xmlns="http://www.w3.org/2000/svg" height="24"
                                             viewBox="0 96 960 960" width="24">
                                            <path
                                                d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="custom-field-icon">
                                <div>
                                    <span class="mw-custom-field-icon-{{$customField->type}}"></span>
                                </div>
                            </td>

                            <td>
                                <span class="mw-custom-fields-list-preview">
                                    <span class="text-capitalize d-inline-block py-1">
                                        <small class="px-1 py-1">
                                            {{$customField->name}}
                                        </small>
                                    </span>
                                </span>
                            </td>


                            <td>
                                <div>
                                    {{$customField->fieldValueText()}}
                                </div>
                            </td>

                            <td class="text-center">
                                <button class="mw-liveedit-button-actions-component" onclick="Livewire.emit('openMwTopDialogIframe', 'custom-field-edit-modal', {{ json_encode(['customFieldId'=>$customField->id]) }})" type="button">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><path d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z"></path>
                                    </svg>
                                </button>
                            </td>

                            <td class="text-center">
                                @php
                                    $deleteModalData = [
                                        'body' => 'Are you sure you want to delete this custom field?',
                                        'title' => 'Delete this field',
                                        'button_text'=> 'Delete',
                                        'action' => 'executeCustomFieldDelete',
                                        'data'=> $customField->id
                                    ];
                                @endphp
                                <button onclick="Livewire.emit('openModal', 'admin-confirm-modal', {{ json_encode($deleteModalData) }})" type="button" class="mw-liveedit-button-actions-component" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete">
                                    <i class="mdi mdi-close mdi-20px"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div wire:ignore>
            <script>
                window.mw.items_editor_sort = function () {

                    if (!mw.$("#js-sortable-items-holder-{{$this->id}}").hasClass("ui-sortable")) {
                        mw.$("#js-sortable-items-holder-{{$this->id}}").sortable({
                            items: '.js-sortable-item',
                            axis: 'y',
                            handle: '.js-sort-handle',
                            update: function () {
                                setTimeout(function () {
                                    var obj = {itemIds: []};
                                    var sortableItems = document.querySelectorAll('#js-sortable-items-holder-{{$this->id}} .js-sortable-item');
                                    sortableItems.forEach(function (item) {
                                        var id = item.getAttribute('sort-key');
                                        obj.itemIds.push(id);
                                    });
                                    Livewire.emit('onReorderCustomFieldsList', obj);
                                }, 300);
                            },
                            scroll: false
                        });
                    }
                }

                $(document).ready(function () {
                    window.mw.items_editor_sort();
                });

                mw.top().app.editor.on('customFieldUpdated', function (a, b) {
                    Livewire.emit('customFieldUpdated');
                }); 

                window.addEventListener('livewire:load', function () {
                    window.mw.items_editor_sort();
                });
            </script>

        </div>


    </div>
</div>
