jQuery("#rowed2").jqGrid({
   	url:'server.php?q=3',
	datatype: "json",
   	colNames:['Actions','Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
		{name:'act',index:'act', width:75,sortable:false},
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90, editable:true},
   		{name:'name',index:'name', width:100,editable:true},
   		{name:'amount',index:'amount', width:80, align:"right",editable:true},
   		{name:'tax',index:'tax', width:80, align:"right",editable:true},		
   		{name:'total',index:'total', width:80,align:"right",editable:true},		
   		{name:'note',index:'note', width:150, sortable:false,editable:true}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#prowed2'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	gridComplete: function(){
		var ids = jQuery("#rowed2").getDataIDs();
		for(var i=0;i<ids.length;i++){
			var cl = ids[i];
			be = "<input style='height:22px;width:20px;' type='button' value='E' onclick=\"jQuery('#rowed2').editRow('"+cl+"');\"  />"; 
			se = "<input style='height:22px;width:20px;' type='button' value='S' onclick=\"jQuery('#rowed2').saveRow('"+cl+"');\"  />"; 
			ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#rowed2').restoreRow('"+cl+"');\" />"; 
			jQuery("#rowed2").setRowData(ids[i],{act:be+se+ce});
		}	
	},
	editurl: "server.php",
    caption:"Custom edit "
}).navGrid("#prowed2",{edit:false,add:false,del:false});
