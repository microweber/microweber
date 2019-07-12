<?php
	only_admin_access();
?>
<div class="mw-module-admin-wrap">
  <div class="media-search-stock-images-wrapper">
        <style>
            .mw-browser-list-png {
                cursor:pointer;
            }
            .image-item{
                display: inline-block;
                width: 65px;
                height: 65px;
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
            }
            #media-results{
                overflow: hidden;
            }
            #media-results li a .image-item {
                width: 100%;
                height: 115px;
            }
            #media-results li a{
                width: 100%;
            }
            #media-results li.no-results{
                width: 100%;
                text-align: center;
            }
            #media-results li{
                float: left;
                width: 48%;
                padding: 0;
                border: 1px solid #d6d6d6;
                margin: 1%;
                border-radius: 3px;
                box-shadow: 0 2px 2px #00000012;
            }
            .stock-field{
                width: 100%;
                padding-bottom: 20px;
            }
            .
        </style>
        <script>
            function selectMediaImage(image) {
                mw.url.windowHashParam('select-file', encodeURIComponent(image));
            }

            function searchMediaLibrary(search) {
                search = (search  || '').trim();
                var root = $('#mw-media-library-results .mw-browser-list');
                if(!search){
                    $('.stock-field').removeClass('loading')
                    $('#resbox').hide();
                    return;
                }
                $('#resbox').show();
                $('.stock-field').addClass('loading')
                $.getJSON(mw.settings.api_url + "media_library/search?keyword=" + search, function(data) {

                    root.html('<?php echo _e('Searching'); ?>...');

                    if (data.success) {
                        root.empty();
                        if(data.photos.length){
                            $.each(data.photos, function(key, val) {
                                var li = $('<li/>');
                                var a = $('<a class="mw-browser-list-file mw-browser-list-png" />');
                                var img = $('<span class="image-item" style="background-image:url('+val.url_thumb+');">');
                                img.on('click', function(){
                                    selectMediaImage(val.url_regular);
                                });
                                li.append(a);
                                a.append(img);
                                root.append(li);
                            });
                        } else {
                            $('.media-results').html('<li class="no-results">Nothing found for <b>'+search+'</b></li>')
                        }

                    } else {
                        $('#mw-media-library-results').find('.mw-browser-list').html(data.error);
                    }

                }).always(function () {
                    $('.stock-field').removeClass('loading')
                });
            }
            searchMediaLibrary('');
        </script>
        <h3>Search for free stock photos</h3>
        <div class="mw-field stock-field">
            <input type="text" placeholder="Search" onkeyup="mw.on.stopWriting(this,function(){searchMediaLibrary(this.value)})" >
        </div>

        <div style="display: none;" id="resbox">
            <div id="mw-media-library-results">
                <ul class="mw-browser-list" id="media-results">
                </ul>
            </div>
        </div>

    </div>
</div>
