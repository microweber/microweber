@extends('admin::layouts.iframe')



@section('content')

    <div id="settings-container">


        <?php

        $moduleTypeForComponent = str_replace('/admin', '', $moduleType);
        $moduleTypeForComponent = str_replace('/', '-', $moduleTypeForComponent);
        $moduleTypeForLegacyModule = module_name_decode($moduleType);
       // $moduleTypeForLegacyModule = $moduleTypeForLegacyModule.'/admin';

        $moduleTypeForComponent = str_replace('_', '-', $moduleTypeForComponent);
        $hasError = false;
        $output = false;

        $livewireComponentName = 'microweber-module-'.$moduleTypeForComponent.'::settings';






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

        @if(livewire_component_exists($livewireComponentName))
                @livewire($livewireComponentName, [
                    'moduleId' => $moduleId,
                    'moduleType' => $moduleTypeForComponent,
                ])
        @else


            @if(is_module($moduleTypeForLegacyModule))


            <script>
                // saving module settings for legacy modules
                var settingsBindOptionsFields = function () {
                    var settings_container_mod_el = $('#settings-container');
                    mw.options.form(settings_container_mod_el, function () {
                        if (mw.top().notification) {
                            mw.top().notification.success('<?php _ejs('Settings are saved') ?>');
                        }
                         <?php if (isset($params['id'])) : ?>

                            if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {
                                mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '<?php print $params['id']  ?>'} || {}))
                            }

                        <?php endif; ?>

                    });

                    createAutoHeight()
                };
                $(document).ready(function () {
                    setTimeout(function () {
                        settingsBindOptionsFields();
                    }, 777)

                });

            </script>


                    <?php
                    $skipKeys = [
                        'id',
                        'module',
                        'type',
                    ];

                    $additionalParamsString = '';
                    if (isset($params['id'])) {

                        $params = xss_clean($params);
                        foreach ($params as $key => $value) {

                            if (in_array($key, $skipKeys)) {
                                continue;
                            }
                            $additionalParamsString .= '' . $key . '="' . $value . '" ';

                        }
                    }


                    $moduleTag = "<module type='{$moduleTypeForLegacyModule}' id='{$moduleId}' {$additionalParamsString} />";

                    $moduleTagRender = app()->parser->process($moduleTag, $options = false);

                    ?>





                {!! $moduleTagRender !!}

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

        window.createAutoHeight = function () {
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

                var moduleContainerElement = document.getElementById("settings-container")
                var docEl = document.documentElement;

                if (docEl && docEl.addEventListener) {
                    docEl.addEventListener("DOMSubtreeModified", function(evt) {
                        var t = evt.target;

                        window.domModifiedForAutoHeight();

                    }, false);
                } else {
                    document.onpropertychange = function() {

                        window.domModifiedForAutoHeight();

                    };
                }

               mw.interval('_settingsAutoHeight', function () {
                    if (document.querySelector('.mw-iframe-auto-height-detector') === null) {
                      window.createAutoHeight();

                    }
               });

            });
            var domModifiedForAutoHeightIntervalId;
            window.domModifiedForAutoHeight = function () {
                clearTimeout(domModifiedForAutoHeightIntervalId)
                 domModifiedForAutoHeightIntervalId = setTimeout(() => {


                     if (document.querySelector('.mw-iframe-auto-height-detector') === null) {
                         window.createAutoHeight();
                     }
                }, 100);
            }


        }
    </script>

    <script>
        $(document).ready(function() {
            $('body').on('click', function(e) {
                if(typeof window.domModifiedForAutoHeight === 'function') {
                    window.domModifiedForAutoHeight();
                }
            });
            window.createAutoHeight();
        });
    </script>



@endsection
