if (window.console != undefined) {
	console.log('Microweber Javascript Edit Page Framework Loaded');
}

$.dataFind = function(data, findwhat){
       var div = document.createElement('div');
       div.innerHTML = data;
       div.className = 'xhidden';
       document.body.appendChild(div);
       setTimeout(function(){$(div).destroy()}, 5);
       return $(div).find(findwhat)
    }




function init_edits(){
	
	$(".editblock").live("click", function() {



 $(this).addClass("mw_edited");



   	 var id = $(this).attr("id");
		 var rel = $(this).attr("rel");
		 
		 
		 var is_post = $(this).attr("post");

		 var field = $(this).attr("field");
		 if(field == undefined){
			 field = '';
		 }



		if( $(this).hasClass('editblock')){
			$tag = 'editblock'
		}



		if( $(this).hasClass('edit')){
			$tag = 'edit'
		}


		 if($tag == undefined){
			 $tag = '';
		 }

if (load_editblock_history !=undefined ) {
  $page_json = get_page_json();
  if(is_post != undefined){
	  $id123 = is_post;
	  rel = 'post';
  } else {
	 $id123 =$page_json.page.id  ;
  }

  load_editblock_history(id, rel,id123 ,$tag, field) ;
  }
});
$(".edit").live("click", function() {
 $(this).addClass("mw_edited");

   	 var id = $(this).attr("id");
		 var rel = $(this).attr("rel");
		  var is_post = $(this).attr("post");

		 var field = $(this).attr("field");
		 if(field == undefined){
			 field = '';
		 }



		if( $(this).hasClass('editblock')){
			$tag = 'editblock'
		}



		if( $(this).hasClass('edit')){
			$tag = 'edit'
		}


		 if($tag == undefined){
			 $tag = '';
		 }

if (load_editblock_history !=undefined ) {
  $page_json = get_page_json();
  
   $page_json = get_page_json();
  if(is_post != undefined){
	  $id123 = is_post;
	  rel = 'post';
  } else {
	 $id123 =$page_json.page.id  ;
  }

  load_editblock_history(id, rel, $id123,$tag, field) ;
  }
});
}


$(document).ready(function() {
	
	
 
	if(typeof window.parent.mw_edit_init == 'function'){
	window.parent.mw_edit_init(window.location.href);
	}

	
	$("[module]").each(function(){
		var val = $(this).attr("module");
		var rel = $(this).attr("rel");
		
		 v1 = encodeURIComponent(val);
			   $url = '<? print site_url("api/module") ?>/admin:true/no_config:true/rel:'+rel+'/?module_name:'+v1;
			//  alert(v1+$url); 
			   // callIframe
		//	   $(this).append("<input type='button' value='aaa' name='aaa'  onclick='javascript:call_edit_module_iframe(\""+$url+"\") ;' />");

			   
 
		
		
		
		
		
		
	});
	
	
	

 $("edit").each(function(){
                 $(this).attr("id", "edit_" + mw.id());
               })


                    init_edits();
                 




               
/*   $.post('<? print site_url("admin/toolbar") ?>', function(data){

      var toolbar = $.dataFind(data, "#mw_toolbar");
      $(document.body).prepend(toolbar);
  });*/




//$(".module").append("<div class='mwedit'></div>"); //throws an error in ie

    $(".module").hover(function(){
        $(this).find(".mwedit").show()
    }, function(){
         $(this).find(".mwedit").hide()
    });



    $(".module").each(function(){
    	
    	
    	$(this).attr("id", "module_id_" + mw.id());
    	
    	
    	
    })


	$(".module").live("click", function(event) {
		   
		 var edit = $(this).attr("edit");
		 var rel = $(this).attr("rel");
		 


		  
		 
		 
		 if(edit ==undefined){
			 
		 } else {


         if($("#enable_browse").is(":checked")){
           	mw.prevent(event);



		   
		   if (load_editblock_history !=undefined ) {

			  var parent_rel = $(this).parents(".editblock:first").attr("rel");
			  var parent_id = $(this).parents(".editblock:first").attr("id"); 
			   

			   $page_json = get_page_json();
			   
			   load_editblock_history(parent_id, parent_rel, $page_json.page.id, 'editblock') ;
			   
			   

			   }
		  

		  // alert(edit);

		   var id = $(this).attr("id");
		   
		   var no_admin = $(this).attr("no_admin");

		   try {
			   $(this).removeAttr('contentEditable');
			 } catch (e) {
				 $(this).attr('contentEditable', false);
			 }


			 if(no_admin == undefined){
		   $url = '<? print site_url("api/module") ?>/admin:true/base64:'+edit;
		   // alert($url);
		   // callIframe

		   call_edit_module_iframe($url, id) ;
			 }

           }
		   
		 }

		});




$(window).load(function(){
        var editblock_ids = "";

        var editblocklength = $(".editblock").length-1;
        $(".editblock").each(function(i){
           var id = $(this).attr("id");

             if(editblock_ids==""){
             editblock_ids = "#" +id;
               }



           if(editblock_ids!=""){
            editblock_ids = editblock_ids + ", #" +id;
           }


        });

        
 

        $(".module").each(function(){
           if($(this).find(".module_handle").length==0){
             var bar = ''
             +'<div class="module_edit_bar">'
             +'<span class="module_edit_bar_handle">&nbsp;</span>'
             +'<span class="module_edit_bar_delete">Delete</span>'
             +"</div>";

             $(this).append(bar)
           }
        });


        $(".module_edit_bar_delete").click(function(event){
            mw.prevent(event);
            var parent = $(this).parents(".module:first");
            mw.modal.confirm({
              html:"Are you sure you want to delete this module?",
              yes:function(){
                parent.remove();
             //   mw.modal.alert("Module deleted");
              }
            })
        });




        $(".editblock").each(function(){
          $(this).sortable({
             connectWith:editblock_ids,
             items:".module",
             cancel: ".module .module",
             handle:".module_edit_bar_handle",
             receive:function(event, ui){

               var mw1 = ui.item.find("textarea").val();
               $(".bar_module").not("#module_bar .bar_module").replaceWith(mw1);

                  $(".editblock").each(function(){
                      var id =  $(this).attr("id");
                      save_editblock(id);
                  })
              }
              // handle:".mwedit"
          })
          //$(this).disableSelection();

        });
        $(".edit").droppable({
           drop: function(event, ui) {
              var mw1 = ui.draggable.find("textarea").val();
              $(".to_here").after(mw1);
              $(".editblock").each(function(){
                      var id =  $(this).attr("id");
                      save_editblock(id);
                      //mw.saveALL();
                  })
           }
        });
        $(".edit *").hover(function(event){
          mw.prevent(event)
          $(".edit *").removeClass("to_here");
          $(this).addClass("to_here");
        }, function(){

        })



});


       /* $(".editblock").sortable({
            connectWith:".editblock",
           items:".module",
            receive:function(){
                $(".editblock").each(function(){
                    var id =  $(this).attr("id");
                    save_editblock(id);
                })
            }
            // handle:".mwedit"
        }).disableSelection(); */
	}); //end doc ready

