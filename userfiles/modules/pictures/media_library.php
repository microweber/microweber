<?php
must_have_access();
?>
<div class="mw-module-admin-wrap">
    <div class="media-search-stock-images-wrapper">
        <style>

            html,body{
                height: auto;
            }
            .image-item-thumbnail {
                display: inline-block;
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
                width: 100% !important;
                height: 115px !important;
            }

            #media-results {
                overflow: hidden;
            }

            #media-results li .image-item-thumbnail {
                width: 100% !important;
                height: 171px !important;
                margin-bottom: 5px;
            }

            #media-results li a {
                font-size: 13px;
                color: #156fbb;
                width: 100% !important;
            }

            #media-results li.no-results {
                width: 100%;
                text-align: center;
            }

            #media-results li .mw-spinner{
                position: absolute;
                top: 50%;
                left: 50%;
                z-index: 3;
                transform: translate(-50%, -50%);
            }
            #media-results li {
                float: left;
                position: relative;
                width: 31.33%;
                padding: 0;
                border: 1px solid #d6d6d6;
                margin: 1%;
                border-radius: 3px;
                box-shadow: 0 2px 2px #00000012;
                text-align: center;
                list-style: none;
            }

            .stock-field {
                width: 100%;
                padding-bottom: 20px;
            }

            .image-item-likes {
                padding: 5px;
                color: red;
                position: absolute;
                right: 4px;
                background: #fff;
                top: 4px;
                border-radius: 6px;
                padding-bottom: 2px;
                width: 44px;
            }

            .image-item-likes span {
                float: left;
                font-size: 13px;
                margin-right: 4px;
            }

            .image-item-author {
                width: 100%;
                margin-bottom: 10px;
            }

            .image-item-is-downloaded {
                z-index: 2;
                font-size: 46px;
                color: #45b318;
                position: absolute;
                text-align: center;
                background: #ffffffb3;
                width: 100%;
                height: 173px;
                padding-top: 65px;
            }
            .stock-field{
                position: relative;
            }
            .stock-field .mw-spinner{
                position: absolute;
                top: 10px;
                right: 14px;
                z-index: 1;
                pointer-events: none;
            }
        </style>
        <script>
            function selectMediaImage(imageElement, photoId) {

                mw.spinner(({element: imageElement.parent(), size: 30})).show();


                getMediaImageUrl = mw.settings.api_url + "media_library/download?photo_id=" + photoId;

                $.get(getMediaImageUrl, function (imageUrl) {
                    mw.url.windowHashParam('select-file', encodeURIComponent(imageUrl));
                    $(imageElement).parent().find('.image-item-thumbnail').css('opacity', ' 0.6');
                    $(imageElement).parent().find('.image-item-thumbnail').before('<span class="mw-icon-checkmark-circled image-item-is-downloaded"></span>');
                    mw.top().notification.success('<?php _e('Image selected'); ?>', 3000);
                    mw.spinner(({element: imageElement.parent(), size: 30})).remove();

                });


                mw.reload_module('files/browser');
            }

            function doSearch(node) {
                var val = node.value.trim();
                if(!val) {
                    mw.spinner(({element: node.parentNode})).hide();
                }
                mw.spinner(({element: node.parentNode, size: 20})).show();
                mw.on.stopWriting(node, function(){
                    searchMediaLibrary(node.value, 1, function () {
                        mw.spinner(({element: node.parentNode})).hide();
                        setTimeout(function (){
                            if(window.thismodal) {
                                thismodal.center()
                            }
                        })
                    })
                })
             }
             var searchMediaLibraryXHR = null;
            function searchMediaLibrary(search, page, callback) {
                page = page || 1
                search = (search || '').trim();
                var root = $('#mw-media-library-results .mw-browser-list');
                if (!search) {
                    $('.stock-field').removeClass('loading')
                    $('#resbox').hide();
                    return;
                }
                $('#resbox').show();
                if(searchMediaLibraryXHR) {
                    searchMediaLibraryXHR.abort();
                }
                searchMediaLibraryXHR = $.getJSON(mw.settings.api_url + "media_library/search?keyword=" + search + "&page=" + page, function (data) {

                    if (page == 1) {
                        root.html('<?php _e('Searching'); ?>...');
                    }

                    if (data.success) {
                        if (page == 1) {
                            root.empty();
                        }
                        if (data.photos.length) {
                            $.each(data.photos, function (key, val) {

                                var li = $('<li/>');
                                var download = $('<button class="btn btn-outline-primary btn-sm py-1 px-1" />');
                                var thumbnail = $('<span class="image-item-thumbnail" style="background-image:url(' + val.url_thumb + ');">');
                                var author = '<div class="image-item-author">Author: <br><a href="' + val.author_url + '" target="_new">' + val.author + '</a></div>';
                                var likes = $('<span class="image-item-likes" />');

                                download.on('click', function () {
                                    selectMediaImage($(this), val.id);
                                    $(this).hide();
                                });

                                li.append(likes);
                                li.append(thumbnail);
                                li.append(download);
                                li.append(author);

                                download.append('<?php _e('Download');  ?>');
                                likes.append('<span class="mw-icon-heart"></span>' + val.likes);

                                root.append(li);

                            });

                            var nextPage = parseInt(page) + 1;

                            var loadMoreFunction = "searchMediaLibrary('" + search + "'," + nextPage + ");";

                            $('#mw-media-library-pagination').html('<div style="text-align: center;"><button onClick="' + loadMoreFunction + '" class="btn btn-primary">Load more</button></div>');

                        } else {
                            $('.media-results').html('<li class="no-results">Nothing found for <b>' + search + '</b></li>')
                        }

                    } else {
                        $('#mw-media-library-results').find('.mw-browser-list').html(data.error);
                    }
                    callback.call(data)
                }).always(function () {

                });
            }
            searchMediaLibrary('');
        </script>


        <div class="text-center text-center mt-3 mx-auto" style="max-width: 450px;">
            <h4 class="mb-3"><?php _e("Search free images from Unsplash"); ?></h4>
            <small class="text-muted d-block"><?php _e("Enter a search term above to begin searching images from "); ?><a href="http://unsplash.com" target="_new" style="color:#009cff;">Unsplash.com</a>. <br/><?php _e("You can use free stock images."); ?></small>

            <div class="form-group stock-field mt-4">
                <input type="text" class="form-control" placeholder="<?php _e("Example: cars, business or landscape..."); ?>" oninput="doSearch(this)">
            </div>
        </div>

        <div style="display: none;" id="resbox">
            <div id="mw-media-library-results">
                <ul class="mw-browser-list" id="media-results">
                </ul>
            </div>
            <div id="mw-media-library-pagination"></div>
        </div>

    </div>
</div>
