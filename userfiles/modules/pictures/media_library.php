<?php
	only_admin_access();
?>
<div class="mw-module-admin-wrap">
  <div class="media-search-stock-images-wrapper">
        <style>
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
                margin-bottom:5px;
            }
            #media-results li a {
                font-size:13px;
                color: #156fbb;
                width: 100% !important;
            }
            #media-results li button {
                font-size:13px;
            }
            #media-results li.no-results{
                width: 100%;
                text-align: center;
            }
            #media-results li{
                float: left;
                width: 31.33%;
                padding: 0;
                border: 1px solid #d6d6d6;
                margin: 1%;
                border-radius: 3px;
                box-shadow: 0 2px 2px #00000012;
                text-align: center;
            }
            .mw-browser-list button {
            	width:80px;
            	margin-left:7px;
            	margin-bottom:7px;
            	padding:0px;
            }

            .stock-field{
                width: 100%;
                padding-bottom: 20px;
            }
            .image-item-likes {
                padding: 5px;
			    color: red;
			    position: absolute;
			    right: 4px;
			    background: #fff;
			    border-radius: 0px;
			    top: 4px;
			    border-radius: 6px;
			    padding-bottom: 2px;
			    width: 44px;
		    }
		    .image-item-likes span {
		    	float:left;
		    	font-size:13px;
		    	margin-right:4px;
		    }
		    .image-item-author {
		    	width:100%;
		    	margin-bottom:10px;
		    }
		    .image-item-is-downloaded {
				z-index: 44;
				font-size: 46px;
				color: #45b318;
				position: absolute;
				text-align: center;
				background: #ffffffb3;
				width: 100%;
				height: 173px;
				padding-top: 65px;
			}
        </style>
        <script>
            function selectMediaImage(imageElement, photoId) {


                mw.notification.success('<?php _e('Downloading'); ?>',4000)
            	$(imageElement).parent().find('.image-item-thumbnail').css('opacity', ' 0.6');
            	$(imageElement).parent().find('.image-item-thumbnail').before('<span class="mw-icon-checkmark-circled image-item-is-downloaded"></span>');

            	getMediaImageUrl = mw.settings.api_url + "media_library/download?photo_id=" + photoId;

            	$.get(getMediaImageUrl, function(imageUrl) {
					 mw.url.windowHashParam('select-file', encodeURIComponent(imageUrl));
                     mw.top().notification.success('<?php _e('Image selected'); ?>',3000)

                });



            	mw.reload_module('files/browser');
            }

            function searchMediaLibrary(search, page = 1) {
                search = (search  || '').trim();
                var root = $('#mw-media-library-results .mw-browser-list');
                if(!search){
                    $('.stock-field').removeClass('loading')
                    $('#resbox').hide();
                    return;
                }
                $('#resbox').show();
                $('.stock-field').addClass('loading')
                $.getJSON(mw.settings.api_url + "media_library/search?keyword=" + search + "&page=" + page, function(data) {

				   if (page == 1) {
                   		root.html('<?php _e('Searching'); ?>...');
				   }

                    if (data.success) {
                    	if (page == 1) {
                       		root.empty();
                    	}
                        if(data.photos.length){
                            $.each(data.photos, function(key, val) {

                                var li = $('<li/>');
                                var download = $('<button class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-info" />');
                                var thumbnail = $('<span class="image-item-thumbnail" style="background-image:url('+val.url_thumb+');">');
                                var author = '<div class="image-item-author">Author: <a href="' + val.author_url + '" target="_new">' + val.author + '</a></div>';
                                var likes = $('<span class="image-item-likes" />');

                                download.on('click', function() {
                                    selectMediaImage($(this), val.id);
                                    $(this).hide();
                                });

                                li.append(likes);
                                li.append(thumbnail);
                                li.append(download);
                                li.append(author);

                                download.append('Download');
                                likes.append('<span class="mw-icon-heart"></span>' +val.likes);

                                root.append(li);
                            });

                            var nextPage = parseInt(page) + 1;

                            var loadMoreFunction = "searchMediaLibrary('" + search + "'," + nextPage + ");";

                            $('#mw-media-library-pagination').html('<div style="text-align: center;"><button onClick="'+loadMoreFunction+'" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-info">Load more</button></div>');

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


          <div style="text-align: center">
         <h2 style="margin-top:0px;"><?php _e("Search Unsplash"); ?></h2>
		  <p><b><?php _e("Enter a search term above to begin searching images from "); ?><a href="http://unsplash.com" target="_new" style="color:#009cff;">Unsplash.com</a>. <br /><?php _e("You can use free stock images."); ?></b></p>

			<br />
		</div>

        <div class="mw-field stock-field">
            <input type="text" placeholder="<?php _e("Search for cars, cats and everything you want..."); ?>" onkeyup="mw.on.stopWriting(this,function(){searchMediaLibrary(this.value, 1)})" >
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
