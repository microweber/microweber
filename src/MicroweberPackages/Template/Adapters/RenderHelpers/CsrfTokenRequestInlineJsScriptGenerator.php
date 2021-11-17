<?php

namespace MicroweberPackages\Template\Adapters\RenderHelpers;

class CsrfTokenRequestInlineJsScriptGenerator
{
    public function generate()
    {
        return <<<HTML
        $( document ).ready(function() {

            if (!document.querySelectorAll('meta[name="csrf-token"]').length == 0) {
                $("head").append( "<meta name=csrf-token />");
            }

            var _csrf_from_local_storage = null;
            if(typeof(mw.cookie) != 'undefined'){
                csrf_from_local_storage_data = mw.cookie.get("csrf-token-data")
                if(csrf_from_local_storage_data){
                    csrf_from_local_storage_data = JSON.parse(csrf_from_local_storage_data);

                    if (csrf_from_local_storage_data && csrf_from_local_storage_data.value && (new Date()).getTime() < csrf_from_local_storage_data.expiry) {
                        _csrf_from_local_storage = csrf_from_local_storage_data.value
                }
                }

            }

            if(_csrf_from_local_storage){
                $('meta[name="csrf-token"]').attr('content',_csrf_from_local_storage)
                     $.ajaxSetup({
                        headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                return;
            }

            setTimeout(function () {
                $.get( route('csrf'), function( data ) {
                    $('meta[name="csrf-token"]').attr('content',data.token)
                    if(typeof(mw.cookie) != 'undefined' ){

                        var csrf_from_local_storage_ttl = 900000; // 15 minutes
                        var item = {
                            value: data.token,
                            expiry: (new Date()).getTime() + csrf_from_local_storage_ttl,
                        }
                        mw.cookie.set("csrf-token-data", JSON.stringify(item))

                     }

                     $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
              })
                }, 1337);
        });
HTML;
    }
}
