jQuery("#custbut").jqGrid({        
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
   	rowNum:5,
   	rowList:[5,10,20],
   	imgpath: gridimgpath,
   	pager: jQuery('#pcustbut'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    height: '100%',
    caption:"Custom Buttons and forms"
}).navGrid('#pcustbut',{edit:false,add:false,del:false})
.navButtonAdd('#pcustbut',{caption:"Edit",
	onClickButton:function(){
		var gsr = jQuery("#custbut").getGridParam('selrow');
		if(gsr){
			jQuery("#custbut").GridToForm(gsr,"#order");
		} else {
			alert("Please select Row")
		}							
} 
});
jQuery("#savedata").click(function(){
	var invid = jQuery("#invid").val();
	if(invid) {
		jQuery("#custbut").FormToGrid(invid,"#order");
	}
	
});
