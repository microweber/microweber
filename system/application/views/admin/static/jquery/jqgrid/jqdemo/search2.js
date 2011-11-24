jQuery("#s2list").jqGrid({
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
   	pager: jQuery('#s2pager'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Multiple Form Search Example",
    onHeaderClick: function (stat) {
    	if(stat == 'visible' ){
    		jQuery("#filter").css("display","none");
    	}
    }	
})
.navGrid('#s2pager',{edit:false,add:false,del:false,search:false,refresh:false})
.navButtonAdd("#s2pager",{caption:"Search",title:"Toggle Search",buttonimg:gridimgpath+'/find.gif',
	onClickButton:function(){ 
		if(jQuery("#filter").css("display")=="none") {
			jQuery(".HeaderButton","#gbox_s2list").trigger("click");
			jQuery("#filter").show();
		}
	} 
})
.navButtonAdd("#s2pager",{caption:"Clear",title:"Clear Search",buttonimg:gridimgpath+'/refresh.gif',
	onClickButton:function(){
		var stat = jQuery("#s2list").getGridParam('search');
		if(stat) {
			var cs = jQuery("#filter")[0];
			cs.clearSearch();
		}
	} 
});
jQuery("#filter").filterGrid("s2list",
	{
		gridModel:true,
		gridNames:true,
		formtype:"vertical",
		enableSearch:true,
		enableClear:false,
		autosearch: false,
		afterSearch : function() {
			jQuery(".HeaderButton","#gbox_s2list").trigger("click");
			jQuery("#filter").css("display","none");
		}
	}
);
jQuery("#sg_invdate","#filter").datepicker({dateFormat:"yy-mm-dd"});