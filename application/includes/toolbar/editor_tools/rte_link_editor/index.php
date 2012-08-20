
<script type="text/javascript">
     mw.require("forms.js");
     mw.require("files.js");
     mw.require("tools.js");

    $(document).ready(function(){

    });


</script>




  <div class="create_link_box" id="pop_up_link">


  <h2>Link to: </h2>

  <div class="link_type_to type_to_url">
    <h3>Website URL</h3>
    <label><input type="checkbox" checked="false" id="is_target_blank" />Open link in new window</label>
    <div class="field_wrapper active">
        <input type="radio" name="linktype" checked="checked" value="outer_url" />
        <div class="link_type_holder">
            <input type="text" id="link_url" />
        </div>
    </div>
  </div>
  <div class="link_type_to type_to_my_site">
    <h3>Page on My Website</h3>
    <div class="field_wrapper active">
        <input type="radio" name="linktype" value="from_site_url" />
        <div class="link_type_holder">
            <div data-value="<?php print site_url(); ?>" id="module_category_selector" class="mw_dropdown mw_dropdown_type_navigation left"> <span class="mw_dropdown_val">Home Page</span>
                <div class="mw_dropdown_fields">
                  <ul>
                    <li class="other-action" value="-1">
                      <div class="dd_search">
                          <input type="text" class="pages_search" id="dd_pages_search"><span class="tb_search_magnify"></span>
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
  <div class="link_type_to type_to_mail">
    <h3>Email Address</h3>
    <div class="field_wrapper active">
        <input type="radio" name="linktype" checked="checked" value="mail_url" />
        <div class="link_type_holder">
            <input type="text" id="mail_url" />
        </div>
    </div>
  </div>





  </div>

