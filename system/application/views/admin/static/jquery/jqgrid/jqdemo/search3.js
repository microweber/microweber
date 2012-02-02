var mygrid = jQuery("#s3list").jqGrid({
   	url:'search.php?q=1',
	datatype: "json",
	width: 700,
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:65},
   		{name:'invdate',index:'invdate', width:90,searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});} }},
   		{name:'name',index:'name', width:100},
   		{name:'amount',index:'amount', width:80, align:"right"},
   		{name:'tax',index:'tax', width:80, align:"right", stype:'select', editoptions:{value:":All;0.00:0.00;12:12.00;20:20.00;40:40.00;60:60.00;120:120.00"}},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	mtype: "POST",
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: '#s3pager',
   	sortname: 'id',
    viewrecords: true,
	rownumbers: true,
    sortorder: "desc",
	gridview : true,
    caption:"Toolbar Search Example"
})
.navGrid('#s3pager',{edit:false,add:false,del:false,search:false,refresh:false})
.navButtonAdd("#s3pager",{caption:"Toggle",title:"Toggle Search Toolbar", buttonicon :'ui-icon-pin-s',
	onClickButton:function(){
		mygrid[0].toggleToolbar()
	} 
})
.navButtonAdd("#s3pager",{caption:"Clear",title:"Clear Search",buttonicon :'ui-icon-refresh',
	onClickButton:function(){
		mygrid[0].clearToolbar()
	} 
});
mygrid.filterToolbar();
