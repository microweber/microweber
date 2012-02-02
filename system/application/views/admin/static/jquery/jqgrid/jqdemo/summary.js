jQuery("#lists2").jqGrid({
   	url:'server.php?q=2',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90},
   		{name:'name',index:'name asc, invdate', width:100},
   		{name:'amount',index:'amount', width:80, align:"right", formatter: 'number'},
   		{name:'tax',index:'tax', width:80, align:"right", formatter: 'number'},		
   		{name:'total',index:'total', width:80,align:"right", formatter: 'number'},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: '#pagers2',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"JSON Example",
	footerrow : true,
	userDataOnFooter : true,
	altRows : true
}).navGrid('#pagers2',{edit:false,add:false,del:false});
