
<script>

     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");

</script>

<script type="text/javascript">

    is_searching = false;


    var hash = window.location.hash;
    var hash = hash.replace(/#/g, "");

    var hash = hash!=='' ? hash : 'insert_link';

    mw.search = function(keyword, limit, callback){
      is_searching = true;
      var obj = {
        limit:limit,
        keyword:keyword,
        order_by:'updated_on desc',
        search_in_fields:'title'
      }
      $.post(mw.settings.site_url + "api/get_content_admin", obj, function(data){
        var json = $.parseJSON(data);
        callback.call(json);
        is_searching = false;
      });
    }

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
            mw.search(val, 10, function(){
              var lis = "";
              var json = this;
              for(var item in json){
                  var obj = json[item];
                  if(typeof obj === 'object'){
                    var title = obj.title;
                    var url = obj.url;
                    lis+= "<li class='mw-dd-list-result' value='"+url+"'><a onclick='mw.tools.dd_sub_set(this)' href='javascript:;'>"+title+"</a>";
                  }
              }
              var parent = el.parents("ul");
              parent.find("li:gt(0)").remove();
              parent.append(lis);
            });
        }
      });
    }










    $(document).ready(function(){


        Progress =  mw.$('#mw-upload-progress');
        ProgressBar = Progress.find('.mw-ui-progress-bar');
        ProgressInfo = Progress.find('.mw-ui-progress-info');
        ProgressPercent = Progress.find('.mw-ui-progress-percent');
        ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;Done! All files have been uploaded.';
        ProgressErrorHTML = function(filename){return '<span class="ico iRemove" style="top:-6px;"></span>&nbsp;Error! "'+filename+'" - Invalid filetype.';}




        mw.tools.dropdown();

        mw.dd_autocomplete('#dd_pages_search');

        mw.simpletabs();



    var frame = mw.files.uploader({name:'upload_file_link',filetypes:'all'});

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



          $(frame).bind("FileUploaded", function(frame, item){
              parent.mw.wysiwyg[hash](item.src);

          });







    frame_holder.append(frame);




    mw.$("#insert_email, #insert_url").click(function(){

        var target = '_self';

         var val = this.parentNode.querySelector('input[type="text"]').value;

         if(this.id == 'insert_email'){
            var val = 'mailto:'+val;
         }

         if(this.id == 'insert_url' && hash=='insert_link'){

           if(mwd.getElementById('url_target').checked == true){
             var target = '_blank';
           }

            parent.mw.wysiwyg.insert_link(val, target);
         }
         else{
            parent.mw.wysiwyg[hash](val);
         }




         parent.mw.tools.modal.remove('mw_rte_link');

         return false;
    });

    $("#insert_from_dropdown").click(function(){

         var val = mw.$("#insert_link_list").getDropdownValue();

         parent.mw.wysiwyg[hash](val);

         parent.mw.tools.modal.remove('mw_rte_link');

        return false;
    });



    });


</script>


<style type="text/css">

#insert_link_list{


}
#insert_link_list .mw_dropdown_val{
  width: 220px;
}

#insert_link_list .mw-ui-field{
  width: 228px;
}

.mw-dd-list-result {
  border-bottom:1px solid #fff;
}
.mw-dd-list-result a{
  border-bottom:1px solid #ddd;
}

ul li.mw-dd-list-result:last-child{
  border-bottom: none;
}
ul li.mw-dd-list-result:last-child a{
  border-bottom: none;
}

.mw_tabs_layout_simple .mw_simple_tabs_nav li{
  margin: 0;
}
.mw_tabs_layout_simple .mw_simple_tabs_nav li a{
  min-width: 35px;
}

#upload_frame{
  width: 100%;
  height: 100%;
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
}

.media-search-holder{
    margin: 0 auto;
    padding-top: 45px;
    width: 350px;
    position: relative;
}

.media-search-holder:after{
  content: ".";
  display: block;
  clear: both;
  visibility: hidden;
  line-height: 0;
  height: 0;
}


.mw-ui-check input[type="checkbox"] + span{
  top: 6px;
  left: 1px;
}

.mw-ui-check input + span + span{
  font-size: 11px;
}

.mw_dropdown_type_navigation li a{
  white-space: nowrap;
  overflow: hidden;
  text-overflow:ellipsis;
}


.mw_dropdown_fields > ul{
  height: 133px;
}
.mw_tabs_layout_simple .mw_simple_tabs_nav{
  padding-top: 0;
}

</style>


<div id="mw-popup-insertlink">
    <div class="mw_simple_tabs mw_tabs_layout_simple">
        <ul class="mw_simple_tabs_nav">
            <li><a class="active" href="javascript:;">Website URL</a></li>
            <li><a href="javascript:;">Page from My Website</a></li>
            <li><a href="javascript:;">File</a></li>
            <li><a href="javascript:;">Email</a></li>
        </ul>
        <!-- TAB 1 -->
        <div class="tab">

            <div class="media-search-holder">
              <div class="mw-ui-field left" style="width: 260px;">
                  <span id="" class="image_status link"></span>
                  <input type="text" style="width: 220px;" class="mw-ui-invisible-field" />
              </div>
              <span class="mw-ui-btn mw-ui-btn-blue right" id="insert_url">Insert</span>
              <div class="mw_clear" style="padding-bottom: 5px;"></div>
              <label class="mw-ui-check"><input type="checkbox" id="url_target"><span></span><span>Open link in new window</span></label>
            </div>

        </div>

        <!-- TAB 2 -->
        <div class="tab">
            <div class="media-search-holder">
                <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw_dropdown mw_dropdown_type_navigation mw_dropdown_autocomplete left">
                    <span class="mw_dropdown_val">Click here to select</span>
                    <div class="mw_dropdown_fields">
                      <ul class="">
                        <li class="other-action" value="-1">

                              <input type="text" class="mw-ui-field  pages_search inactive dd_search" id="dd_pages_search" />

                        </li>
                      </ul>
                    </div>
                </div>
                <span class="mw-ui-btn mw-ui-btn-blue right insert_the_link" id="insert_from_dropdown" style="padding: 7px 10px;">Insert</span>
            </div>
        </div>

        <!-- TAB 3 -->
        <div class="tab">
            <div class="media-search-holder">
              <div class="mw-ui-field relative left" style="width: 328px;">
                  <span class="image_status link"></span>
                  <div id="upload_frame"></div>
                  <span class="mw-ui-btn mw-ui-btn-blue insert_the_link" id="insert_email" style="height: 15px;position: absolute; right: -1px; top: -1px;">Upload</span>
              </div>
            </div>

            <div class="mw-ui-progress" id="mw-upload-progress" style="width: 100%">
                <div class="mw-ui-progress-bar" style="width: 0%;"></div>
                <div class="mw-ui-progress-info"></div>
                <span class="mw-ui-progress-percent"></span>
            </div>

        </div>

        <!-- TAB 4 -->
        <div class="tab">
            <div class="media-search-holder">
                <div class="mw-ui-field left" style="width: 260px;">
                    <span id="" class="image_status link"></span>
                    <input type="text" style="width: 220px;" class="mw-ui-invisible-field" id="email_field" />
                </div>
                <span class="mw-ui-btn mw-ui-btn-blue right insert_the_link" id="insert_email">Insert</span>
            </div>

        </div>
    </div>
</div>












































