jQuery("#list13").jqGrid({        
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
   	pager: jQuery('#pager13'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	multiselect: true,
	multikey: "ctrlKey",
	caption: "Multiselect Example",
	editurl:"someurl.php"
});
jQuery("#cm1").click( function() {
	var s;
	s = jQuery("#list13").getGridParam('selarrrow');
	alert(s);
});
jQuery("#cm1s").click( function() {
	jQuery("#list13").setSelection("13");
});
jQuery("#list13").navGrid('#pager13',{add:false,edit:false,del:false},
	{}, // edit parameters
	{}, // add parameters
	{reloadAfterSubmit:false} //delete parameters
);