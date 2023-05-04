@extends('admin::layouts.base')



@section('content')

    <div>


        <?php
        $moduleTypeForComponent = str_replace('/', '-', $moduleType);
        $moduleTypeForLegacyModule = module_name_decode($moduleType);
        $moduleTypeForComponent = str_replace('_', '-', $moduleTypeForComponent);
        $hasError = false;
        $output = false;

//        try {
//            $output = \Livewire\Livewire::mount('microweber-live-edit::' . $moduleTypeForComponent, [
//                //'id' => $moduleId,
//                'moduleId' => $moduleId,
//                'moduleType' => $moduleType,
//            ])->html();
//
//        } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        } catch (InvalidArgumentException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        } catch (\Exception $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        }
//
//        if ($hasError) {
//            print '<div class="alert alert-danger" role="alert">';
//            print $output;
//            print '</div>';
//        } else {
//            print $output;
//        }


        ?>

        @if(livewire_component_exists('microweber-module-'.$moduleTypeForComponent.'::live-edit'))
                @livewire('microweber-module-'.$moduleTypeForComponent.'::live-edit', [
                    'moduleId' => $moduleId,
                    'moduleType' => $moduleType,
                ])
        @else


            @if(is_module($moduleTypeForLegacyModule))
                <module type="{{ $moduleTypeForLegacyModule}}" id="{{ $moduleId }}"/>
            @endif


        @endif
    </div>







    <script>

        Livewire.on('settingsChanged', $data => {
            if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ($data || {}))
            }
        })
    </script>



    <script>

        var createAutoHeight = function () {
            if (window.thismodal && thismodal.iframe) {
                mw.tools.iframeAutoHeight(thismodal.iframe, 'now');
            }
            else if (mw.top().win.frameElement && mw.top().win.frameElement.contentWindow === window) {
                mw.tools.iframeAutoHeight(mw.top().win.frameElement, 'now');
            } else if (window.top !== window) {
                mw.top().$('iframe').each(function () {
                    try {
                        if (this.contentWindow === window) {
                            mw.tools.iframeAutoHeight(this, 'now');
                        }
                    } catch (e) {
                    }
                })
            }
        };


        if (self !== top) {
            $(window).on('load', function () {

                mw.interval('_settingsAutoHeight', function () {
                    if (document.querySelector('.mw-iframe-auto-height-detector') === null) {
                        createAutoHeight();
                    }
                });

            });

            $(window).on('onbeforeunload', function () {

                mw.removeInterval('_settingsAutoHeight');
            });

        }
    </script>

@endsection
