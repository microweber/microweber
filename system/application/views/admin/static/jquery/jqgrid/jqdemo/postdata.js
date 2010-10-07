jQuery("#pdata").jqGrid({
   	url:'server.php',
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
   	pager: jQuery('#ppdata'),
   	sortname: 'id',
	mtype: "POST",
	postData:{q:1},
    viewrecords: true,
    sortorder: "desc",
    caption:"New Methods",
    multiselect: true
}).navGrid('#ppdata',{edit:false,add:false,del:false});

jQuery("#ps1").click( function() {
	$("#pdata").setPostData({q:1,param1:"p1"});
	$("#pdata").trigger("reloadGrid");
});

jQuery("#ps2").click( function() {
	var pd =$("#pdata").getPostData();
	var r ="";
	$.each(pd,function(i){
		r += i+": "+pd[i]+",";
	})
	$("#postdata").html(r).css("background-color","yellow");
});

jQuery("#ps3").click( function() {
	$("#pdata").appendPostData({param2:"p2"});
	$("#pdata").trigger("reloadGrid");
	var pd =$("#pdata").getPostData();
	var r ="";
	$.each(pd,function(i){
		r += i+": "+pd[i]+",";
	})
	$("#postdata").html(r).css("background-color","yellow");
});
jQuery("#ps4").click( function() {
	$("#pdata").setPostDataItem("param2","I'w new value");
	$("#pdata").trigger("reloadGrid");
	var pd =$("#pdata").getPostData();
	var r ="";
	$.each(pd,function(i){
		r += i+": "+pd[i]+",";
	})
	$("#postdata").html(r).css("background-color","yellow");
});
jQuery("#ps5").click( function() {
	alert( "rows : "+$("#pdata").getPostDataItem("rows"));
});
jQuery("#ps6").click( function() {
	$("#pdata").removePostDataItem("param1");
	$("#pdata").trigger("reloadGrid");
	var pd =$("#pdata").getPostData();
	var r ="";
	$.each(pd,function(i){
		r += i+": "+pd[i]+",";
	})
	$("#postdata").html(r).css("background-color","yellow");
});
