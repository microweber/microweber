
<script>
     parent.mw.require("external_callbacks.js");
     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");

</script>

<script type="text/javascript">

    is_searching = false;


    var hash = window.location.hash;
    var hash = hash.replace(/#/g, "");

    var hash = hash!=='' ? hash : 'insert_link';


      mw.dd_autocomplete = function(id){
      var el = $(id);

      el.bind("change keyup focus", function(event){
        if(!is_searching){
            var val = el.val();
            if(event.type=='focus'){
              if(el.hasClass('inactive')){
                el.removeClass('inactive')
              }
              else{
                return false;
              }
            }
            mw.tools.ajaxSearch({keyword:val, limit:10}, function(){
              var lis = "";
              var json = this;
              for(var item in json){
                  var obj = json[item];
                  if(typeof obj === 'object'){
                    var title = obj.title;
                    var url = obj.url;
                    lis+= "<li class='mw-dd-list-result' value='"+url+"' onclick='$(\"#insert_link_list\").setDropdownValue(\""+url+"\")'>"+title+"</a>";
                  }
              }
              var ul = el.parent().find("ul");
              ul.find("li:gt(0)").remove();
              ul.append(lis);
            });
        }
      });
    }










    $(document).ready(function(){


        Progress =  mw.$('#mw-upload-progress');
        ProgressBar = Progress.find('.mw-ui-progress-bar');
        ProgressInfo = Progress.find('.mw-ui-progress-info');
        ProgressPercent = Progress.find('.mw-ui-progress-percent');
        ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;<?php _e("Done! All files have been uploaded"); ?>.';
        ProgressErrorHTML = function(filename){return '<span class="ico iRemove" style="top:-6px;"></span>&nbsp;<?php _e("Error"); ?>! "'+filename+'" - <?php _e("Invalid filetype"); ?>.';}




        mw.tools.dropdown();

        mw.dd_autocomplete('#dd_pages_search');



    var frame = mw.files.uploader({name:'upload_file_link',filetypes:'all',multiple:false});

    var frame_holder = mw.$("#upload_frame");

    frame.width = frame_holder.width();

    frame.height = frame_holder.height();




        frame.className += ' mw_upload_frame';

        $(frame).bind("progress", function(frame, file){
              ProgressBar.width(file.percent+'%');
              ProgressInfo.html(file.name);
              ProgressPercent.html(file.percent+'%');

        });
        $(frame).bind("done", function(frame, item){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressDoneHTML);
              parent.mw.tools.modal.remove('mw_rte_link');
              Progress.hide()
        });

        $(frame).bind("error", function(frame, file){
              ProgressBar.width('0%');
              ProgressPercent.html('');
              ProgressInfo.html(ProgressErrorHTML(file.name));
              Progress.hide()
        });

         $(frame).bind("FilesAdded", function(frame, files_array, runtime){
              if(runtime == 'html4'){
                ProgressInfo.html('<?php _e("Uploading"); ?> - "' + files_array[0].name+'" ...');
              }
              Progress.show()
          });



          $(frame).bind("FileUploaded", function(frame, item){

              parent.mw.iframecallbacks[hash](item.src);

          });







    frame_holder.append(frame);


    mw.$("#insert_email").click(function(){
         var val = mwd.getElementById('email_field').value;
         if(!val.contains('mailto:')){
            var val = 'mailto:'+val;
         }
         parent.mw.iframecallbacks[hash](val);
         parent.mw.tools.modal.remove('mw_rte_link');
         RegisterChange(hash, val);
         return false;
    });
    mw.$("#insert_url").click(function(){
         var val = mwd.getElementById('customweburl').value;
         var target = '_self';
         if(hash=='insert_link'){
           if(mwd.getElementById('url_target').checked == true){
             var target = '_blank';
           }
         }
         RegisterChange(hash, val, target);
         parent.mw.iframecallbacks[hash](val, target);
         parent.mw.tools.modal.remove('mw_rte_link');

         return false;
    });

    $("#insert_from_dropdown").click(function(){
         var val = mw.$("#insert_link_list").getDropdownValue();
         parent.mw.iframecallbacks[hash](val);
         parent.mw.tools.modal.remove('mw_rte_link');
        return false;
    });

     mw.tabs({
       nav:".mw-ui-btn-nav-tabs a",
       tabs:".tab"
     });



    });





</script>
 

<style type="text/css">



#upload_frame{
  width: 100%;
  height: 100%;
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
}


#tabs .tab{
  display: none;
}
#mw-popup-insertlink{
  padding: 10px;
}
.mw-ui-row-nodrop, .media-search-holder{
  margin-bottom: 12px;
}
.mw-ui-box-content{
  padding-top: 20px;
}

</style>


<div id="mw-popup-insertlink">
    <div class="">

        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
           <a  class="mw-ui-btn active" href="javascript:;"><?php _e("Website URL"); ?></a>
            <a class="mw-ui-btn" href="javascript:;"><?php _e("Page from My Website"); ?></a>
            <a class="mw-ui-btn" href="javascript:;"><?php _e("File"); ?></a>
            <a class="mw-ui-btn" href="javascript:;"><?php _e("Email"); ?></a>
        </div>
        <div class="mw-ui-box mw-ui-box-content" id="tabs">
        <!-- TAB 1 -->
        <div class="tab" style="display: block">

            <div class="media-search-holder">
              <div class="mw-ui-row-nodrop w100">
                  <div class="mw-ui-col"><input type="text" class="mw-ui-field w100" id="customweburl" autofocus="" /></div>
                  <div class="mw-ui-col" style="width: 90px;">
                    <span class="mw-ui-btn pull-right" id="insert_url"><?php _e("Insert"); ?></span>
                  </div>
              </div>

              <label class="mw-ui-check"><input type="checkbox" id="url_target"><span></span><span><?php _e("Open link in new window"); ?></span></label>
            </div>

        </div>

        <!-- TAB 2 -->
        <div class="tab">
            <div class="media-search-holder">
                <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw-dropdown mw-dropdown_type_navigation mw-dropdown_autocomplete left">
                    <span class="mw-dropdown-val"><?php _e("Click here to select"); ?></span>
                    <input type="text" class="mw-ui-field  pages_search dd_search inactive" id="dd_pages_search" />
                    <div class="mw-dropdown-content">
                      <ul class="">
                        <li class="other-action" value="-1"></li>
                      </ul>
                    </div>
                </div>
                <span class="mw-ui-btn mw-ui-btn-blue right insert_the_link" id="insert_from_dropdown" style="padding: 7px 10px;"><?php _e("Insert"); ?></span>
            </div>
        </div>

        <!-- TAB 3 -->
        <div class="tab">
            <div class="media-search-holder">
              <div class="mw-ui-btn mw-ui-btn-big w100">
                  <div id="upload_frame"></div>
                  <span class="mw-icon-upload"></span><?php _e("Upload"); ?>
              </div>
            </div>

            <div class="mw-ui-progress" id="mw-upload-progress" style="width: 100%;display: none">
                <div class="mw-ui-progress-bar" style="width: 0%;"></div>
                <div class="mw-ui-progress-info"></div>
                <span class="mw-ui-progress-percent"></span>
            </div>

        </div>

        <!-- TAB 4 -->
        <div class="tab">
            <div class="media-search-holder">
                <input type="text" class="mw-ui-field" id="email_field" />
                <span class="mw-ui-btn mw-ui-btn-blue right insert_the_link" id="insert_email"><?php _e("Insert"); ?></span>
            </div>

        </div> </div>
    </div>
</div>












































