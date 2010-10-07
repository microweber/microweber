jQuery("#setcols").jqGrid({
   	url:'server.php?q=2',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55,hidedlg:true},
   		{name:'invdate',index:'invdate', width:90,editable:true},
   		{name:'name',index:'name asc, invdate', width:100},
   		{name:'amount',index:'amount', width:80, align:"right",editable:true,editrules:{number:true}},
   		{name:'tax',index:'tax', width:80, align:"right",editable:true,editrules:{number:true}},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#psetcols'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc"
});
jQuery("#setcols").navGrid('#pgwidth',{edit:false,add:false,del:false});

jQuery("#vcol").click(function (){
	jQuery("#setcols").setColumns();
});