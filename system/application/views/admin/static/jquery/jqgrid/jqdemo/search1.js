jQuery("#s1list").jqGrid({
   	url:'search.php?q=1',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:65},
   		{name:'invdate',index:'invdate', width:90},
   		{name:'name',index:'name', width:100},
   		{name:'amount',index:'amount', width:80, align:"right"},
   		{name:'tax',index:'tax', width:80, align:"right", edittype:'select', editoptions:{value:":All;0.00:0.00;12:12.00;20:20.00;40:40.00;60:60.00;120:120.00"}},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	mtype: "POST",
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#s1pager'),
   	sortname: 'id',
    viewrecords: true,
	toolbar : [true,"top"],
    sortorder: "desc",
    caption:"Multiple Toolbar Search Example"
});
jQuery("#t_s1list").height(25).hide().filterGrid("s1list",{gridModel:true,gridToolbar:true});
jQuery("#sg_invdate").datepicker({dateFormat:"yy-mm-dd"});

jQuery("#s1list").navGrid('#s1pager',{edit:false,add:false,del:false,search:false,refresh:false})
.navButtonAdd("#s1pager",{caption:"Search",title:"Toggle Search",buttonimg:gridimgpath+'/find.gif',
	onClickButton:function(){ 
		if(jQuery("#t_s1list").css("display")=="none") {
			jQuery("#t_s1list").css("display","");
		} else {
			jQuery("#t_s1list").css("display","none");
		}
		
	} 
});
