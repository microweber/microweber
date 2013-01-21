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

        mw.tools.dropdown();

        mw.dd_autocomplete('#dd_pages_search');

        mw.simpletabs();

    });


</script>


<style type="text/css">

#insert_link_list{


}
#insert_link_list .mw_dropdown_val{
  width: 250px;
}

#insert_link_list .mw-ui-field{
  margin: 13px;
  width: 255px;
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
            <div class="mw-ui-field left">
                <span id="image_status" class="link"></span>
                <input type="text" style="width: 220px;" class="mw-ui-invisible-field" />
            </div>
            <span class="mw-ui-btn mw-ui-btn-blue left">Save</span>
            <div class="mw_clear"></div>
            <label class="mw-ui-check"><input type="checkbox"><span></span><span>Open link in new window</span></label>
        </div>

        <!-- TAB 2 -->
        <div class="tab">

          <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw_dropdown mw_dropdown_type_navigation left"> <span class="mw_dropdown_val">Home Page</span>
                <div class="mw_dropdown_fields">
                  <ul class="">
                    <li class="other-action" value="-1">
                      <div class="dd_search">
                          <input type="text" class="mw-ui-field  pages_search inactive dd_search" id="dd_pages_search">
                      </div>
                    </li>
                  </ul>
                </div>
            </div>

        </div>

        <!-- TAB 3 -->
        <div class="tab">




        </div>

        <!-- TAB 4 -->
        <div class="tab">tab 4 :)</div>
    </div>
</div>












































