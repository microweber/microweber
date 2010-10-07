jQuery("#search").jqGrid({        
   	url:'server.php?q=1',
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
   	pager: jQuery('#pagersr'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Search Example",
    editurl:"someurl.php"
});
$("#bsdata").click(function(){
	jQuery("#search").searchGrid(
		{sopt:['cn','bw','eq','ne','lt','gt','ew']}
	);
});