jQuery("#list6").jqGrid({        
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
   	//rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pager6'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	onSortCol: function(name,index){ alert("Column Name: "+name+" Column Index: "+index);},
	ondblClickRow: function(id){ alert("You double click row with id: "+id);},
	caption:" Get Methods",
	height: 200
});
jQuery("#list6").navGrid("#pager6",{edit:false,add:false,del:false});
