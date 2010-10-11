<!--SUBNAV-->

<?php include ACTIVE_TEMPLATE_DIR."users/post.js.php" ?>

<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <ul class="nav">
      <?php //include ACTIVE_TEMPLATE_DIR."users/posts_header_nav.php" ?>
      <li><a href="#choose-layout" title="Choose Layout" id="t1"><span>Choose Layout</span></a></li>
      <li><a href="#choose-theme" title="Choose Style" id="t2"><span>Choose Style</span></a></li>
      <li><a href="#edit-template" title="Edit template" id="t3"><span>Edit page</span></a></li>
      <li><a href="#publish" title="Publish" id="t4"><span>Publish</span></a></li>
    </ul>
  </div>
  
  <!--HELP-->
  <div id="RU-help"> <a href="#" title="Help" class="help"></a>
    <div>
      <div>
        <div id="tab1"></div>
      </div>
      <div id="preloader"></div>
    </div>
    
    <!--END HELP--> 
  </div>
  <div class="clr"></div>
  <!--END SUBNAV--> 
</div>
<div id="RU-content">
  <form class="" action="<? print site_url($this->uri->uri_string()); ?>"   method="post" enctype="multipart/form-data"   id="postform">
    <div class="pad2"></div>
    
    <!-- panes -->
    
    <div class="panes" id="form-manager">
      <div id="form-manager-holder" style="left: 0px;">
        <div class="tab" id="choose-layout">
          <div id="layouts-list" > 
            <!-- comes from axaj--> Loading layouts... </div>
          <div class="scroller">
            <div class="scroller-content" > </div>
            <span class="scroll-left"></span> <span class="scroll-right"></span> </div>
          <a class="next tabs-next" href="#">Continue to the next step</a> </div>
        <div class="tab" id="choose-theme">
          <h2>Choose style</h2>
          <div id="layout-styles-list" class="layouts-list-2"> <!-- comes from axaj--> Loading styles...</div>
        </div>
        <div class="tab" id="edit-template">
          <? if($the_content_is_saved == true): ?>
          <h2>saved!</h2>
          <br />
          <a href="<? print $this->content_model->getContentURLById($the_saved_id); ?>" target="_blank"><strong>See it</strong></a><br />
          <a href="<? print site_url('users/user_action:post/id:'.$the_saved_id) ; ?>"><strong>Edit it</strong></a>
          <? // var_dump($the_saved_id); ?>
          <? else: ?>
          <script type="text/javascript">
 
 
</script>
          <? if(!empty($form_errors)): ?>
          <? p($form_errors); ?>
          <? endif; ?>
          <input name="content_layout_name" type="hidden" id="content_layout_name" value="<? print $form_values['content_layout_name'] ?>" />
          <input name="content_layout_style" type="hidden" id="content_layout_style" value="<? print $form_values['content_layout_style'] ?>" />
          <input name="content_subtype" type="hidden" id="content_subtype" value="form" />
          <textarea name="content_body" id="content_body" class="required richtext"  cols="" rows=""><? print $form_values['content_body']; ?></textarea>
          <? endif; ?>
        </div>
        <div class="tab" id="publish">
          <div class="box-holder">
            <div class="box-top">&nbsp;</div>
            <div class="box-inside">
              <h2 class="box-title">Please fill the fields correctly</h2>
              <div class="hr">&nbsp;</div>
              <label class="label-160">Choose category</label>
              <input name="id" type="hidden" value="<? print $form_values['id']; ?>" />
              <?
                    //$categories_ids_to_remove = false;
                    //p($categories_ids_to_remove);
                    //p($categories_ids_to_add);
                    
                    $categories = $this->taxonomy_model->getTaxonomiesForContent($form_values['id'], 'categories');
                    //var_dump($categories);
                    //$last = count($categories);
                    $actve_ids = false;
                    
                    if(!empty($categories)){
                        $actve_ids = $categories;
                    }
                    
                    $active_code = ' checked="checked"  ';
                    $removed_ids_code = ' disabled="disabled"   ';
                    $this->content_model->content_helpers_getCaregoriesUlTree($content_parent = 0, $link=  "<input id='category_selector_{id}' name='taxonomy_categories[]' type='radio'  {active_code}  {removed_ids_code}   id='category_selector_{id}' value='{id}' /><label>{taxonomy_value}</label>", $actve_ids = $categories_ids_to_add, $active_code , $remove_ids = $categories_ids_to_remove, $removed_ids_code = $removed_ids_code, $ul_class_name =  'user-post-select-categories ooyesTrees', $include_first = true, $content_type = 'form', $li_class_name = false, $add_ids = false);

                    //p($categories_ids_to_add);
                ?>
              <div class="hr">&nbsp;</div>
              <label class="label-160">Title *</label>
              <span class="field">
              <input style="width: 550px;" class="required type-text" name="content_title" type="text" value="<? print $form_values['content_title']; ?>" />
              </span>
              <div class="hr">&nbsp;</div>
              <label class="label-160">Paste your Aweber, GetResponce or MailChimp form code: *</label>
              <span class="field">
              <textarea style="width: 550px;height: 50px;" class="required"  name="custom_fields_form_code" cols="" rows=""><? print $form_values['custom_fields']['form_code']; ?></textarea>
              </span>
              <div class="hr">&nbsp;</div>
              <label class="label-160">Save</label>
              <input name="save" type="submit" value="save" />
            </div>
            <div class="box-bottom">&nbsp;</div>
          </div>
        </div>
      </div>
    </div>
    <!-- /RU-content -->
    <div class="pad2" style="height: 20px;"></div>
  </form>
  <!-- formata --> 
  <!--END CONTENT--> 
</div>
<script type="text/javascript">




    function Sizes(){
      //alert(windowLoaded)
        if(windowLoaded == true){
          //alert($("#content_body_ifr").attr('scrollHeight'))
            var window_width = $(window).width()
            $("#content_body_tbl").width(window_width);
            $(".tab").width(window_width-40);
            $("#form-manager-holder").width($("#form-manager-holder .tab").length*window_width+100);
            var href = $(".nav a.current").attr("href");
            var scroller_left = parseFloat($('#form-manager-holder').css('left'));
            var href_item_offset = $(href + '-tab').offset().left;
            var tab_left = href_item_offset - $('#form-manager').offset().left;
            $("#form-manager-holder").not(":animated").css({"left":scroller_left-tab_left});
            if(window.location.hash=='#edit-template'){
              $('body, html').css('overflow', 'hidden');
            }
            else{
               $('body, html').removeAttr('style')
            }
            $("#content_body_ifr").height($(window).height() - $('.mceToolbar').height() - $('#RU-header').height() - $('#RU-subnav').height() - 47)
            $("#content_body_tbl").height($(window).height() - $('#RU-header').height() - $('#RU-subnav').height() - 100)
        }
    }
    setInterval("Sizes()", 100);

    $(window).load(function(){
      if(window.location.hash==''){
        window.location.hash='#choose-layout';
      }
    })







</script> 