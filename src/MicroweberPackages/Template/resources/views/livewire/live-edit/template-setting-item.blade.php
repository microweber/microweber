@if(isset($item['settings']))
    <div style="margin-left:15px;" class="mt-2">

        @foreach($item['settings'] as $setting)
            <div>
                @if (isset($setting['title']))
                    <b>{{ $setting['title'] }}</b>
                @endif
                @if(isset($setting['description']))
                    <p>{{$setting['description']}}</p>
                @endif
            </div>

            <div style="margin-left:15px;">
                @include('template::livewire.live-edit.template-setting-item', ['item' => $setting])
            </div>

        @endforeach

    </div>
@endif
