<?php $continents = $this->core_model->geoGetAllContinents(); ?>

<?php //amount:Fixed amount;percent:Percent

 $continents_drop_down_code = '';

 $counter = 0;

 foreach( $continents as $i){

 

 $continents_drop_down_code .= "$i:$i" ; 

 if($continents[$counter+1] != false){

 $continents_drop_down_code .=';';

 }

 $counter++;

 

 }

 ?>





<script type="text/javascript">







function shipping_costs_info_delete($order_id){

		var del_order_url = '<?php print site_url('admin/orders/ajax_delete_shipping_costs') ?>';

		var where_to= confirm("Are you sure you want to delete this shipping cost? This cannot be undone!");

		if (where_to== true)

		{

		   var su=jQuery("#shipping_costs_table").delRowData($order_id);

			if(su) {

			$.post(del_order_url, { id: $order_id },

			  function(data){

				//alert("Data Loaded: " + data);  

			  });

			} 

		}

}









jQuery(document).ready(function(){ 

 var shipping_costs_tablelastsel2;

  var shipping_costs_tablelastsel3;

  var shipping_costs_tablelastsel4;

 

  

  

  jQuery("#shipping_costs_table").jqGrid({

    url:'<?php print site_url('admin/orders/ajax_json_get_shipping_costs') ?>',

    datatype: 'xml',

    mtype: 'POST', 

	editable: true,

    colNames:[ 'Created on', 'Is active' , 'Ship to continent','Shiping Cost per item (EUR)','Edit'],

    colModel :[ 

	  {name:'created_on', index:'created_on', editable: false , width:120 }, 

	  {name:'is_active', index:'is_active', editable:true,  width:100, edittype:"select",editoptions:{value:"y:Yes;n:No"}},

	  {name:'ship_to_continent', index:'ship_to_continent', editable: true ,  hidden:false , edittype:"select",editoptions:{value:"<?php print $continents_drop_down_code ?>"}},  

      {name:'shiping_cost_per_item', index:'shiping_cost_per_item', editable: true , width:130 }, 

 	  {name:'act',sortable:false,index:'act', search:false , width:30,sortable:false}  

    ],

	loadComplete: function(){

		var ids = jQuery("#shipping_costs_table").getDataIDs();

		for(var i=0;i<ids.length;i++){ 

			var cl = ids[i];

			be = "<input type='image' src='"+shipping_costs_edit_icon+"' value='Edit' onclick=jQuery('#shipping_costs_table').editRow("+cl+"); />"; 

			se = "<input type='image' src='"+shipping_costs_save_icon+"' value='Save'  onclick=jQuery('#shipping_costs_table').saveRow("+cl+"); />"; 

			de = "<input  type='image' src='"+shipping_costs_delete_icon+"' value='Del'   onclick=javascript:shipping_costs_info_delete('"+cl+"'); />"; 

			jQuery("#shipping_costs_table").setRowData(ids[i],{act:be+se+de})

		}	

	},

   

    pager: 'shipping_costs_table_pager12',  

    emptyrecords: "Nothing to display",

	viewrecords: true,

	editurl: "<?php print site_url('admin/orders/ajax_json_edit_shipping_costs') ?>",

    rowNum:100,

    rowList:[100,200,300],

    sortname: 'created_on',

    sortorder: 'desc',

    viewrecords: true,

    caption: 'Shipping cost',

	 reloadAfterEdit:true,

	 reloadAfterSubmit:true,

	autowidth: true, 

	viewrecords: true,

	footerrow : true,

	pgbuttons: true,

	pginput: true,

	multipleSearch:true,

	 

	height: '100%'



  }).filterToolbar({autosearch:true});     

  

  

  

    $("#shipping_costs_add_conrolls").html('<br><br><a class="btn green" id="badata"><span><img src="'+ shipping_costs_add_icon +'" alt=" " border="0">Add new shipping cost info</span></a>');

	

 





$("#badata").click(function(){

	jQuery("#shipping_costs_table").editGridRow("new",{

	height:350,

	bSubmit: "Submit",

	bCancel: "Cancel",

	closeAfterAdd : true,

	reloadAfterSubmit:true

	});

});

  





}); 

</script>



<table id="shipping_costs_table" width="100%" class="scroll" cellpadding="0" cellspacing="0">

</table>

<div id="shipping_costs_table_pager12"></div>

<div id="shipping_costs_table_search" class="scroll" style="text-align:left;"></div>

<div id="shipping_costs_add_conrolls"></div>

