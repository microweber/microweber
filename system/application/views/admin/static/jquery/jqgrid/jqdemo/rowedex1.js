jQuery("#rowed1").jqGrid({
   	url:'server.php?q=2',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
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
   	pager: jQuery('#prowed1'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	editurl: "server.php",
	caption: "Basic Example"
}).navGrid("#prowed1",{edit:false,add:false,del:false});

jQuery("#ed1").click( function() {
	jQuery("#rowed1").editRow("13");
	this.disabled = 'true';
	jQuery("#sved1,#cned1").attr("disabled",false);
});
jQuery("#sved1").click( function() {
	jQuery("#rowed1").saveRow("13");
	jQuery("#sved1,#cned1").attr("disabled",true);
	jQuery("#ed1").attr("disabled",false);
});
jQuery("#cned1").click( function() {
	jQuery("#rowed1").restoreRow("13");
	jQuery("#sved1,#cned1").attr("disabled",true);
	jQuery("#ed1").attr("disabled",false);
});
