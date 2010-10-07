var myarr =
[
	{name:'id',index:'id', width:55},
	{name:'invdate',index:'invdate', width:90},
	{name:'name',index:'name asc, invdate', width:100},
	{name:'amount',index:'amount', width:80, align:"right"},
	{name:'tax',index:'tax', width:80, align:"right"},		
	{name:'total',index:'total', width:80,align:"right"},		
	{name:'note',index:'note', width:150, sortable:false}		
];
jQuery("#list18").jqGrid({        
   	url:'server.php?q=2',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:myarr,
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pager18'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	beforeInit: function() {if(1==1) myarr[4].hidden = true; }
});
