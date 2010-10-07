jQuery("#list19").jqGrid({
   	url: 'books.xml',
	datatype: "xml",
   	colNames:["Author","Title", "Price", "Published Date"],
   	colModel:[
   		{name:"Author",index:"Author", width:120, xmlmap:"ItemAttributes>Author"},
   		{name:"Title",index:"Title", width:180,xmlmap:"ItemAttributes>Title"},
   		{name:"Price",index:"Manufacturer", width:100, align:"right",xmlmap:"ItemAttributes>Price", sorttype:"float"},
   		{name:"DatePub",index:"ProductGroup", width:130,xmlmap:"ItemAttributes>DatePub",sorttype:"date"}
   	],
	height:250,
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
    viewrecords: true,
	loadonce: true,
	xmlReader: {
			root : "Items",
			row: "Item",
			repeatitems: false,
			id: "ASIN"
	},
	caption: "XML Mapping example"
});
