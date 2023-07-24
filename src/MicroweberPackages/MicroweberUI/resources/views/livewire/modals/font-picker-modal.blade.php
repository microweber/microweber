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
                <div class="w-full">
<!--                    Don't change the high of handle. Cause you will break the draggable modal.-->
                    <div id="js-modal-livewire-ui-draggable-handle" class="w-full h-6" style="cursor:move"></div>
                </div>
                <div id="js-modal-livewire-ui-close" class="cursor-pointer" style="padding-top:5px;padding-right:5px;font-size:28px">
                    <i class="mdi mdi-close"></i>
                </div>
            </div>
            <div class="pr-5">
                @if(!empty($fonts))
                    @foreach($fonts as $font)
                        <div class="d-flex justify-content-between">
                           <div>
                               <button type="button"
                                       style="background:#fff;border:0px;text-align:left;width:100%;margin-top:5px;">
                                <span style="font-size:18px;font-family:'{!! $font['family'] !!}',sans-serif;">
                                     {!! $font['family'] !!}
                                </span>
                               </button>
                           </div>
                            <div class="pr-3" wire:click="favorite('{{$font['family']}}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17.562 21.56a1 1 0 0 1-.465-.116L12 18.764l-5.097 2.68a1 1 0 0 1-1.45-1.053l.973-5.676l-4.124-4.02a1 1 0 0 1 .554-1.705l5.699-.828l2.549-5.164a1.04 1.04 0 0 1 1.793 0l2.548 5.164l5.699.828a1 1 0 0 1 .554 1.705l-4.124 4.02l.974 5.676a1 1 0 0 1-.985 1.169Z"/></svg>
                            </div>
                        </div>
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
