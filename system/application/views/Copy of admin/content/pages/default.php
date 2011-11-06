<?php require_once (ADMINVIEWSPATH.'content/pages/blocks/_start.php')  ?>


<div id="message_area">
    <div id="message_wrapper">
        <div class="help_message" id="message">

                            Pages are the main structure of the site. You can choose the page to be a static page, articles category or module
                 </div>
        <!-- /message -->
    </div><!-- /message_wrapper -->
</div>

<div style="">
<table cellpadding="0" cellspacing="0" id="asdbtable" width="100%">
  <tr>
    <td width="250px" valign="top"><div class="bar left">
        <script>
    $(document).ready(function(){
         var tabsobj = $("#tabsobj").tabs();

         $("#uictrl li").click(function(){
             var UIindex =  $("#uictrl li").index(this);
             //alert(UIindex)
             tabsobj.tabs('select', UIindex);
             $("#uictrl li").removeClass("active");
             $(this).addClass("active");


         });


        /* $(".objbigSave").css({
           "marginLeft":"74px"
         })    */

      })
</script>
         
        <div class="clear" style="height: 5px;"></div>
        <ul id="uictrl" class="stabs">
          <li class="active"><a href="javascript:;">Content</a></li>
         <!-- <li><a href="javascript:;">Gallery</a></li>-->
          <!--<li><a href="javascript:;">Text and description</a></li>-->
          <!--<li><a href="javascript:;">Original link options</a></li>-->
          <!--<li><a href="javascript:;">Tags</a></li>-->
          <!--<li><a href="javascript:;">Event callendar</a></li>-->
          
          <!--<li><a href="javascript:;">Other options</a></li>-->

          <li><a href="javascript:;">Advanced settings</a></li>
          <li><a href="javascript:;">Menus</a></li>
          <li><a href="javascript:;">Meta tags</a></li>
        </ul>
        <div style="clear: both;overflow: hidden;height: 10px;">&nbsp;<!--  --></div>
        <a href="javascript:;" class="objbigSave" style="float: left">Save</a>

        <div class="clear" style="height: 5px;"></div>
      </div></td>
    <td valign="top"><div id="side-content-wrapper">
        <div id="side-content">
          <div id="tabsobj" style="position: absolute;top:-10000px">
            <ul style="height: 34px;display: none !important">
              <li class="ui-state-active"><a href="#uitab1">Content and URL</a></li>
            <!--  <li><a href="#uitab2">Gallery</a></li>-->
            
             <li><a href="#uitab3">Advanced settings</a></li>
 

               <li><a href="#uitab4">Menus</a></li>
                <li><a href="#uitab5">Meta tags</a></li>
            </ul>
          </div>
          <div class="objtabs" id="uitab1">
            <?php require_once (ADMINVIEWSPATH.'content/pages/blocks/content_and_url.php')  ?>

          </div>

   <!--       <div class="objtabs" id="uitab2">
            <?php //  require_once (ADMINVIEWSPATH.'content/content_blocks/gallery.php')  ?>
          </div>-->

          <div class="objtabs" id="uitab3">
             <?php require_once (ADMINVIEWSPATH.'content/pages/blocks/basic_settings.php')  ?>
          </div>
          
           <div class="objtabs" id="uitab4">
             <?php require_once (ADMINVIEWSPATH.'content/pages/blocks/menus.php')  ?>
          </div>
          
          <div class="objtabs" id="uitab5">
             <?php require_once (ADMINVIEWSPATH.'content/pages/blocks/meta_tags.php')  ?>
          </div>
  
    
        </div>
      </div></td>
  </tr>
</table>
</div>
<?php require_once (ADMINVIEWSPATH.'content/pages/blocks/_end.php')  ?>
