
<?php $path = INCLUDES_URL . "toolbar/editor_tools/rte_image_editor/"; ?>

<script type="text/javascript">

     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");
     mw.require("url.js");

</script>
<script type="text/javascript">


    GlobalEmbed = false;
    hash = window.location.hash.replace(/#/g, '');

    afterInput = function(url, todo){   //what to do after image is uploaded (depending on the hash in the url)

      var todo = todo || false;



      if(!todo){
          if(hash!==''){
            if(hash=='editimage'){
              parent.mw.image.currentResizing.attr("src", url);
              parent.mw.tools.modal.remove('mw_rte_image');
            }
            else if(hash=='set_bg_image'){
              parent.mw.wysiwyg.set_bg_image(url);
              parent.mw.tools.modal.remove('mw_rte_image');
            }
            else{
              parent.mw.exec(hash, url);
            }
          }
          else{ /*
            parent.mw.wysiwyg.restore_selection();
            parent.mw.wysiwyg.insert_image(url, true);      */
          }
      }
      else{
        if(todo=='video'){
          parent.mw.wysiwyg.insert_html('<div class="element mw-embed-embed"><embed controller="true" loop="false" autoplay="false" width="560" height="315" src="'+url+'"></embed></div>');
          parent.mw.tools.modal.remove('mw_rte_image');
        }
      }




    }


    $(document).ready(function(){

        mw.simpletabs(document.getElementById('image_tabs'));

        Progress =  mw.$('#mw-upload-progress');
        ProgressBar = Progress.find('.mw-ui-progress-bar');
        ProgressInfo = Progress.find('.mw-ui-progress-info');
        ProgressPercent = Progress.find('.mw-ui-progress-percent');
        ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;Done! All files have been uploaded.';
        ProgressErrorHTML = function(filename){return '<span class="ico iRemove" style="top:-6px;"></span>&nbsp;Error! "'+filename+'" - Invalid filetype.';}

        mw.$(".mw-upload-filetypes li").each(function(){
          var li = $(this);
          var filetypes = li.dataset('type');

          var frame = mw.files.uploader({filetypes:filetypes});
          frame.width = li.width();
          frame.height = li.height();
          $(frame).bind("progress", function(frame, file){
              ProgressBar.width(file.percent+'%');
              ProgressPercent.html(file.percent+'%');
              ProgressInfo.html(file.name);
              li.parent().find("li").addClass('disabled');
          });
          $(frame).bind("done", function(frame, item){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressDoneHTML);
              li.parent().find("li").removeClass('disabled');
              if(filetypes!='videos'){
                  afterInput(item.src);
              }
              else{
                afterInput(item.src, 'video');
              }


          });
          $(frame).bind("error", function(frame, file){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressErrorHTML(file.name));
              li.parent().find("li").removeClass('disabled');
          });

          $(frame).bind("FilesAdded", function(frame, files_array, runtime){
              if(runtime == 'html4'){
                ProgressInfo.html('Uploading - "' + files_array[0].name+'" ...');
              }
          });
          li.append(frame);
          li.hover(function(){
            if(!li.hasClass('disabled')){
               li.parent().find("li").not(this).addClass('hovered');
            }

          }, function(){
            if(!li.hasClass('disabled')){
                li.parent().find("li").removeClass('hovered');
            }
          });
        });

        var fu = mw.$('#mw_folder_upload');
        var frame = mw.files.uploader({name:'upload_file_link'});
        frame.className += ' mw_upload_frame';
        frame.width = fu.width();
        frame.height = fu.height();
        fu.append(frame);

        $(frame).bind("progress", function(frame, file){
              ProgressBar.width(file.percent+'%');
              ProgressPercent.html(file.percent+'%');

        });
        $(frame).bind("done", function(frame, item){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressDoneHTML);


              afterInput(item.src);

        });

        $(frame).bind("error", function(frame, file){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressErrorHTML(file.name));

        });

         $(frame).bind("FilesAdded", function(frame, files_array, runtime){
              if(runtime == 'html4'){
                ProgressInfo.html('Uploading - "' + files_array[0].name+'" ...');
              }
          });


          var urlSearcher = mw.$("#media_search_field");
          var submit = mw.$('#btn_insert');
          var status = mw.$("#image_status");

          urlSearcher.bind('keyup paste', function(e){
             GlobalEmbed = false;
             if(e.type=='keyup'){
               mw.on.stopWriting(urlSearcher[0], function(){
                 var val = urlSearcher.val();
                 var type = mw.url.type(val);
                 status[0].className = type;
                 if(type!='image'){
                    status.empty();
                 }
                 else{
                   status.html('<img class="image_status_preview_image" src="'+val+'" />');
                 }
                 GlobalEmbed = __generateEmbed(type, val);
               });
             }
             else{
                 setTimeout(function(){
                   var val = urlSearcher.val();
                   var type = mw.url.type(val);
                   GlobalEmbed = __generateEmbed(type, val);
                   if(type!='link'){
                       parent.mw.wysiwyg.insert_html(GlobalEmbed);
                       parent.mw.tools.modal.remove('mw_rte_image');
                   }
                 }, 500);
             }

          });


          submit.click(function(){


              var val = urlSearcher.val();
              var type = mw.url.type(val);
              if(type!='link'){
                parent.mw.wysiwyg.insert_html(GlobalEmbed);
              }
              else{
                parent.mw.wysiwyg.insert_link(val);
              }
              parent.mw.tools.modal.remove('mw_rte_image');
          });

    });  //end document ready



    __generateEmbed = function(type, url){
       switch(type){
         case 'link':
           return mw.embed.link(url);
           break;
         case 'image':
           return mw.embed.image(url);
           break;
         case 'youtube':
            return mw.embed.youtube(url);
           break;
         case 'vimeo':
         return  mw.embed.vimeo(url);
         break;
         default:
         return false;
       }
    }



