@if(isset($item['styles']))
<div>

    @foreach($item['styles'] as $styleSetting)

    <div style="border:1px solid #000;margin-top:25px;">

        <div>
            @if (isset($styleSetting['title']))
                <a class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                    {{ $styleSetting['title'] }}
                </a>
            @endif
        </div>

        <div class="mt-3">
            <div>
                @if(isset($styleSetting['settings']))
                    @include('template::livewire.live-edit.template-setting-item', ['item' => $styleSetting])
                @endif
            </div>
            <div>
                @if(isset($styleSetting['styles']))
                    @include('template::livewire.live-edit.template-style-item', ['item' => $styleSetting])
                @endif
            </div>
        </div>

    </div>
    @endforeach

</div>
@endif
