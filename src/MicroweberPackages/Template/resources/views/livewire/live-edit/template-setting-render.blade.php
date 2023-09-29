<div class="mt-3">

    @if(isset($setting['title']))
        <div>
            @if(isset($setting['settings']))
                <a href="#" x-on:click="showStyleSettings = '{{$setting['url']}}'"
                   class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                    {{$setting['title']}}
                </a>
            @endif
        </div>
    @endif

    <div>
        @if(isset($setting['fieldType']))

            @if ($setting['fieldType'] == 'clearAll')
                <div class="mt-2">
                   <div>
                       <b>{{$setting['title']}}</b>
                   </div>
                    <div>
                        <p>{{$setting['description']}}</p>
                    </div>
                    <button
                        x-on:click="(e) => {
                            @foreach($setting['fieldSettings']['properties'] as $property)
                                 mw.top().app.cssEditor.removeSheetRuleProperty('{{end($setting['selectors'])}}', '{{$property}}');
                            @endforeach 
                            }"
                        class="btn btn-outline-dark">
                        <i class="mdi mdi-delete-sweep" style="font-size:22px"></i> {{$setting['title']}}
                    </button>
                </div>
            @endif

            @if ($setting['fieldType'] == 'colorPalette')

               @if(isset($setting['fieldSettings']['colors']))
                    @foreach($setting['fieldSettings']['colors'] as $colorPallete)
                        <div class="mt-2">
                            <div class="d-flex gap-2"

                                 x-on:click="(e) => {
                                    @foreach($colorPallete['properties'] as $property=>$propertyValue)
                                    mw.top().app.cssEditor.setPropertyForSelector('{{end($setting['selectors'])}}', '{{$property}}', '{{$propertyValue}}');
                                    @endforeach
                                }"

                            >
                                @foreach($colorPallete['mainColors'] as $mainColors)
                                    <div style="border-radius:6px;width:100%;height:50px;background:{{$mainColors}}"></div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

            @endif

            @if ($setting['fieldType'] == 'colorPicker')

                <x-microweber-ui::color-picker x-on:update="(e) => {
                    mw.top().app.cssEditor.setPropertyForSelector('{{end($setting['selectors'])}}', '{{$setting['fieldSettings']['property']}}', event.target.value);
                }"
               label="{{$setting['title']}}" />

            @endif

            @if ($setting['fieldType'] == 'rangeSlider')
                <x-microweber-ui::range-slider x-on:update="(e) => {
                    mw.top().app.cssEditor.setPropertyForSelector('{{end($setting['selectors'])}}', '{{$setting['fieldSettings']['property']}}', event.target.value + 'px');
                }"
               label="{{$setting['title']}}" min="0" max="100" labelUnit="" />
            @endif

            @if ($setting['fieldType'] == 'styleEditor')
                <a href="#"
                   x-on:click="() => {
                        showStyleSettings = 'styleEditor';
                        styleEditorData = {{json_encode($setting)}};
                    }"

                   class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                    {{$setting['title']}}
                </a>
            @endif

            @if ($setting['fieldType'] == 'infoBox')
                <b>{{$setting['title']}}</b>
                <p>{{$setting['description']}}</p>
            @endif

            @if ($setting['fieldType'] == 'fontFamily')
            <x-microweber-ui::font-picker x-on:change="(e) => {
                    mw.top().app.cssEditor.setPropertyForSelector(':root', '--fontFamily', event.target.value);
                }" />
            @endif

            @if ($setting['fieldType'] == 'fontSize')
<!--                <x-microweber-ui::range-slider x-on:update="(e) => {
                    mw.top().app.cssEditor.setPropertyForSelector('body', 'fontSize', event.target.value + 'px);
                }"
               label="{{$setting['title']}}" min="8" max="120" labelUnit="px" />-->
            @endif
        @endif
    </div>
</div>
<script>
    import BubbleNav from "../../../../../LiveEdit/resources/js/ui/components/LeftSidebar/BubbleNav";
    export default {
        components: {BubbleNav}
    }
</script>
