
<script type="text/javascript">

    is_searching = false;

     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");

    $(document).ready(function(){
        mw.tools.dropdown();
        mw.dd_autocomplete("#dd_pages_search");

        Uploader = mw.files.browser({

        });

        mw.filechange(Uploader, function(){
            mw.files.upload(Uploader, {upload_type:'iframe'}, function(){

            }, function(){

            });
        });



        mw.files.browser_connector("#Uploader", Uploader);


    });



    mw.search = function(keyword, limit, callback){
      is_searching = true;
      var obj = {
        limit:limit,
        keyword:keyword
      }
      $.post(mw.settings.site_url + "api/get_content_admin", obj, function(data){
        var json = $.parseJSON(data);
        callback.call(json);
        is_searching = false;
      });
    }

    mw.dd_autocomplete = function(id){
      var el = $(id);
      el.bind("change keyup", function(){
        if(!is_searching){
          var val = el.val();
          mw.search(val, 10, function(){
            var lis = "";
            var json = this;
            for(var item in json){
                var obj = json[item];
                var title = obj.content_title;
                var url = obj.url;
                lis+= "<li value='"+url+"'><a href='javascript:;'>"+title+"</a>";
            }
            var parent = el.parents("ul");
            parent.find("li:gt(0)").remove();
            parent.append(lis);
          });
        }
      });
    }


</script>


<h2 class="popup_title">Link to: </h2>

<div id="link_tool_holder">


  <div class="create_link_box link_type_holder_main" id="pop_up_link">




  <div class="link_type_to type_to_url">
    <h3>Website URL</h3>
    <label><input type="checkbox" checked="false" id="is_target_blank" />Open link in new window</label>
    <div class="field_wrapper active">
        <input type="radio" name="linktype" checked="checked" value="outer_url" />
        <div class="link_type_holder">
            <span class="fancy_input white"><input type="text" id="link_url" /></span>
        </div>
    </div>
  </div>
  <div class="link_type_to type_to_my_site">
    <h3>Page on My Website</h3>
    <div class="field_wrapper">
        <input type="radio" name="linktype" value="from_site_url" />
        <div class="link_type_holder">
            <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw_dropdown mw_dropdown_type_navigation left"> <span class="mw_dropdown_val">Home Page</span>
                <div class="mw_dropdown_fields">
                  <ul class="mw_simple_tabs_nav">
                    <li class="other-action" value="-1">
                      <div class="dd_search">
                          <span class="fancy_input white"><input type="text" class="pages_search" id="dd_pages_search"><span class="tb_search_magnify"></span></span>
                      </div>
                    </li>
                    <li value="<?php print site_url(); ?>"><a href="#">Home Page</a></li>
                    <li value="<?php print site_url(); ?>other-page"><a href="#">Other Page</a></li>
                  </ul>
                </div>
            </div>
        </div>
    </div>
  </div>


  <div class="link_type_to type_to_file">
    <h3>File on My Website</h3>
    <div class="field_wrapper">
        <input type="radio" name="linktype" checked="checked" value="file_url" />
        <div class="link_type_holder">
             No file. <span class="upload_file_link" id="Uploader">Upload a new file</span>
             <small>Max file size: <?php print ini_get('upload_max_filesize'); ?></small>
        </div>
    </div>
  </div>
  <div class="link_type_to type_to_mail">
    <h3>Email Address</h3>
    <div class="field_wrapper">
        <input type="radio" name="linktype" checked="checked" value="mail_url" />
        <div class="link_type_holder">
            <span class="fancy_input white"><input type="text" id="mail_url" /></span>
        </div>
    </div>
  </div>





  </div>


  </div>

