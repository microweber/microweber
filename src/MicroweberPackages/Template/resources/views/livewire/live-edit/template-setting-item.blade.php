@if(isset($item['settings']))
    <div style="margin-left:15px;" class="mt-2">

        @foreach($item['settings'] as $setting)

            @php
            $hashParent = '';
            if (isset($parent)) {
                $hashParent = json_encode($parent);
            }
            $hash = md5(json_encode($setting) . $hashParent);
            @endphp

            <div x-data="{show{{$hash}}: false}">

            <div>
                @if (isset($setting['title']))
                    <button x-on:click="show{{$hash}} = true" type="button">{{ $setting['title'] }}</button>
                @endif
            </div>

            <div x-show="show{{$hash}} == true" x-transition:enter="tab-pane-slide-left-active" style="margin-left:15px;">

                <div>
                    <button x-on:click="show{{$hash}} = false" class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start" type="button">
                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                        <div class="ms-1 font-weight-bold">
                            Back to {{mb_strtolower($item['title'])}}
                        </div>
                    </button>
                </div>

                @if (isset($setting['title']))
                <div>
                    <h4>{{ $setting['title'] }}</h4>
                </div>
                @endif
                <div>
                    @if(isset($setting['description']))
                        <p>{{$setting['description']}}</p>
                    @endif
                </div>

                @include('template::livewire.live-edit.template-setting-item', [
                    'item' => $setting,
                    'parent' => $item
                ])
            </div>

            </div>
        @endforeach

    </div>
@endif