var $curent_edit_element_id

function load_editblock_history($id,$rel, $page_id, $tag, $field) {
	
	 data1 = {}
	   data1.module = 'admin/mics/edit_block_history';
	    data1.id = $id;
	    if($rel != undefined){
	    data1.rel = $rel;
	    }
	    if($page_id != undefined){
	    data1.page_id = $page_id;
	    }
	    
	    if($tag != undefined){
		    data1.tag = $tag;
		    }
	    
	    if($field != undefined){
		    data1.field = $field;
		    }
	    
	   $.ajax({
	  url: '<? print site_url('api/module') ?>',
	   type: "POST",
	      data: data1,

	      async:true,

	  success: function(resp) {

	   $('#mw_block_history').html(resp);

	 

	  }
	    });
		
	
	
	
	
	
}

function load_field_from_history_file($id, $base64fle){


	$.ajax({
		  type: 'POST',
		  url: '<? print site_url("api/content/load_history_file") ?>',
		  data: { history_file: $base64fle },
		  success: function(data) {
			   $("#"+$id).html(data);
		  }
		})
	
	
}

function load_editblock($id, $history_file) {
	$page_json = get_page_json();
	
	$rel = $(".editblock#"+$id).attr('rel');
	
	 if ( typeof $history_file != 'undefined' ) {
		 $history_file = $history_file;
	 } else {
		 $history_file = false; 
	 }
	
	
	$.ajax({
		  type: 'POST',
		  url: '<? print site_url("api/content/load_block") ?>',
		  data: { id: $id, rel:$rel , page_id: $page_json.page.id, history_file:$history_file },
		  success: function(data) {
			  // alert(data);
			   $(".editblock#"+$id).html(data);
			   
			 
			   
			   if ( typeof load_editblock_history != 'undefined' ) {
			   load_editblock_history($id, $rel, $page_json.page.id) ;
			   }
			   
			   
			   
			   
               $(".module").each(function(){
                 $(this).attr("id", "module_id_" + mw.id());
               })
       //        init_edits();


		  }
		})
		
		
		
}

function get_page_json() {
	if ( typeof get_page_json.page == 'undefined' ) {
    	$page_data = window.location.href ;
    	$.ajax({
  		  type: 'POST',
  		  url: $page_data,
  		  data: { format: 'json'},
           async:false,
           dataType: "json", 
  		  success: function(data) {
    		get_page_json.page = data;

  		  }
  		})
    }

    
return get_page_json.page;
	
}	
function save_editblock($id) {
	// alert($id);
    $(".module_edit_bar").remove();
	$page_json = get_page_json();
	//alert($page_json);




	

	
	var clone =  $(".editblock#"+$id).find(".mw_save").clone(true);
	$(".editblock#"+$id).find(".mw_save").remove();
	$test = $(".editblock#"+$id).html();
	
	$rel = $(".editblock#"+$id).attr('rel');
	
	$(".editblock#"+$id).append(clone)
	
	
	
	

	$.ajax({
		  type: 'POST',
		  url: '<? print site_url("api/content/save_block") ?>',
		  data: { id: $id, html:$test , rel:$rel ,page_id: $page_json.page.id},
          async:false,
		  success: function(data) {
			 // alert(data);
			  load_editblock($id);

		  }
		})
//init_edits();
}

function call_edit_module_iframe(url, id) {
 if(id){
	url = url + 'element_id:'+id;
	$curent_edit_element_id=id;
 }
 
 
		var call_iframe = mw.modal.iframe({src:url, width:700, overlay:true, height:500, id:"module_edit_iframe"});

}


function update_module_element($new_value) {
	
	$temp = $('#'+$curent_edit_element_id).parents(".editblock") .attr("id")
	//alert($temp);
	  $('#'+$curent_edit_element_id).attr("base64_array", $new_value);
	save_editblock($temp);

	  mw.modal.close();
	  
}

mw.saveALL = function(){
	
	nic_save_all(function(){
		
		$(".editblock").each(function(){
	        var id = $(this).attr("id");
	        save_editblock(id);
	    });
		
	//	init_edits();
	});
	
	
    




}