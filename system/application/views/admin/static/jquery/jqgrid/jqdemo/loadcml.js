jQuery("#list15").jqGrid({        
   	url:'server.php?q=2&nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90},
   		{name:'name',index:'name', width:100},
   		{name:'amount',index:'amount', width:80, align:"right"},
   		{name:'tax',index:'tax', width:80, align:"right"},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pager15'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	loadComplete: function(){
		var ret;
		alert("This function is executed immediately after\n data is loaded. We try to update data in row 13.");
		ret = jQuery("#list15").getRowData("13");
		if(ret.id == "13"){
			jQuery("#list15").setRowData(ret.id,{note:"<font color='red'>Row 13 is updated!</font>"})
		}
	}
});
jQuery("#sids").click( function() {
	alert("Id's of Grid: \n"+jQuery("#list15").getDataIDs());
});
