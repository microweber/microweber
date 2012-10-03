<script type="text/javascript">

    is_searching = false;

     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");


    var hash = window.location.hash;
    var hash = hash.replace(/#/g, "");

    command = hash!=='' ? hash : 'insert_link';

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
                var title = obj.title;
                var url = obj.url;
                lis+= "<li value='"+url+"'><a onclick='mw.tools.dd_sub_set(this)' href='javascript:;'>"+title+"</a>";
            }
            var parent = el.parents("ul");
            parent.find("li:gt(0)").remove();
            parent.append(lis);
          });
        }
      });
    }

    $(document).ready(function(){

        mw.tools.dropdown();

        mw.dd_autocomplete('#dd_pages_search');

        $("#upload_file_link").bind("progress",function(obj,percents){
            $("#file_link_progres").width(percents+"%");
        });
        $("#upload_file_link").bind("done",function(a,data){
            $("#file_link_progres").width(0);
            $("#file_radio").val(data.src);
            $("#insert_link_btn").removeClass("disabled");
        });


  $(".field_wrapper, .field_wrapper iframe").click(function(){
      var el = this.tagName.toLowerCase()=='iframe' ? $(this).parents(".field_wrapper").eq(0) : $(this);
      if(!el.hasClass("active")){
          el.parents(".link_type_holder_main").find(".field_wrapper").removeClass("active");
          el.addClass("active");
          el.find("input").attr("checked", true);
      }
  });

  $(".field_wrapper input[type='text']").focus(function(){
    $(this).parents(".field_wrapper").trigger("click");
    if(this.id=='mail_url'){
       if(mw.form.validate.email(this)){
            $("#insert_link_btn").removeClass("disabled");
       }
       else{
          $("#insert_link_btn").addClass("disabled");
       }
    }
    else if(this.id=='link_url'){
       if(mw.form.validate.url(this)){
            $("#insert_link_btn").removeClass("disabled");
       }
       else{
          $("#insert_link_btn").addClass("disabled");
       }
    }
  });



       $("#insert_link_btn").bind("mousedown mouseup click", function(event){
           event.preventDefault();

           if(event.type=='click'){
             if(!$(this).hasClass("disabled")){
                
                var val = $("input:radio:checked").val();
                if(mw.form.validate.email(val)){
                  var val = 'mailto:'+val;
                }
                parent.mw.wysiwyg[command](val);
                parent.mw.tools.modal.remove('mw_rte_link');
             }
             else{

             }
           }
       });




       $("#link_url").keyup(function(){
         if(mw.form.validate.url(this)){
            $("#insert_link_btn").removeClass("disabled");
            var val = (this.value.indexOf('http://')==0 || this.value.indexOf('https://')==0 || this.value.indexOf('ftp://')==0) ? this.value : 'http://'+this.value;
            $("#link_url_radio").val(val);
         }
         else{
           $("#insert_link_btn").addClass("disabled");
         }
       });


       $("#mail_url").keyup(function(){
           if(mw.form.validate.email(this)){
                $("#insert_link_btn").removeClass("disabled");
                $("#mail_radio").val(this.value);
           }
           else{
              $("#insert_link_btn").addClass("disabled");
           }
       });


       $("#insert_link_list").change(function(){
         var val = $(this).getDropdownValue();
         $("#dd_pages_search_radio").val(val);
         $("#insert_link_btn").removeClass("disabled");
       });


    });


</script>


<h2 class="popup_title">Link to: </h2>

<div id="link_tool_holder">


  <div class="create_link_box link_type_holder_main" id="pop_up_link">




  <div class="link_type_to type_to_url">
    <h3>Website URL</h3>
    <label><input type="checkbox" checked="false" id="is_target_blank" />Open link in new window</label>
    <div class="field_wrapper active">
        <input type="radio" name="linktype" id="link_url_radio" checked="checked" value="" />
        <div class="link_type_holder">
            <span class="fancy_input white"><input type="text" id="link_url" /></span>
        </div>
    </div>
  </div>
  <div class="link_type_to type_to_my_site">
    <h3>Page on My Website</h3>
    <div class="field_wrapper relative z-2">
        <input type="radio" name="linktype" id="dd_pages_search_radio" value="" />
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
    <div class="field_wrapper relative">
        <div id="file_link_progres"></div>
        <input type="radio" name="linktype" id="file_radio" value="" />
        <div class="link_type_holder relative">
             No file. <span class="upload_file_link" id="Uploader">Upload a new file</span>

             <iframe scrolling="no" name="upload_file_link" id="upload_file_link" class="mw_upload_frame" frameborder="0" src="<? print SITE_URL; ?>editor_tools/plupload/"></iframe>

        </div>
    </div>
  </div>
  <div class="link_type_to type_to_mail">
    <h3>Email Address</h3>
    <div class="field_wrapper">
        <input type="radio" name="linktype" id="mail_radio"  value="" />
        <div class="link_type_holder">
            <span class="fancy_input white"><input type="text" id="mail_url" /></span>
        </div>
    </div>
  </div>


  <span class="bluebtn right disabled" id="insert_link_btn">Insert</span>


  </div>


  </div>