mw.embed = {
  link:function(url, text){
    if(!!text){
      return '<a href="'+url+'" title="'+text+'">'+text+'</a>';
    }
    else{
      return '<a href="'+url+'">'+url+'</a>';
    }
  },
  image:function(url, text){
    if(!!text){
      return '<img class="element" src="'+url+'"  alt="'+text+'" title="'+text+'" />';
    }
    else{
      return '<img class="element" src="'+url+'"  alt=""  />';
    }
  },
  youtube:function(url){
    if(url.contains('youtu.be')){
      var id = url.split('/').pop();
      if(id==''){
        var id = id.pop();
      }
      return '<div class="element mw-embed-iframe"><iframe width="560" height="315" src="http://www.youtube.com/embed/'+id+'?v=1" frameborder="0" allowfullscreen></iframe></div>';
    }
    else{
      var id = mw.url.getUrlParams(url).v;
      return '<div class="element mw-embed-iframe"><iframe width="560" height="315" src="http://www.youtube.com/embed/'+id+'?v=1" frameborder="0" allowfullscreen></iframe></div>';
    }
  },
  vimeo:function(url){
    var id = url.split('/').pop();
    if(id==''){
      var id = id.pop();
    }
    return '<div class="element mw-embed-iframe"><iframe src="http://player.vimeo.com/video/'+id+'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a" width="560" height="315" frameborder="0" allowFullScreen></iframe></div>';
  }
}


</script>


<style type="text/css">

.mw-o-box-content{
  height: 150px;
}

.mw-upload-filetypes{
  list-style: none;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.mw-upload-filetypes li{
  margin: 0 30px;
  font-size:11px;
  display: inline-block;
  position: relative;
  cursor: default;
  width: 80px;
  text-align: center;
  overflow: hidden;
  transition: opacity 0.12s;
  -moz-transition: opacity 0.12s;
  -webkit-transition: opacity 0.12s;
  -o-transition: opacity 0.12s;
}

.mw-upload-filetypes .mw-upload-frame{
  display: block;
  width: 80px;
  height: 66px;
  background: url(<?php print $path; ?>buttons.png) no-repeat;

}
.mw-upload-filetypes li.disabled, .mw-upload-filetypes li.hovered{ opacity:0.4; }


.mw-upload-filetypes li.mw-upload-filetype-video .mw-upload-frame{ background-position: -143px 0; }
.mw-upload-filetypes li.mw-upload-filetype-file .mw-upload-frame{ background-position: -273px 0; }

.mw-upload-filetypes li span{
  display: block;
  padding-top:10px;
}

.mw-upload-filetypes li iframe{
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
}
.mw-upload-filetypes li.disabled iframe, .mw-upload-filetypes li.hovered iframe{ left:-9999px; }
.mw_tabs_layout_simple .mw_simple_tabs_nav{
  padding-top: 0;
}

#media_search_field{
  float: right;
  width: 202px;
}

#media-search-holder{
  margin: 0 auto;
  padding-top: 45px;
  width: 350px;
}
.image_status_preview_image{
  max-width:100%;
  max-height: 100%;
}

</style>

<div class="mw_simple_tabs mw_tabs_layout_simple" id="image_tabs">
  <ul class="mw_simple_tabs_nav">
    <li><a href="#">My Computer</a></li>
    <li><a href="#" onmouseup="mw.$('#media_search_field').focus();">URL</a></li>
    <li><a href="#">Search</a></li>
  </ul>
  <div class="mw_clear"></div>
  <div class="tab" id="drag_files_here">
    <center style="padding-top: 25px;">


        <ul class="mw-upload-filetypes" id="">
            <li class="mw-upload-filetype-image" data-type="images">
                <div class="mw-upload-frame"></div>
                <span>Image</span>
            </li>
            <li class="mw-upload-filetype-video" data-type="videos">
                <div class="mw-upload-frame"></div>
                <span>Video</span></li>
            <li class="mw-upload-filetype-file" data-type="files">
                <div class="mw-upload-frame"></div>
                <span>Files</span>
            </li>
        </ul>



      <div class="drag_files_label" style="display: none;">Drag your files here</div>
    </center>
  </div>
  <div class="tab" id="get_image_from_url">


    <div id="media-search-holder">
    <div class="mw-ui-field left" style="width: 230px;" id="media_search">
        <span id="image_status"></span>
        <input type="text" id="media_search_field" onfocus="mw.form.dstatic(event);" onblur="mw.form.dstatic(event);" data-default="URL" value="URL" class="mw-ui-invisible-field" name="get_image_by_url" />
     </div>
    <button type="button" class="mw-ui-btn mw-ui-btn-blue right" id="btn_insert" style="font-size: 12px;width:80px;">Insert</button>


   </div>

  </div>
  <div class="tab">

    <? exec_action('rte_image_editor_image_search'); ?>

  </div>

</div>

<div class="vSpace"></div>


<div class="mw-ui-progress" id="mw-upload-progress" style="width: 100%">
    <div class="mw-ui-progress-bar" style="width: 0%;"></div>
    <div class="mw-ui-progress-info"></div>
    <span class="mw-ui-progress-percent"></span>
</div>
