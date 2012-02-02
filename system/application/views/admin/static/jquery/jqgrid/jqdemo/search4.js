jQuery("#s4list").jqGrid({
   	url:'search_adv.php?q=1',
	datatype: "json",
	width: 700,
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:65, searchoptions:{sopt:['eq','ne','lt','le','gt','ge']}},
   		{name:'invdate',index:'invdate', width:90, searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});} }},
   		{name:'name',index:'name', width:100},
   		{name:'amount',index:'amount', width:80, align:"right",searchoptions:{sopt:['eq','ne','lt','le','gt','ge']}},
   		{name:'tax',index:'tax', width:80, align:"right", stype:'select', editoptions:{value:":All;0.00:0.00;12:12.00;20:20.00;40:40.00;60:60.00;120:120.00"}},
   		{name:'total',index:'total', width:80,align:"right",searchoptions:{sopt:['eq','ne','lt','le','gt','ge']}},
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	mtype: "POST",
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: '#s4pager',
   	sortname: 'id',
    viewrecords: true,
	rownumbers: true,
	gridview : true,
    sortorder: "desc",
    caption:"Advanced Search Example"
}).navGrid('#s3pager',
{
	edit:false,add:false,del:false,search:true,refresh:true
},
{}, // edit options
{}, // add options
{}, //del options
{multipleSearch:true} // search options
);
