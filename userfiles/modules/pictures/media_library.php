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
                height: 115px !important;
                cursor:pointer;
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
                width: 23%;
                padding: 0;
                border: 1px solid #d6d6d6;
                margin: 1%;
                border-radius: 3px;
                box-shadow: 0 2px 2px #00000012;
            }
            .mw-browser-list button {
            	width:155px;
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
			    right: 0px;
			    background: #c8c8c8e8;
			    border-radius: 0px;
		    }
		    .image-item-author {
		    	width:100%;
		    	margin-bottom:7px;
		    }
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
                                var download = $('<button class="mw-ui-btn mw-ui-btn-medium" />');
                                var thumbnail = $('<span class="image-item-thumbnail" style="background-image:url('+val.url_thumb+');">');
                                var author = $('<a href="' + val.author_url + '" class="image-item-author" target="_new" />');
                                var likes = $('<span class="image-item-likes" />');
                                
                                thumbnail.on('click', function() {
                                    selectMediaImage(mw.settings.api_url + "media_library/download?photo_id=" + val.id);
                                });

                                download.on('click', function() {
                                    selectMediaImage(mw.settings.api_url + "media_library/download?photo_id=" + val.id);
                                }); 

                                li.append(likes);
                                li.append(thumbnail);
                                li.append(download);
                                li.append(author);
                                
                                download.append('Download');
                                author.append('Author: ' + val.author);
                                likes.append('<span class="mw-icon-heart"></span><br />' +val.likes); 
								
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
       
               
         <center>
         <h2 style="margin-top:0px;">Search Unsplash</h2>  
		  <p><b>Enter a search term above to begin searching images from <a href="http://unsplash.com" target="_new" style="color:#009cff;">Unsplash.com</a>. <br />You can use free stock images.</b></p>
			<br />
		</center>
       
        <div class="mw-field stock-field">
            <input type="text" placeholder="Search for cars, cats and evrything you want..." onkeyup="mw.on.stopWriting(this,function(){searchMediaLibrary(this.value)})" >
        </div>

        <div style="display: none;" id="resbox">
            <div id="mw-media-library-results">
                <ul class="mw-browser-list" id="media-results">
                </ul>
            </div>
        </div>

    </div>
</div>
