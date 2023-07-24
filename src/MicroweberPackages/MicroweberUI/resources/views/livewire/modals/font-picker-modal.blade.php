<div>
    <div class="row">
        <div class="col-md-4 h-auto" style="background:#ececec;">

            <div class="mt-3 ms-2">
                <x-microweber-ui::input wire:model="search" type="text" placeholder="Search fonts..." />
            </div>

            <div class="d-flex flex-column align-items-start gap-2 mt-3 ms-3">
                @foreach($categories as $categoryKey=>$categoryName)

                    @php
                        $buttonClass = '';
                        if($categoryKey == $category) {
                            $buttonClass = 'active';
                        }
                    @endphp

                <x-microweber-ui::button-animation :class="$buttonClass" wire:click="category('{{$categoryKey}}')">
                    {{ $categoryName }}
                </x-microweber-ui::button-animation>
                @endforeach
            </div>

        </div>

        <div class="col-md-8 bg-white">
            <div class="d-flex">
                <div class="p-5 w-full" style="background:red;">
                    <div id="js-modal-livewire-ui-draggable-handle">drag modal</div>
                </div>
                <button id="js-modal-livewire-ui-close">close modal</button>
            </div>
            <div class="pr-5">
                @if(!empty($fonts))
                    @foreach($fonts as $font)
                        <button type="button"
                                style="background:#fff;border:0px;text-align:left;width:100%;margin-top:5px;">
                            <span style="font-size:18px;font-family:'{!! $font['family'] !!}',sans-serif;">
                                 {!! $font['family'] !!}
                            </span>
                        </button>
                    @endforeach
                @endif

                <div>
                    <div class="mt-3">
                        {!! $fonts->links('microweber-ui::livewire.pagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @foreach($fonts as $font)
        loadFontFamily('{{$font['family']}}');
        @endforeach

        window.addEventListener('font-picker-load-fonts', function (e) {
            if (e.detail.fonts) {
                for (var i in e.detail.fonts) {
                    loadFontFamily(e.detail.fonts[i]['family']);
                }
            }
        });

        function loadFontFamily(family) {

            if (!family) {
                return;
            }

            var filename = "//fonts.googleapis.com/css?family=" + encodeURIComponent(family) + "&text=" + encodeURIComponent(family);
            var fileref = document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
            fileref.setAttribute("crossorigin", "anonymous")
            fileref.setAttribute("data-noprefix", "1")


            var fileref2 = document.createElement("link")
            fileref2.setAttribute("rel", "stylesheet")
            fileref2.setAttribute("type", "text/css")
            fileref2.setAttribute("href", filename);
            fileref2.setAttribute("crossorigin", "anonymous")
            fileref2.setAttribute("data-noprefix", "1")

            if(self !== top){
                mw.top().doc.getElementsByTagName("head")[0].appendChild(fileref)
                document.getElementsByTagName("head")[0].appendChild(fileref2)
            } else {
                document.getElementsByTagName("head")[0].appendChild(fileref)
            }
        }
    </script>
</div>
