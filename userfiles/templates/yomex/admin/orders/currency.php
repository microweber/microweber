<script type="text/javascript">



function currency_code_delete($order_id){
		var del_order_url = '<?php print site_url('admin/orders/ajax_delete_currency') ?>';
		var where_to= confirm("Are you sure you want to delete this curency code? This cannot be undone!");
		if (where_to== true)
		{
		   var su=jQuery("#currency_table").delRowData($order_id);
			if(su) {
			$.post(del_order_url, { id: $order_id },
			  function(data){
				//alert("Data Loaded: " + data);  
			  });
			} 
		}
}




jQuery(document).ready(function(){ 
 var currency_tablelastsel2;
  var currency_tablelastsel3;
  var currency_tablelastsel4;

  
  jQuery("#currency_table").jqGrid({
    url:'<?php print site_url('admin/orders/ajax_json_get_currencies') ?>',
    datatype: 'xml',
    mtype: 'POST', 
	editable: true,
    colNames:[ 'currency_from', 'currency_to' , 'currency_rate', 'Edit'],
    colModel :[ 
	  {name:'currency_from', index:'currency_from', editable: true , width:120 }, 
	  {name:'currency_to', index:'currency_to', editable:true,  width:100},
	  {name:'currency_rate', index:'currency_rate', editable: true ,  hidden:false },  
 
 	  {name:'act',sortable:false,index:'act', search:false , width:30,sortable:false}  
    ],
	loadComplete: function(){
		var ids = jQuery("#currency_table").getDataIDs();
		for(var i=0;i<ids.length;i++){ 
			var cl = ids[i];
			be = "<input type='image' src='"+currency_code_edit_icon+"' value='Edit' onclick=jQuery('#currency_table').editRow("+cl+"); />"; 
			se = "<input type='image' src='"+currency_code_save_icon+"' value='Save'  onclick=jQuery('#currency_table').saveRow("+cl+"); />"; 
			de = "<input type='image' src='"+currency_code_delete_icon+"' onclick=javascript:currency_code_delete('"+cl+"'); />";  
			
			
		
			jQuery("#currency_table").setRowData(ids[i],{act:be+se+de})
		}	
	},
   
    pager: 'currency_table_pager12',  
    emptyrecords: "Nothing to display",
	viewrecords: true,
	editurl: "<?php print site_url('admin/orders/ajax_json_edit_currency') ?>",
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
  
  
 
 
 
 
   
  $("#currency_codes_add_controlls").html('<br><br><a class="btn green" id="badata"><span><img src="'+ currency_code_add_icon +'" alt=" " border="0">Add new currency</span></a>');       
$("#badata").click(function(){
	jQuery("#currency_table").editGridRow("new",{
	height:350,
	bSubmit: "Submit",
	bCancel: "Cancel",
	closeAfterAdd : true,
	reloadAfterSubmit:false
	});
});
  


}); 
</script>

<table id="currency_table" width="100%" class="scroll" cellpadding="0" cellspacing="0">
</table>
<div id="currency_table_pager12"></div>
<div id="currency_table_search" class="scroll" style="text-align:left;"></div>
<br />
<br />

<div id="currency_codes_add_controlls"></div>