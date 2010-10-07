jQuery("#list16").jqGrid({
   	url:'server.php?q=2&nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55, resizable:false},
   		{name:'invdate',index:'invdate', width:90,resizable:false},
   		{name:'name',index:'name', width:100},
   		{name:'amount',index:'amount', width:80, align:"right",resizable:false},
   		{name:'tax',index:'tax', width:80, align:"right",resizable:false},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false,resizable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pager16'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	caption: "Resizable columns"
}).navGrid("#pager16",{edit:false,add:false,del:false});
