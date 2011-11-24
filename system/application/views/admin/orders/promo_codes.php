<script type="text/javascript">



function promo_code_delete($order_id){
		var del_order_url = '<?php print site_url('admin/orders/ajax_delete_promo_code') ?>';
		var where_to= confirm("Are you sure you want to delete this promo code? This cannot be undone!");
		if (where_to== true)
		{
		   var su=jQuery("#promo_table").delRowData($order_id);
			if(su) {
			$.post(del_order_url, { id: $order_id },
			  function(data){
				//alert("Data Loaded: " + data);  
			  });
			} 
		}
}




jQuery(document).ready(function(){ 
 var promo_tablelastsel2;
  var promo_tablelastsel3;
  var promo_tablelastsel4;

  
  jQuery("#promo_table").jqGrid({
    url:'<?php print site_url('admin/orders/ajax_json_get_promo_codes') ?>',
    datatype: 'xml',
    mtype: 'POST', 
	editable: true,
    colNames:[ 'Promo Code', 'Created on' , 'Discount','Discount type','Description', 'Edit'],
    colModel :[ 
	  {name:'promo_code', index:'promo_code', editable: true , width:120 }, 
	  {name:'created_on', index:'created_on', editable:false,  width:100},
	  {name:'amount_modifier', index:'amount_modifier', editable: true ,  hidden:false },  
      {name:'amount_modifier_type', index:'amount_modifier_type', editable: true , width:55, edittype:"select",editoptions:{value:"amount:Fixed amount;percent:Percent"} }, 
      {name:'description', index:'description', editable:true, edittype:'textarea', editoptions:{rows:"2",cols:"20"}, width:90}, 
 	  {name:'act',sortable:false,index:'act', search:false , width:30,sortable:false}  
    ],
	loadComplete: function(){
		var ids = jQuery("#promo_table").getDataIDs();
		for(var i=0;i<ids.length;i++){ 
			var cl = ids[i];
			be = "<input type='image' src='"+promo_code_edit_icon+"' value='Edit' onclick=jQuery('#promo_table').editRow("+cl+"); />"; 
			se = "<input type='image' src='"+promo_code_save_icon+"' value='Save'  onclick=jQuery('#promo_table').saveRow("+cl+"); />"; 
			de = "<input type='image' src='"+promo_code_delete_icon+"' onclick=javascript:promo_code_delete('"+cl+"'); />";  
			
			
		
			jQuery("#promo_table").setRowData(ids[i],{act:be+se+de})
		}
	},
   
    pager: 'promo_table_pager12',  
    emptyrecords: "Nothing to display",
	viewrecords: true,
	editurl: "<?php print site_url('admin/orders/ajax_json_edit_promo_code') ?>",
    rowNum:100,
    rowList:[100,200,300],
    sortname: 'created_on',
    sortorder: 'desc',
    viewrecords: true,
    caption: 'Promo codes',
	 
	 
	autowidth: true, 
	viewrecords: true,
	footerrow : true,
	pgbuttons: true,
	pginput: true,
	multipleSearch:true,
	 
	height: '100%'

  }).filterToolbar({autosearch:true});     
  
  
 
 
 
 

  $("#promo_codes_add_controlls").html('<br><br><a class="btn green" id="badata"><span><img src="'+ promo_code_add_icon +'" alt=" " border="0">Add new promo code</span></a>');
$("#badata").click(function(){
	jQuery("#promo_table").editGridRow("new",{
	height:350,
	bSubmit: "Submit",
	bCancel: "Cancel",
	closeAfterAdd : true,
	reloadAfterSubmit:false
	});
});
  


}); 
</script>

<table id="promo_table" width="100%" class="scroll" cellpadding="0" cellspacing="0">
</table>
<div id="promo_table_pager12"></div>
<div id="promo_table_search" class="scroll" style="text-align:left;"></div>
<div id="promo_codes_add_controlls"></div>