<? 
$rand = rand();
 
if($params['cf_id']){
	
	$arr = array();
		$arr['id'] =$params['cf_id'];
$cf_conf = CI::model ( 'core' )->getCustomFieldsConfig($arr) 	;
$cf_conf = $cf_conf[0];
//p($cf_conf);
	
}


?>
<script>

  dd = '<img height="16"  src="<? print $config['url_to_module'] ?>dropdown.png"   style="display:inline-block;" />';

$(document).ready(function() {
   
   
   
   

 $(".objcolorsSizes<? print  $rand; ?> li:empty").remove();
     $(".objcolorsSizes<? print  $rand; ?> li:first-child").addClass("active");

     old_sizes = $("#selectTheSize ul").html();
     old_sizes_value = $("#selectTheSize li:first").html();

     var firstSize = $(".objcolorsSizes<? print  $rand; ?>:first").html();
     if($(".objcolorsSizes<? print  $rand; ?>:first li").length>0){
        var html = $(".objcolorsSizes<? print  $rand; ?>:first").html();
        $("#selectTheSize ul").html(html);
        $("#selectTheSize span").html($(".objcolorsSizes<? print  $rand; ?>:first li:first").html());
        $("#selectTheSize input").val($(".objcolorsSizes<? print  $rand; ?>:first li:first").html());
     }


     $("#objcolors<? print  $rand; ?> li").mouseup(function(){
       if($(this).find(".objcolorsSizes<? print  $rand; ?> li").length>0){
         var html = $(this).find(".objcolorsSizes<? print  $rand; ?>").html();
        $("#selectTheSize ul").html(html);
        $("#selectTheSize span").html($(this).find(".objcolorsSizes<? print  $rand; ?> li:first").html());
        $("#selectTheSize input").val($(this).find(".objcolorsSizes<? print  $rand; ?> li:first").html());

       }
       else{

        $("#selectTheSize ul").html(old_sizes);
        $("#selectTheSize span").html(old_sizes_value);
        $("#selectTheSize input").val(old_sizes_value);
        //$(document.body).append("<span>"+old_sizes_value+"</span>")
       }


    $(".DropDown<? print  $rand; ?> ul li").hover(function(){$(this).addClass("hover")}, function(){$(this).removeClass("hover")});




     });


$("#objcolors<? print  $rand; ?> li").addClass("parent");
$("#objcolors<? print  $rand; ?> li li").removeClass("parent");



$(".DropDown<? print  $rand; ?>").each(function(){
    var DropActiveHTML = $(this).find("li.active").html();
    var DropActiveValue = $(this).find("li.active").attr("title");
   
   
   
   
   
   
   $(this).find("span").html(DropActiveHTML +dd);
    $(this).find("input").val(DropActiveValue);
	 // $(this).find("input").change();
});
$(".DropDown<? print  $rand; ?>").addClass("OBJDropDown<? print  $rand; ?>");
$(".DropDown<? print  $rand; ?>").click(function(){
   $(this).find("ul").toggle();
   $(this).toggleClass("StateActive");
});

$(".DropDown<? print  $rand; ?>").hover(function(){
    $(this).removeClass("OBJDropDown<? print  $rand; ?>")
}, function(){
    $(this).addClass("OBJDropDown<? print  $rand; ?>")
});

$(".DropDown<? print  $rand; ?> ul li").hover(function(){$(this).addClass("hover")}, function(){$(this).removeClass("hover")});
$(".DropDown<? print  $rand; ?> ul li").live("click", function(){
    var DropItemHTML = $(this).html();
    var DropItemValue = $(this).attr("title");
    $(this).parents(".DropDown<? print  $rand; ?>").find("input").val(DropItemValue);
	
	
	
	
	
	
    $(this).parents(".DropDown<? print  $rand; ?>").find("span").html(DropItemHTML + dd);

});


   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
});


</script>
<? 
  // p( $params);
  
  $media1 = get_media( $params['cf_id'], 'table_content', $media_type='pictures');  
  $pics = $media1['pictures'];
  
  // p( $media1);
  
   ?>
<style>
.DropDown<? print $rand;
?>Gray {
 background-color: #EDE9E8;
}
.DropDown<? print $rand;
?>Colors {
 width:80px;
 
}
 .DropDown<? print $rand;
?>Colors em {
 background-color:#FFF;
 color:#333;
}
 .DropDown<? print $rand;
?>Alpha {
 background-color: #FFF;
 border: 1px solid #C9C4C0;
 cursor: pointer;
 margin-left:4px;
 display:block;
/* padding: 3px;

padding-left: 10px;*/ 
 
 
 z-index: 2;
}
</style>
<div class="dtitled left" style="margin-right:10px;">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><span class="DropDown<? print  $rand; ?>_title">
        <? if(trim($cf_conf['help']) != ''): ?>
        <? print $cf_conf['help'] ?>:
        <? else : ?>
        <? print $cf_conf['name'] ?>:
        <? endif; ?>
        </span></td>
      <td><div id="objcolors<? print  $rand; ?>" class="DropDown<? print  $rand; ?> DropDown<? print  $rand; ?>Alpha DropDown<? print  $rand; ?>Colors DropDown<? print  $rand; ?>Gray zebra drop_down_colors"> <span class="only_colors"></span>
          <br /><br>

          <ul style="height: 160px;overflow-x: hidden;overflow-y: scroll; display:none; background-color:#FFF; " class="drop_down_colors_non_selected">
            <?  $i=0;   foreach($pics as $pic): ?>
            <?php 
                 $orig =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], 'original');
				   $tn =  CI::model ( 'core' )->mediaGetThumbnailForMediaId($pic['id'], '90');
            //p($thumb);
			
			
			
			if($pic['media_name'] == ''){
			$pic['media_name'] = 	$pic['filename'];
			}
			
            ?>
            <li <? if($pic['media_name'] != '') : ?> title="<? print($tn) ;?>"   <? endif; ?>  <? if($i ==0): ?> class="active" <? endif; ?>  style="display:block;" cf_img_number=<? print($i) ;?> >
            <div style="clear: both;overflow: hidden">
              <!--  -->
            </div>
            
            
            <div style="background-image:none; background-repeat:no-repeat; background-position:center center; float:left; height:22px; display:block; margin-bottom:1px;" class="each_color" description="<? print addslashes($pic['media_description']) ;?>" >
            <s ><img height="22" width="60" src="<? print  $orig; ?>" cf_img_number=<? print($i) ;?> description="<? print addslashes($pic['media_description']) ;?>" title="<? print addslashes($pic['media_name']) ;?>" style="visibility:visible" />
             
            </s>
            <em></em>
            
            
            </div>
            
            <!-- <em><?php print addslashes($pic['media_name']); ?></em>-->
            </li>
            <?php $i++; endforeach; ?>
            <!-- <li title="purple" class="active"><s style="background:purple"></s><em>Purple</em><s style="background:red"></s><em>Red</em></li>
              <li title="green"><s style="background:green"></s><em>Green</em></li>
              <li title="yellow"><s style="background:yellow"></s><em>Green</em></li>
              <li title="red"><s style="background:red"></s><em>Green</em></li>-->
          </ul>
          <input name="custom_field_<? print $cf_conf['param'] ?>"   type="hidden" />
        </div></td>
    </tr>
  </table>
</div>
