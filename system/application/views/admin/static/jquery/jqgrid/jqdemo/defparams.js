jQuery("#params").jqGrid({        
   	url:'server.php',
	datatype: "xml",
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
   	pager: jQuery('#pparams'),
   	sortname: 'id',
	mtype: "POST",
	postData:{q:1},
    viewrecords: true,
    sortorder: "desc",
    caption:"New Methods"
}).navGrid('#pparams',{edit:false,add:false,del:false});

jQuery("#pp1").click( function() {
	$.extend($.jgrid.defaults,{recordtext: "Record(s)",loadtext: "Processing"});
	alert("New parameters are set - reopen the grid");
});
