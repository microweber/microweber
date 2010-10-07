var lastsel3;
jQuery("#rowed6").jqGrid({        
	datatype: "local",
	height: 250,
   	colNames:['ID Number','Last Sales','Name', 'Stock', 'Ship via','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:90, sorttype:"int", editable: true},
		{name:'sdate',index:'sdate',width:90, editable:true, sorttype:"date"},
   		{name:'name',index:'name', width:150,editable: true,editoptions:{size:"20",maxlength:"30"}},
   		{name:'stock',index:'stock', width:60, editable: true,edittype:"checkbox",editoptions: {value:"Yes:No"}},
   		{name:'ship',index:'ship', width:90, editable: true,edittype:"select",editoptions:{value:"FE:FedEx;IN:InTime;TN:TNT;AR:ARAMEX"}},
   		{name:'note',index:'note', width:200, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}}		
   	],
   	imgpath: gridimgpath,
	onSelectRow: function(id){
		if(id && id!==lastsel3){
			jQuery('#rowed6').restoreRow(lastsel3);
			jQuery('#rowed6').editRow(id,true,pickdates);
			lastsel3=id;
		}
	},
	editurl: "server.php",
	caption: "Date Picker Integration"

});
var mydata3 = [
		{id:"12345",name:"Desktop Computer",note:"note",stock:"Yes",ship:"FedEx", sdate:"2007-12-03"},
		{id:"23456",name:"Laptop",note:"Long text ",stock:"Yes",ship:"InTime",sdate:"2007-12-03"},
		{id:"34567",name:"LCD Monitor",note:"note3",stock:"Yes",ship:"TNT",sdate:"2007-12-03"},
		{id:"45678",name:"Speakers",note:"note",stock:"No",ship:"ARAMEX",sdate:"2007-12-03"},
		{id:"56789",name:"Laser Printer",note:"note2",stock:"Yes",ship:"FedEx",sdate:"2007-12-03"},
		{id:"67890",name:"Play Station",note:"note3",stock:"No", ship:"FedEx",sdate:"2007-12-03"},
		{id:"76543",name:"Mobile Telephone",note:"note",stock:"Yes",ship:"ARAMEX",sdate:"2007-12-03"},
		{id:"87654",name:"Server",note:"note2",stock:"Yes",ship:"TNT",sdate:"2007-12-03"},
		{id:"98765",name:"Matrix Printer",note:"note3",stock:"No", ship:"FedEx",sdate:"2007-12-03"}
		];
for(var i=0;i<mydata3.length;i++)
jQuery("#rowed6").addRowData(mydata3[i].id,mydata3[i]);

function pickdates(id){
	jQuery("#"+id+"_sdate","#rowed6").datepicker({dateFormat:"yy-mm-dd"});
}
