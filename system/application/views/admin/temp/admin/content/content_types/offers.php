<?php require_once (ADMINVIEWSPATH.'content/content_blocks/_start.php')  ?>
<table cellpadding="0" cellspacing="0" id="asdbtable" width="100%"><tr>
<td width="250px" valign="top">
<div class="bar left">
<script>
    $(document).ready(function(){
         var tabsobj = $("#tabsobj").tabs();

         $("#uictrl li").click(function(){
             var UIindex =  $("#uictrl li").index(this);
             //alert(UIindex)
             tabsobj.tabs('select', UIindex);
             $("#uictrl li").removeClass("active");
             $(this).addClass("active");


         })
      })
</script>


<h2 class="blue_title" style="font-size: 20px;">Add new content</h2>


 <span style="display: block;padding: 5px 0;">Publish this content in category</span>


 <?php require_once (ADMINVIEWSPATH.'content/content_blocks/categories_selector.php')  ?>

 <div class="clear" style="height: 5px;"></div>

    <ul id="uictrl" class="stabs">
          <li class="active"><a href="javascript:;">Content</a></li>
          <!--<li><a href="javascript:;">Text and description</a></li>-->
          <!--<li><a href="javascript:;">Original link options</a></li>-->
          <!--<li><a href="javascript:;">Tags</a></li>-->
          <!--<li><a href="javascript:;">Event callendar</a></li>-->
          <li><a href="javascript:;">Gallery</a></li>
         <!-- <li><a href="javascript:;">Videos</a></li>-->
          <li><a href="javascript:;">Offer options</a></li>
          <!--<li><a href="javascript:;">Comments</a></li>-->
      </ul>
      <div class="clear" style="height: 5px;"></div>
      <a class="objbigSave right" href="javascript:;" style="float: left">Save</a>   

</div>
</td>
<td valign="top">
<div id="side-content-wrapper">
    <div id="side-content">
    <div id="tabsobj" style="position: absolute;top:-10000px">
      <ul style="height: 34px;display: none !important">
          <li class="ui-state-active"><a href="#uitab1">Content</a></li>
          <!--<li><a href="#uitab8">Text and description</a></li>-->
          <!--<li><a href="#uitab2">Original link options</a></li>-->
          <!--<li><a href="#uitab3">Tags</a></li>-->
          <!--<li><a href="#uitab4">Event callendar</a></li>-->
          <li><a href="#uitab5">Gallery</a></li>
          <!--<li><a href="#uitab6">Videos</a></li>-->
          <!--<li><a href="#uitab6">Other options</a></li>-->
          <li><a href="#uitab7">Offer options</a></li>
       <!--   <li><a href="#uitab8">Comments</a></li>-->

      </ul>
    </div>
      <div class="objtabs" id="uitab1">

      <?php require_once (ADMINVIEWSPATH.'content/content_blocks/title_and_url_selector.php')  ?>

        <?php require_once (ADMINVIEWSPATH.'content/content_blocks/text_and_desctiprion_editor.php')  ?>
        
        <?php require_once (ADMINVIEWSPATH.'content/content_blocks/offer_description.php')  ?>
      </div>

      <!--<div class="objtabs" id="uitab2"><?php //require_once (ADMINVIEWSPATH.'content/content_blocks/original_link_options.php')  ?></div>-->
      <!--<div class="objtabs" id="uitab3"><?php //require_once (ADMINVIEWSPATH.'content/content_blocks/tags_editor.php') ?></div>-->
      <!--<div class="objtabs" id="uitab4"><?php //require_once (ADMINVIEWSPATH.'content/content_blocks/event_calendar.php')  ?></div>-->
      <div class="objtabs" id="uitab5"><?php require_once (ADMINVIEWSPATH.'content/content_blocks/gallery.php')  ?></div>
       <!--<div class="objtabs" id="uitab6"><?php // require_once (ADMINVIEWSPATH.'content/content_blocks/videos.php')  ?></div>-->
      <!--<div class="objtabs" id="uitab6"><?php //require_once (ADMINVIEWSPATH.'content/content_blocks/other_options.php')  ?></div>-->
      <div class="objtabs" id="uitab7"><?php require_once (ADMINVIEWSPATH.'content/content_blocks/offer_options.php')  ?></div>


      <div class="objtabs" id="uitab8">
      <?php //require_once (ADMINVIEWSPATH.'content/content_blocks/comments_controlls.php')  ?>
      </div>




    </div>
</div>
</td>
</tr>
</table>



<?php require_once (ADMINVIEWSPATH.'content/content_blocks/_end.php')  ?>