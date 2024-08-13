@php
    use Filament\Support\Enums\MaxWidth;

    $navigation = filament()->getNavigation();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">

    @if (method_exists($livewire, 'showTopBar') && $livewire->showTopBar())

        @if (filament()->hasTopbar())
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_BEFORE, scopes: $livewire->getRenderHookScopes()) }}

            <x-filament-panels::topbar :navigation="$navigation"/>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_AFTER, scopes: $livewire->getRenderHookScopes()) }}
        @endif

    @endif

    @php
        $iframeClass = '';
        $isIframe = false;

        if (request()->header('Sec-Fetch-Dest') === 'iframe') {
            $iframeClass = 'mw-live-edit-module-settings-iframe';
            $isIframe = true;
           }


    @endphp


    <main class="mw-live-edit-page-wrapper {{ $iframeClass }}" id="mw-live-edit-page-wrapper">
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_START, scopes: $livewire->getRenderHookScopes()) }}

        {{ $slot }}

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_END, scopes: $livewire->getRenderHookScopes()) }}


        @if ($isIframe)

            <script>
                if (self !== top) {

                    document.addEventListener("DOMContentLoaded", () => {
                        if (typeof Livewire === 'undefined') {
                            return;
                        }

                        Livewire.hook('message.sent', (message, component) => {
                            $('body').addClass('mw-livewire-loading')
                        })

                        Livewire.hook('message.processed', (message, component) => {
                            $('body').removeClass('mw-livewire-loading')
                        })
                        Livewire.hook('message.failed', (message, component) => {
                            $('body').removeClass('mw-livewire-loading')
                        })
                        Livewire.hook('element.updated', (message, component) => {
                            $('body').removeClass('mw-livewire-loading')
                        })

                        // remove the class after 5 seconds, just in case
                        // if there is an error and the class is not removed
                        setTimeout(function () {
                            $('body').removeClass('mw-livewire-loading')
                        }, 5000);
                    });
                     if(self.frameElement){
                        mw.tools.iframeAutoHeight(self.frameElement);
                    }

                }
            </script>

        @endif

    </main>


    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}

</x-filament-panels::layout.base>
