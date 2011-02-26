
<div id="edit_bar">
    <a href="#" id="edit_bar_edit">Edit</a>
    <a href="#" id="edit_bar_save">save</a>
</div>

<div id="mw_toolbar">
<script type="text/javascript" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/nicedit.js"></script>
  <div id="drag_mw_toolbar"></div>
  <div id="mw_toolbar_content">
  <div id="mw_editbar"></div>
  
    <div class="modules_list">




      <mw module="posts/list" />



    </div>
    <script>


  mw.color = function(){
    return 'rgb(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ')';
  }


$(document).ready(function(){
  $(".module").each(function(){
    $(this).css("background", mw.color());
  });

  $(".modules_list").sortable({
    connectWith:'editblock'
  });



  $(".editblock").each(function (index, domEle) {
      // domEle == this
      $editblock_id = $(domEle).attr("id");
      $(domEle).find(".mw_save").remove();
      $(domEle).append('<a class="mw_save" href="javascript:save_editblock(\''+$editblock_id+'\');">save</a>');
   /*   if ($(this).is("#stop")) {
        $("span").text("Stopped at div index #" + index);
        return false;
      }*/
  });
   
   


   
   

});











$(document).ready(function(){



    $(".edit").each(function(){
        if($(this).attr("id")==""){
           $(this).attr("id", "edit_" + mw.id());
        }

    });


    $(".edit").click(function(){

		old_edit = nicEditors.findEditor(id);
        try{
        //  old_edit.removeInstance(id);
		  
        }
        catch(err){}




        var id = $(this).attr("id");


 
        myNicEditor = new nicEditor({
          fullPanel : true,
          iconsPath : '<?php print( ADMIN_STATIC_FILES_URL);  ?>js/nicEditorIcons.gif',
          onSave:function(content, id, instance){



var nic_obj = "";
var attrs = $("#"+id)[0].attributes;
for(var i=0;i<attrs.length;i++) {
   if(nic_obj==""){
      nic_obj = nic_obj + attrs[i].nodeName + ":'" + attrs[i].nodeValue + "'";
   }
   if(nic_obj!=""){
     nic_obj =  nic_obj + "," + attrs[i].nodeName + ":'" + attrs[i].nodeValue + "'";
   }
}

var nic_obj = eval("({" + nic_obj + "})");


var obj = {
    attributes:nic_obj,
    html : content
}



$.post("<?php print site_url('api/content/save_field');  ?>", obj, function(){

});







          }
        }).panelInstance('mw_editbar');

        //myNicEditor.setPanel('mw_editbar');
        myNicEditor.addInstance(id);



      });
});


</script>
    <style>
    editblock{
      min-height: 150px;
      display: block;
    }
    #main_side{
      min-height:300px;
    }

    #edit_bar{
      position: absolute;
      padding: 5px;
      background: #719BC6;
      border: 1px solid #303854;
    }


    .ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 150px !important; display: block;background:#ccc; }
	.ui-sortable-placeholder * { visibility: hidden; }

#mw_toolbar{
  background: white;
  position: fixed;
  top: 0px;
  z-index: 100;
  width: 100%;
  -moz-box-shadow: 0 0 5px #888;
-webkit-box-shadow: 0 0 5px#888;
box-shadow: 0 0 5px #888;
opacity:.8;
filter:alpha(opacity=80);
}
#mw_toolbar:hover{
opacity:1;
filter:alpha(opacity=100);
}


#mw_toolbar_content{
  padding: 10px;
}


     .create_module{
       display: block;
       width: 60px;
       height: 30px;
       background: #2F8AB7;
       text-align: center;
       -moz-border-radius:6px;
       -webkit-border-radius:6px;
       color:white;
       padding: 15px 0;
       float: left;
       margin: 0 5px;
     }



</style>



  </div>
</div>





