<div>

    <script wire:ignore>
        window.loadFontFamily = function (family) {

            if (!family) {
                return;
            }
            var id = 'font-' + family.replace(/[^a-zA-Z0-9]/g, '');

            var filename = "//fonts.googleapis.com/css?family=" + encodeURIComponent(family) + "&text=" + encodeURIComponent(family);
            var fileref = document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
            fileref.setAttribute("referrerpolicy", "no-referrer")
            fileref.setAttribute("crossorigin", "anonymous")
            fileref.setAttribute("data-noprefix", "1")
            fileref.setAttribute("id", id)


            var fileref2 = document.createElement("link")
            fileref2.setAttribute("rel", "stylesheet")
            fileref2.setAttribute("type", "text/css")
            fileref2.setAttribute("href", filename);
            fileref2.setAttribute("referrerpolicy", "no-referrer")
            fileref2.setAttribute("crossorigin", "anonymous")
            fileref2.setAttribute("data-noprefix", "1")
            fileref2.setAttribute("data-noprefix", "1")
            fileref2.setAttribute("id", id)


            if(self !== top){
                //check if the font is already loaded
                if (mw.top().doc.getElementById(id)) {
                    return;
                }
                mw.top().doc.getElementsByTagName("head")[0].appendChild(fileref)
                document.getElementsByTagName("head")[0].appendChild(fileref2)
            } else {
                //check if the font is already loaded
                if (document.getElementById(id)) {
                    return;
                }
                document.getElementsByTagName("head")[0].appendChild(fileref)
            }
        }
    </script>

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

            <script>
                document.addEventListener('fontAddedToFavorites', function (e) {
                    if (mw.top().app.fontManager) {
                        mw.top().app.fontManager.selectFont(e.detail.fontFamily);
                    }
                });
            </script>

            <div class="pr-5">

                @if($fonts->count() > 0)
                    @foreach($fonts as $font)
                        @php
                            $fontId = md5($font['family'].$font['category']);
                        @endphp
                        <div wire:key="font-id-{{$fontId}}" x-data="{favorite: @if (isset($font['favorite']) && $font['favorite']) true @else false @endif }" class="d-flex justify-content-between px-3">

                            <script>
                                loadFontFamily('{{$font['family']}}');
                            </script>

                                <div>
                               <button type="button" x-on:click="favorite = true" wire:click="favorite('{{$font['family']}}')"
                                       style="background:#fff;border:0px;text-align:left;width:100%;margin-top:5px;">
                                <span style="font-size:18px;font-family:'{!! $font['family'] !!}',sans-serif;">
                                     {!! $font['family'] !!}
                                </span>
                               </button>
                           </div>
                            <div>
                                <div x-show="favorite" style="color:#ffd400" class="pr-3" x-on:click="favorite = false" wire:click="removeFavorite('{{$font['family']}}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M22 10.1c.1-.5-.3-1.1-.8-1.1l-5.7-.8L12.9 3c-.1-.2-.2-.3-.4-.4c-.5-.3-1.1-.1-1.4.4L8.6 8.2L2.9 9c-.3 0-.5.1-.6.3c-.4.4-.4 1 0 1.4l4.1 4l-1 5.7c0 .2 0 .4.1.6c.3.5.9.7 1.4.4l5.1-2.7l5.1 2.7c.1.1.3.1.5.1h.2c.5-.1.9-.6.8-1.2l-1-5.7l4.1-4c.2-.1.3-.3.3-.5z"/></svg>
                                </div>
                                <div x-show="!favorite" class="pr-3" x-on:click="favorite = true" wire:click="favorite('{{$font['family']}}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m354-247 126-76 126 77-33-144 111-96-146-13-58-136-58 135-146 13 111 97-33 143ZM233-80l65-281L80-550l288-25 112-265 112 265 288 25-218 189 65 281-247-149L233-80Zm247-350Z"/></svg>                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div>
                        No fonts in this {{ $category }}
                    </div>
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

        document.addEventListener('font-picker-load-fonts', function (e) {

            if (e.detail.fonts) {
                for (var i in e.detail.fonts) {
                    loadFontFamily(e.detail.fonts[i]['family']);
                }
            }
        });




    </script>
</div>
