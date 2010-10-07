jQuery("#gwidth").jqGrid({
   	url:'server.php?q=2',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90},
   		{name:'name',index:'name asc, invdate', width:100},
   		{name:'amount',index:'amount', width:80, align:"right"},
   		{name:'tax',index:'tax', width:80, align:"right"},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pgwidth'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Dynamic height/width Example",
	forceFit : true,
	onSortCol :function (nm,index) {
		if (nm=='invdate') {
			jQuery("#gwidth").setGridParam({sortname:'name'});
		}
	},
	onHeaderClick: function (status){
		alert("My status is now: "+ status);
	}
}).navGrid('#pgwidth',{edit:false,add:false,del:false});
jQuery("#snw").click(function (){
	var nw = parseInt(jQuery("#setwidth").val());
	if(isNaN(nw)) {
		alert("Value must be a number");
	} else if (nw<200 || nw > 700) {
		alert("Value can be between 200 and 700")
	} else {
		jQuery("#gwidth").setGridWidth(nw);
	}
});
jQuery("#snh").click(function (){
	var nh = jQuery("#setheight").val();
	jQuery("#gwidth").setGridHeight(nh);
});
