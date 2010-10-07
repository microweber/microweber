jQuery("#list8").jqGrid({        
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
   	pager: jQuery('#pager8'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc"
});
jQuery("#e1").click( function() {
	var s;
	s = jQuery("#list8").toXmlData();
	alert(s);
});
jQuery("#e2").click( function() {
	var s;
	s = jQuery("#list8").toJSONData();
	alert(s);
});
