;(function ($) {
/*
 * jqGrid  3.5.2 - jQuery Grid
 * Copyright (c) 2008, Tony Tomov, tony@trirand.com
 * Dual licensed under the MIT and GPL licenses
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Date: 2009-08-14
 */
$.extend($.jgrid,{  
	htmlDecode : function(value){
		if(value=='&nbsp;' || value=='&#160;' || (value.length==1 && value.charCodeAt(0)==160)) { return "";}
		return !value ? value : String(value).replace(/&amp;/g, "&").replace(/&gt;/g, ">").replace(/&lt;/g, "<").replace(/&quot;/g, '"');
	},
	htmlEncode : function (value){
		return !value ? value : String(value).replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;");
	},
	format : function(format){ //jqgformat
		var args = $.makeArray(arguments).slice(1);
		return format.replace(/\{(\d+)\}/g, function(m, i){
			return args[i];
		});
	},
	getAbsoluteIndex : function (t,rInd){ 
		var cntnotv=0,cntv=0, cell, i;
		if($.browser.version > 7 ) return rInd;
		for (i=0;i<t.cells.length;i++) { 
			cell=t.cells(i); 
			if (cell.style.display=='none') cntnotv++; else cntv++; 
			if (cntv>rInd) return i; 
		}
		return i; 
	},
	stripHtml : function(v) {
		var regexp = /<("[^"]*"|'[^']*'|[^'">])*>/gi;
		if(v) {	return v.replace(regexp,"");}
		else {return v;}
	},
	stringToDoc : function (xmlString) {
		var xmlDoc;
		if(typeof xmlString !== 'string') return xmlString;
		try	{
			var parser = new DOMParser();
			xmlDoc = parser.parseFromString(xmlString,"text/xml");
		}
		catch(e) {
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
			xmlDoc.async=false;
			xmlDoc["loadXM"+"L"](xmlString);
		}
		return (xmlDoc && xmlDoc.documentElement && xmlDoc.documentElement.tagName != 'parsererror') ? xmlDoc : null;
	},
	parse : function(jsonString) {
		var js = jsonString;
		if (js.substr(0,9) == "while(1);") { js = js.substr(9); }
		if (js.substr(0,2) == "/*") { js = js.substr(2,js.length-4); }
		if(!js) { js = "{}"; }
		with(window) {
			return  eval('('+js+')');
		}
	}
});
$.fn.jqGrid = function( p ) {
	p = $.extend(true,{
	url: "",
	height: 150,
	page: 1,
	rowNum: 20,
	records: 0,
	pager: "",
	pgbuttons: true,
	pginput: true,
	colModel: [],
	rowList: [],
	colNames: [],
	sortorder: "asc",
	sortname: "",
	datatype: "xml",
	mtype: "GET",
	altRows: false,
	selarrrow: [],
	savedRow: [],
	shrinkToFit: true,
	xmlReader: {},
	jsonReader: {},
	subGrid: false,
	subGridModel :[],
	reccount: 0,
	lastpage: 0,
	lastsort: 0,
	selrow: null,
	beforeSelectRow: null,
	onSelectRow: null,
	onSortCol: null,
	ondblClickRow: null,
	onRightClickRow: null,
	onPaging: null,
	onSelectAll: null,
	loadComplete: null,
	gridComplete: null,
	loadError: null,
	loadBeforeSend: null,
	afterInsertRow: null,
	beforeRequest: null,
	onHeaderClick: null,
	viewrecords: false,
	loadonce: false,
	multiselect: false,
	multikey: false,
	editurl: null,
	search: false,
	caption: "",
	hidegrid: true,
	hiddengrid: false,
	postData: {},
	userData: {},
	treeGrid : false,
	treeGridModel : 'nested',
	treeReader : {},
	treeANode : -1,
	ExpandColumn: null,
	tree_root_level : 0,
	prmNames: {page:"page",rows:"rows", sort: "sidx",order: "sord", search:"_search", nd:"nd"},
	forceFit : false,
	gridstate : "visible",
	cellEdit: false,
	cellsubmit: "remote",
	nv:0,
	loadui: "enable",
	toolbar: [false,""],
	scroll: false,
	multiboxonly : false,
	deselectAfterSort : true,
	scrollrows : false,
	autowidth: false,
	scrollOffset :18,
	cellLayout: 5,
	subGridWidth: 20,
	multiselectWidth: 20,
	gridview: false,
	rownumWidth: 25,
	rownumbers : false,
	pagerpos: 'center',
	recordpos: 'right',
	footerrow : false,
	userDataOnFooter : false,
	hoverrows : true,
	altclass : 'ui-priority-secondary',
	viewsortcols : [false,'vertical',true],
	resizeclass : '',
	autoencode : false
	}, $.jgrid.defaults, p || {});
	var grid={         
		headers:[],
		cols:[],
		footers: [],
		dragStart: function(i,x,y) {
			this.resizing = { idx: i, startX: x.clientX, sOL : y[0]};
			this.hDiv.style.cursor = "col-resize";
			this.curGbox = $("#rs_m"+p.id,"#gbox_"+p.id);
			this.curGbox.css({display:"block",left:y[0],top:y[1],height:y[2]});
			document.onselectstart=new Function ("return false");
		},
		dragMove: function(x) {
			if(this.resizing) {
				var diff = x.clientX-this.resizing.startX,
				h = this.headers[this.resizing.idx],
				newWidth = h.width + diff, hn, nWn;
				if(newWidth > 33) {
					this.curGbox.css({left:this.resizing.sOL+diff});
					if(p.forceFit===true ){
						hn = this.headers[this.resizing.idx+p.nv];
						nWn = hn.width - diff;
						if(nWn >33) {
							h.newWidth = newWidth;
							hn.newWidth = nWn;
							this.newWidth = p.tblwidth;
						}
					} else {
						this.newWidth = p.tblwidth+diff;
						h.newWidth = newWidth;
					}
				}
			}
		},
		dragEnd: function() {
			this.hDiv.style.cursor = "default";
			if(this.resizing) {
				var idx = this.resizing.idx,
				nw = this.headers[idx].newWidth || this.headers[idx].width;
				this.resizing = false;
				$("#rs_m"+p.id).css("display","none");
				p.colModel[idx].width = nw;
				this.headers[idx].width = nw;
				this.headers[idx].el.style.width = nw + "px";
				if(this.cols.length>0) {this.cols[idx].style.width = nw+"px";}
				if(this.footers.length>0) {this.footers[idx].style.width = nw+"px";}
				if(p.forceFit===true){
					nw = this.headers[idx+p.nv].newWidth || this.headers[idx+p.nv].width;
					this.headers[idx+p.nv].width = nw;
					this.headers[idx+p.nv].el.style.width = nw + "px";
					if(this.cols.length>0) this.cols[idx+p.nv].style.width = nw+"px";
					if(this.footers.length>0) {this.footers[idx+p.nv].style.width = nw+"px";}
					p.colModel[idx+p.nv].width = nw;
				} else  {
					p.tblwidth = this.newWidth;
					$('table:first',this.bDiv).css("width",p.tblwidth+"px");
					$('table:first',this.hDiv).css("width",p.tblwidth+"px");
					this.hDiv.scrollLeft = this.bDiv.scrollLeft;
					if(p.footerrow) {
						$('table:first',this.sDiv).css("width",p.tblwidth+"px");
						this.sDiv.scrollLeft = this.bDiv.scrollLeft;
					}
				}
			}
			this.curGbox=null;
			document.onselectstart=new Function ("return true");
		},
		scrollGrid: function() {
			if(p.scroll === true) {
				var scrollTop = this.bDiv.scrollTop;
				if (scrollTop != this.scrollTop) {
					this.scrollTop = scrollTop;
					if ((this.bDiv.scrollHeight-scrollTop-$(this.bDiv).height()) <= 0) {
						if(parseInt(p.page,10)+1<=parseInt(p.lastpage,10)) {
							p.page = parseInt(p.page,10)+1;
							this.populate();
						}
					}
				}
			}
			this.hDiv.scrollLeft = this.bDiv.scrollLeft;
			if(p.footerrow) {
				this.sDiv.scrollLeft = this.bDiv.scrollLeft;
			}
		}
	};
	return this.each( function() {
		if(this.grid) {return;}
		this.p = p ;
		var i;
		if(this.p.colNames.length === 0) {
			for (i=0;i<this.p.colModel.length;i++){
				this.p.colNames[i] = this.p.colModel[i].label || this.p.colModel[i].name;
			}
		}
		if( this.p.colNames.length !== this.p.colModel.length ) {
			alert($.jgrid.errors.model);
			return;
		}
		var gv = $("<div class='ui-jqgrid-view'></div>"), ii,
		isMSIE = $.browser.msie ? true:false,
		isSafari = $.browser.safari ? true : false;
		
		$(gv).insertBefore(this);
		$(this).appendTo(gv).removeClass("scroll");
		var eg = $("<div class='ui-jqgrid ui-widget ui-widget-content ui-corner-all'></div>");
		$(eg).insertBefore(gv).attr("id","gbox_"+this.id);
		$(gv).appendTo(eg).attr("id","gview_"+this.id);
		if (isMSIE && $.browser.version <= 6) {
			ii = '<iframe style="display:block;position:absolute;z-index:-1;filter:Alpha(Opacity=\'0\');" src="javascript:false;"></iframe>';
		} else { ii="";}
		$("<div class='ui-widget-overlay jqgrid-overlay' id='lui_"+this.id+"'></div>").append(ii).insertBefore(gv);
		$("<div class='loading ui-state-default ui-state-active' id='load_"+this.id+"'>"+this.p.loadtext+"</div>").insertBefore(gv);
		$(this).attr({cellSpacing:"0",cellPadding:"0",border:"0","role":"grid","aria-multiselectable":this.p.multiselect,"aria-labelledby":"gbox_"+this.id});
		var ts = this,
		bSR = $.isFunction(this.p.beforeSelectRow) ? this.p.beforeSelectRow :false,
		ondblClickRow = $.isFunction(this.p.ondblClickRow) ? this.p.ondblClickRow :false,
		onSortCol = $.isFunction(this.p.onSortCol) ? this.p.onSortCol : false,
		loadComplete = $.isFunction(this.p.loadComplete) ? this.p.loadComplete : false,
		loadError = $.isFunction(this.p.loadError) ? this.p.loadError : false,
		loadBeforeSend = $.isFunction(this.p.loadBeforeSend) ? this.p.loadBeforeSend : false,
		onRightClickRow = $.isFunction(this.p.onRightClickRow) ? this.p.onRightClickRow : false,
		afterInsRow = $.isFunction(this.p.afterInsertRow) ? this.p.afterInsertRow : false,
		onHdCl = $.isFunction(this.p.onHeaderClick) ? this.p.onHeaderClick : false,
		beReq = $.isFunction(this.p.beforeRequest) ? this.p.beforeRequest : false,
		onSC = $.isFunction(this.p.onCellSelect) ? this.p.onCellSelect : false,
		sortkeys = ["shiftKey","altKey","ctrlKey"],
		IntNum = function(val,defval) {
			val = parseInt(val,10);
			if (isNaN(val)) { return defval ? defval : 0;}
			else {return val;}
		},
		formatCol = function (pos, rowInd){
			var ral = ts.p.colModel[pos].align, result="style=\"";
			if(ral) result += "text-align:"+ral+";";
			if(ts.p.colModel[pos].hidden===true) result += "display:none;";
			if(rowInd===0) {
				result += "width: "+grid.headers[pos].width+"px;"
			}
			return result+"\"";
		},
		addCell = function(rowId,cell,pos,irow, srvr) {
			var v,prp;
			v = formatter(rowId,cell,pos,srvr,'add');
			prp = formatCol( pos,irow);
			return "<td role=\"gridcell\" "+prp+" title=\""+$.jgrid.stripHtml(v)+"\">"+v+"</td>";
		},
		formatter = function (rowId, cellval , colpos, rwdat, _act){
			var cm = ts.p.colModel[colpos],v;
			if(typeof cm.formatter !== 'undefined') {
				var opts= {rowId: rowId, colModel:cm };
				if($.isFunction( cm.formatter ) ) {
					v = cm.formatter(cellval,opts,rwdat,_act);
				} else if($.fmatter){
					v = $.fn.fmatter(cm.formatter, cellval,opts, rwdat, _act);
				} else {
					v = cellVal(cellval);
				}
			} else {
				v = cellVal(cellval);
			}
			return ts.p.autoencode ? $.jgrid.htmlEncode(v) : v;
		},
		cellVal =  function (val) {
			return val === undefined || val === null || val === "" ? "&#160;" : val+"";
		},
		addMulti = function(rowid,pos,irow){
			var	v = "<input type=\"checkbox\""+" id=\"jqg_"+rowid+"\" class=\"cbox\" name=\"jqg_"+rowid+"\"/>",
			prp = formatCol(pos,irow);
			return "<td role='gridcell' "+prp+">"+v+"</td>";
		},
		addRowNum = function (pos,irow,pG,rN) {
			var v =  (parseInt(pG)-1)*parseInt(rN)+1+irow,
			prp = formatCol(pos,irow);
			return "<td role=\"gridcell\" class=\"ui-state-default jqgrid-rownum\" "+prp+">"+v+"</td>";
		},
		reader = function (datatype) {
			var field, f=[], j=0, i;
			for(i =0; i<ts.p.colModel.length; i++){
				field = ts.p.colModel[i];
				if (field.name !== 'cb' && field.name !=='subgrid' && field.name !=='rn') {
					f[j] = (datatype=="xml") ? field.xmlmap || field.name : field.jsonmap || field.name;
					j++;
				}
			}
			return f;
		},
		addXmlData = function (xml,t, rcnt) {
			var startReq = new Date();
			ts.p.reccount = 0;
			if($.isXMLDoc(xml)) {
				if(ts.p.treeANode===-1 && ts.p.scroll===false) {
					$("tbody", t).empty(); rcnt=0;
				} else { rcnt = rcnt > 0 ? rcnt :0; }
			} else { return; }
			var i,fpos,ir=0,v,row,gi=0,si=0,ni=0,idn, getId,f=[],rd ={}, rl= ts.rows.length, xmlr,rid, rowData=[],ari=0, cn=(ts.p.altRows === true) ? ts.p.altclass:'',cn1;
			if(!ts.p.xmlReader.repeatitems) {f = reader("xml");}
			if( ts.p.keyIndex===false) {
				idn = ts.p.xmlReader.id;
				if( idn.indexOf("[") === -1 ) {
					getId = function( trow, k) {return $(idn,trow).text() || k;};
				}
				else {
					getId = function( trow, k) {return trow.getAttribute(idn.replace(/[\[\]]/g,"")) || k;};
				}
			} else {
				getId = function(trow) { return (f.length - 1 >= ts.p.keyIndex) ? $(f[ts.p.keyIndex],trow).text() : $(ts.p.xmlReader.cell+":eq("+ts.p.keyIndex+")",trow).text(); };
			}
			$(ts.p.xmlReader.page,xml).each(function() {ts.p.page = this.textContent  || this.text || 1; });
			$(ts.p.xmlReader.total,xml).each(function() {ts.p.lastpage = this.textContent  || this.text || 1; }  );
			$(ts.p.xmlReader.records,xml).each(function() {ts.p.records = this.textContent  || this.text  || 0; }  );
			$(ts.p.xmlReader.userdata,xml).each(function() {ts.p.userData[this.getAttribute("name")]=this.textContent || this.text;});
			var gxml = $(ts.p.xmlReader.root+" "+ts.p.xmlReader.row,xml),gl = gxml.length, j=0;
			if(gxml && gl){
			var rn = ts.p.rowNum;
			while (j<gl) {
				xmlr = gxml[j];
				rid = getId(xmlr,j+1);
				cn1 = j%2 == 1 ? cn : '';
				rowData[ari++] = "<tr id=\""+rid+"\" role=\"row\" class =\"ui-widget-content jqgrow "+cn1+"\">";
				if(ts.p.rownumbers===true) {
					rowData[ari++] = addRowNum(0,j,ts.p.page,ts.p.rowNum);
					ni=1;
				}
				if(ts.p.multiselect===true) {
					rowData[ari++] = addMulti(rid,ni,j);
					gi=1;
				}
				if (ts.p.subGrid===true) {
					rowData[ari++]= $(ts).addSubGridCell(gi+ni,j+rcnt);
					si= 1;
				}
				if(ts.p.xmlReader.repeatitems===true){
					$(ts.p.xmlReader.cell,xmlr).each( function (k) {
						v = this.textContent || this.text;
						rd[ts.p.colModel[k+gi+si+ni].name] = v;
						rowData[ari++] = addCell(rid,v,k+gi+si+ni,j+rcnt,xmlr);
					});
				} else {
					for(i = 0; i < f.length;i++) {
						v = $(f[i],xmlr).text();
						rd[ts.p.colModel[i+gi+si+ni].name] = v;
						rowData[ari++] = addCell(rid, v, i+gi+si+ni, j+rcnt, xmlr);
					}
				}
				rowData[ari++] = "</tr>";
				if(ts.p.gridview === false ) {
					if( ts.p.treeGrid === true) {
						fpos = ts.p.treeANode >= -1 ? ts.p.treeANode: 0;
						row = $(rowData.join(''))[0]; // speed overhead
						try {$(ts).setTreeNode(rd,row);} catch (e) {}
						rl ===  0 ? $("tbody:first",t).append(row) : $(ts.rows[j+fpos+rcnt]).after(row);
					} else {
						$("tbody:first",t).append(rowData.join(''));
					}
					if (ts.p.subGrid===true) {
						try {$(ts).addSubGrid(ts.rows[ts.rows.length-1],gi+ni);} catch (e){}
					}
					if(afterInsRow) {ts.p.afterInsertRow(rid,rd,xmlr);}
					rowData=[];ari=0;
				}
				rd={};
				ir++;
				j++;
				if( rn !=-1 && ir>rn) {break;}
			}
			}
			if(ts.p.gridview === true) {
				$("table:first",t).append(rowData.join(''));
			}
			ts.p.totaltime = new Date() - startReq;
			if(ir>0) {ts.grid.cols = ts.rows[0].cells;if(ts.p.records===0)ts.p.records=gl;}
			rowData =null;
		  	if(!ts.p.treeGrid && !ts.p.scroll) {ts.grid.bDiv.scrollTop = 0; ts.p.reccount=ir;}
			ts.p.treeANode = -1;
			if(ts.p.userDataOnFooter) $(ts).footerData("set",ts.p.userData,true);
			updatepager(false);
		},
		addJSONData = function(data,t, rcnt) {
			var startReq = new Date();
			ts.p.reccount = 0;
			if(data) {
				if(ts.p.treeANode === -1 && ts.p.scroll===false) {
					$("tbody", t).empty(); rcnt=0;
				} else { rcnt = rcnt > 0 ? rcnt :0; }
			} else { return; }
			var ir=0,v,i,j,row,f=[],cur,gi=0,si=0,ni=0,len,drows,idn,rd={}, fpos,rl = ts.rows.length,idr,rowData=[],ari=0,cn=(ts.p.altRows === true) ? ts.p.altclass:'',cn1;
			ts.p.page = data[ts.p.jsonReader.page] || 1;
			ts.p.lastpage= data[ts.p.jsonReader.total] || 1;
			ts.p.records= data[ts.p.jsonReader.records] || 0;
			ts.p.userData = data[ts.p.jsonReader.userdata] || {};
			if(!ts.p.jsonReader.repeatitems) {f = reader("json");}
			if( ts.p.keyIndex===false ) {
				idn = ts.p.jsonReader.id;
				if(f.length>0 && !isNaN(idn)) {idn=f[idn];}
			} else {
				idn = f.length>0 ? f[ts.p.keyIndex] : ts.p.keyIndex;
			}
			drows = data[ts.p.jsonReader.root];
			if (drows) {
			len = drows.length, i=0;
			var rn = ts.p.rowNum;
			while (i<len) {
				cur = drows[i];
				idr = cur[idn];
				if(idr === undefined) {
					if(f.length===0){
						if(ts.p.jsonReader.cell){
							var ccur = cur[ts.p.jsonReader.cell];
							idr = ccur[idn] || i+1;
							ccur=null;
						} else {idr=i+1;}
					} else {
						idr=i+1;
					}
				}
				cn1 = i%2 == 1 ? cn : '';
				rowData[ari++] = "<tr id=\""+ idr +"\" role=\"row\" class= \"ui-widget-content jqgrow "+ cn1+"\">";
				if(ts.p.rownumbers===true) {
					rowData[ari++] = addRowNum(0,i,ts.p.page,ts.p.rowNum);
					ni=1;
				}
				if(ts.p.multiselect){
					rowData[ari++] = addMulti(idr,ni,i);
					gi = 1;
				}
				if (ts.p.subGrid) {
					rowData[ari++]= $(ts).addSubGridCell(gi+ni,i+rcnt);
					si= 1;
				}
				if (ts.p.jsonReader.repeatitems === true) {
					if(ts.p.jsonReader.cell) {cur = cur[ts.p.jsonReader.cell];}
					for (j=0;j<cur.length;j++) {
						rowData[ari++] = addCell(idr,cur[j],j+gi+si+ni,i+rcnt,cur);
						rd[ts.p.colModel[j+gi+si+ni].name] = cur[j];
					}
				} else {
					for (j=0;j<f.length;j++) {
						v=cur[f[j]];
						if(v===undefined) {
							try { v = eval("cur."+f[j]);}
							catch (e) {}
						}
						rowData[ari++] = addCell(idr,v,j+gi+si+ni,i+rcnt,cur);
						rd[ts.p.colModel[j+gi+si+ni].name] = cur[f[j]];
					}
				}
				rowData[ari++] = "</tr>";
				if(ts.p.gridview === false ) {
					if( ts.p.treeGrid === true) {
						fpos = ts.p.treeANode >= -1 ? ts.p.treeANode: 0;
						row = $(rowData.join(''))[0];
						try {$(ts).setTreeNode(rd,row);} catch (e) {}
						rl ===  0 ? $("tbody:first",t).append(row) : $(ts.rows[i+fpos+rcnt]).after(row);
					} else {
						$("tbody:first",t).append(rowData.join(''));
					}
					if(ts.p.subGrid === true ) {
						try { $(ts).addSubGrid(ts.rows[ts.rows.length-1],gi+ni);} catch (e){}
					}
					if(afterInsRow) {ts.p.afterInsertRow(idr,rd,cur);}
					rowData=[];ari=0;
				}
				rd={};
				ir++;
				i++;
				if(rn !=-1 && ir>rn) break;
			}
			if(ts.p.gridview === true ) {
				$("table:first",t).append(rowData.join(''));
			}
			ts.p.totaltime = new Date() - startReq;
			if(ir>0) {ts.grid.cols = ts.rows[0].cells;if(ts.p.records===0)ts.p.records=len;}
			}
			if(!ts.p.treeGrid && !ts.p.scroll) {ts.grid.bDiv.scrollTop = 0;}
			ts.p.reccount=ir;
			ts.p.treeANode = -1;
			if(ts.p.userDataOnFooter) $(ts).footerData("set",ts.p.userData,true);
			updatepager(false);
		},
		updatepager = function(rn) {
			var cp, last, base,bs, from,to,tot,fmt;
			base = (parseInt(ts.p.page)-1)*parseInt(ts.p.rowNum);
			if(ts.p.pager) {
				if (ts.p.loadonce) {
					cp = last = 1;
					ts.p.lastpage = ts.page =1;
					$(".selbox",ts.p.pager).attr("disabled",true);
				} else {
					cp = IntNum(ts.p.page);
					last = IntNum(ts.p.lastpage);
					$(".selbox",ts.p.pager).attr("disabled",false);
				}
				if(ts.p.pginput===true) {
					$('.ui-pg-input',ts.p.pager).val(ts.p.page);
					$('#sp_1',ts.p.pager).html(ts.p.lastpage );
				}
				if (ts.p.viewrecords){
					bs = ts.p.scroll === true ? 0 : base;
					if(ts.p.reccount === 0) 
						$(".ui-paging-info",ts.p.pager).html(ts.p.emptyrecords);
					else {
						from = bs+1; to = base+ts.p.reccount; tot=ts.p.records;
						if($.fmatter) {
							fmt = $.jgrid.formatter.integer || {};
							from = $.fmatter.util.NumberFormat(from,fmt);
							to = $.fmatter.util.NumberFormat(to,fmt);
							tot = $.fmatter.util.NumberFormat(tot,fmt);
						}
						$(".ui-paging-info",ts.p.pager).html($.jgrid.format(ts.p.recordtext,from,to,tot));
					}
				}
				if(ts.p.pgbuttons===true) {
					if(cp<=0) {cp = last = 1;}
					if(cp==1) {$("#first, #prev",ts.p.pager).addClass('ui-state-disabled').removeClass('ui-state-hover');} else {$("#first, #prev",ts.p.pager).removeClass('ui-state-disabled');}
					if(cp==last) {$("#next, #last",ts.p.pager).addClass('ui-state-disabled').removeClass('ui-state-hover');} else {$("#next, #last",ts.p.pager).removeClass('ui-state-disabled');}
				}
			}
			if(rn===true && ts.p.rownumbers === true) {
				$("td.jqgrid-rownum",ts.rows).each(function(i){
					$(this).html(base+1+i);
				});
			}
			if($.isFunction(ts.p.gridComplete)) {ts.p.gridComplete();}
		},
		populate = function () {
			if(!ts.grid.hDiv.loading) {
				var prm = {}, dt, dstr;
				prm[ts.p.prmNames.search] = ts.p.search; prm[ts.p.prmNames.nd] = new Date().getTime();
				prm[ts.p.prmNames.rows]= ts.p.rowNum; prm[ts.p.prmNames.page]= ts.p.page;
				prm[ts.p.prmNames.sort]= ts.p.sortname; prm[ts.p.prmNames.order]= ts.p.sortorder;
				$.extend(ts.p.postData,prm);
				var rcnt = ts.p.scroll===false ? 0 : ts.rows.length-1; 
				if ($.isFunction(ts.p.datatype)) { ts.p.datatype(ts.p.postData,"load_"+ts.p.id); return;}
				else if(beReq) {ts.p.beforeRequest();}
				dt = ts.p.datatype.toLowerCase();
				switch(dt)
				{
				case "json":
				case "xml":
				case "script":
					$.ajax({url:ts.p.url,type:ts.p.mtype,dataType: dt ,data: ts.p.postData,
						complete:function(req,st) {
							if(st=="success" || (req.statusText == "OK" && req.status == "200")) {
								if(dt === "json" || dt === "script") addJSONData($.jgrid.parse(req.responseText),ts.grid.bDiv,rcnt);
								if(dt === "xml") addXmlData(req.responseXML,ts.grid.bDiv,rcnt);
								if(loadComplete) loadComplete(req);
							}
							req=null;
							endReq();
						},
						error:function(xhr,st,err){
							if(loadError) loadError(xhr,st,err);
							endReq();
							xhr=null;
						},
						beforeSend: function(xhr){
							beginReq();
							if(loadBeforeSend) loadBeforeSend(xhr);
						}
					});
					if( ts.p.loadonce || ts.p.treeGrid) {ts.p.datatype = "local";}
				break;
				case "xmlstring":
					beginReq();
					addXmlData(dstr = $.jgrid.stringToDoc(ts.p.datastr),ts.grid.bDiv);
					ts.p.datatype = "local";
					if(loadComplete) {loadComplete(dstr);}
					ts.p.datastr = null;
					endReq();
				break;
				case "jsonstring":
					beginReq();
					if(typeof ts.p.datastr == 'string') dstr = $.jgrid.parse(ts.p.datastr);
					else dstr = ts.p.datastr;
					addJSONData(dstr,ts.grid.bDiv);
					ts.p.datatype = "local";
					if(loadComplete) {loadComplete(dstr);}
					ts.p.datastr = null;
					endReq();
				break;
				case "local":
				case "clientside":
					beginReq();
					ts.p.datatype = "local";
					sortArrayData();
					endReq();
				break;
				}
			}
		},
		beginReq = function() {
			ts.grid.hDiv.loading = true;
			if(ts.p.hiddengrid) { return;}
			switch(ts.p.loadui) {
				case "disable":
					break;
				case "enable":
					$("#load_"+ts.p.id).show();
					break;
				case "block":
					$("#lui_"+ts.p.id).show();
					$("#load_"+ts.p.id).show();
					break;
			}
		},
		endReq = function() {
			ts.grid.hDiv.loading = false;
			switch(ts.p.loadui) {
				case "disable":
					break;
				case "enable":
					$("#load_"+ts.p.id).hide();
					break;
				case "block":
					$("#lui_"+ts.p.id).hide();
					$("#load_"+ts.p.id).hide();
					break;
			}
		},
		sortArrayData = function() {
			var stripNum = /[\$,%]/g;
			var rows=[], col=0, st, sv, findSortKey,newDir = (ts.p.sortorder == "asc") ? 1 :-1;
			$.each(ts.p.colModel,function(i,v){
				if(this.index == ts.p.sortname || this.name == ts.p.sortname){
					col = ts.p.lastsort= i;
					st = this.sorttype;
					return false;
				}
			});
			if (st == 'float' || st== 'number' || st== 'currency') {
				findSortKey = function($cell) {
					var key = parseFloat($cell.replace(stripNum, ''));
					return isNaN(key) ? 0 : key;
				};
			} else if (st=='int' || st=='integer') {
				findSortKey = function($cell) {
					return IntNum($cell.replace(stripNum, ''));
				};
			} else if(st == 'date') {
				findSortKey = function($cell) {
					var fd = ts.p.colModel[col].datefmt || "Y-m-d";
					return parseDate(fd,$cell).getTime();
				};
			} else {
				findSortKey = function($cell) {
					return $.trim($cell.toUpperCase());
				};
			}
			$.each(ts.rows, function(index, row) {
				try { sv = $.unformat($(row).children('td').eq(col),{colModel:ts.p.colModel[col]},col,true);}
				catch (_) { sv = $(row).children('td').eq(col).text(); }
				row.sortKey = findSortKey(sv);
				rows[index] = this;
			});
			if(ts.p.treeGrid) {
				$(ts).SortTree( newDir);
			} else {
				rows.sort(function(a, b) {
					if (a.sortKey < b.sortKey) {return -newDir;}
					if (a.sortKey > b.sortKey) {return newDir;}
					return 0;
				});
				if(rows[0]){
					$("td",rows[0]).each( function( k ) {
						$(this).css("width",grid.headers[k].width+"px");
					});
					grid.cols = rows[0].cells;
				}
				$.each(rows, function(index, row) {
					$('tbody',ts.grid.bDiv).append(row);
					row.sortKey = null;
				});
			}
			if(ts.p.multiselect) {
				$("tbody tr", ts.grid.bDiv).removeClass("ui-state-highlight");
				$("[id^=jqg_]",ts.rows).attr("checked",false);
				$("#cb_jqg",ts.grid.hDiv).attr("checked",false);
				ts.p.selarrrow = [];
			}
			ts.grid.bDiv.scrollTop = 0;
		},
		parseDate = function(format, date) {
			var tsp = {m : 1, d : 1, y : 1970, h : 0, i : 0, s : 0},k,hl,dM;
			date = date.split(/[\\\/:_;.\t\T\s-]/);
			format = format.split(/[\\\/:_;.\t\T\s-]/);
			var dfmt  = $.jgrid.formatter.date.monthNames;
		    for(k=0,hl=format.length;k<hl;k++){
				if(format[k] == 'M') {
					dM = $.inArray(date[k],dfmt);
					if(dM !== -1 && dM < 12){date[k] = dM+1;}
				}
				if(format[k] == 'F') {
					dM = $.inArray(date[k],dfmt);
					if(dM !== -1 && dM > 11){date[k] = dM+1-12;}
				}
		        tsp[format[k].toLowerCase()] = parseInt(date[k],10);
		    }			
			tsp.m = parseInt(tsp.m,10)-1;
			var ty = tsp.y;
			if (ty >= 70 && ty <= 99) {tsp.y = 1900+tsp.y;}
			else if (ty >=0 && ty <=69) {tsp.y= 2000+tsp.y;}
			return new Date(tsp.y, tsp.m, tsp.d, tsp.h, tsp.i, tsp.s,0);
		},
		setPager = function (){
			var sep = "<td class='ui-pg-button ui-state-disabled' style='width:4px;'><span class='ui-separator'></span></td>",
			pgid= $(ts.p.pager).attr("id") || 'pager',
			pginp = (ts.p.pginput===true) ? "<td>"+$.jgrid.format(ts.p.pgtext || "","<input class='ui-pg-input' type='text' size='2' maxlength='7' value='0' role='textbox'/>","<span id='sp_1'></span>")+"</td>" : "",
			pgl="<table cellspacing='0' cellpadding='0' border='0' style='table-layout:auto;' class='ui-pg-table'><tbody><tr>",
			str, pgcnt, lft, cent, rgt, twd, tdw, i,
			clearVals = function(onpaging){
				if ($.isFunction(ts.p.onPaging) ) {ts.p.onPaging(onpaging);}
				ts.p.selrow = null;
				if(ts.p.multiselect) {ts.p.selarrrow =[];$('#cb_jqg',ts.grid.hDiv).attr("checked",false);}
				ts.p.savedRow = [];
			};
			pgcnt = "pg_"+pgid;
			lft = pgid+"_left"; cent = pgid+"_center"; rgt = pgid+"_right";
			$(ts.p.pager).addClass('ui-jqgrid-pager corner-bottom')
			.append("<div id='"+pgcnt+"' class='ui-pager-control' role='group'><table cellspacing='0' cellpadding='0' border='0' class='ui-pg-table' style='width:100%;table-layout:fixed;' role='row'><tbody><tr><td id='"+lft+"' align='left'></td><td id='"+cent+"' align='center' style='white-space:nowrap;'></td><td id='"+rgt+"' align='right'></td></tr></tbody></table></div>");
			if(ts.p.pgbuttons===true) {
				pgl += "<td id='first' class='ui-pg-button ui-corner-all'><span class='ui-icon ui-icon-seek-first'></span></td>";
				pgl += "<td id='prev' class='ui-pg-button ui-corner-all'><span class='ui-icon ui-icon-seek-prev'></span></td>";
				pgl += pginp !="" ? sep+pginp+sep:"";
				pgl += "<td id='next' class='ui-pg-button ui-corner-all'><span class='ui-icon ui-icon-seek-next'></span></td>";
				pgl += "<td id='last' class='ui-pg-button ui-corner-all'><span class='ui-icon ui-icon-seek-end'></span></td>";
			} else if (pginp !="") { pgl += pginp; }
			if(ts.p.rowList.length >0){
				str="<select class='ui-pg-selbox' role='listbox'>";
				for(i=0;i<ts.p.rowList.length;i++){
					str +="<option role='option' value="+ts.p.rowList[i]+((ts.p.rowNum == ts.p.rowList[i])?' selected':'')+">"+ts.p.rowList[i];
				}
				str +="</select>";
				pgl += "<td>"+str+"</td>";
			}
			pgl += "</tr></tbody></table>";
			if(ts.p.viewrecords===true) {$("td#"+pgid+"_"+ts.p.recordpos,"#"+pgcnt).append("<div class='ui-paging-info'></div>");}
			$("td#"+pgid+"_"+ts.p.pagerpos,"#"+pgcnt).append(pgl);
			tdw = $(".ui-jqgrid").css("font-size") || "11px";
			$('body').append("<div id='testpg' class='ui-jqgrid ui-widget ui-widget-content' style='font-size:"+tdw+";visibility:hidden;' ></div>");
			twd = $(pgl).clone().appendTo("#testpg").width();
			setTimeout(function(){$("#testpg").remove();},0);
			if(twd > 0) {
				twd += 25;
				$("td#"+pgid+"_"+ts.p.pagerpos,"#"+pgcnt).width(twd);
			}
			ts.p._nvtd = [];
			ts.p._nvtd[0] = twd ? Math.floor((ts.p.width - twd)/2) : Math.floor(ts.p.width/3);
			ts.p._nvtd[1] = 0;
			pgl=null;
			$('.ui-pg-selbox',"#"+pgcnt).bind('change',function() { 
				ts.p.rowNum = this.value; 
				clearVals('records');
				populate();
				return false;
			});
			if(ts.p.pgbuttons===true) {
			$(".ui-pg-button","#"+pgcnt).hover(function(e){
				if($(this).hasClass('ui-state-disabled')) {
					this.style.cursor='default';
				} else {
					$(this).addClass('ui-state-hover');
					this.style.cursor='pointer';
				}
			},function(e) {
				if($(this).hasClass('ui-state-disabled')) {
				} else {
					$(this).removeClass('ui-state-hover');
					this.style.cursor= "default";
				}
			});
			$("#first, #prev, #next, #last",ts.p.pager).click( function(e) {
				var cp = IntNum(ts.p.page),
				last = IntNum(ts.p.lastpage), selclick = false,
				fp=true, pp=true, np=true,lp=true;
				if(last ===0 || last===1) {fp=false;pp=false;np=false;lp=false; }
				else if( last>1 && cp >=1) {
					if( cp === 1) { fp=false; pp=false; } 
					else if( cp>1 && cp <last){ }
					else if( cp===last){ np=false;lp=false; }
				} else if( last>1 && cp===0 ) { np=false;lp=false; cp=last-1;}
				if( this.id === 'first' && fp ) { ts.p.page=1; selclick=true;} 
				if( this.id === 'prev' && pp) { ts.p.page=(cp-1); selclick=true;} 
				if( this.id === 'next' && np) { ts.p.page=(cp+1); selclick=true;} 
				if( this.id === 'last' && lp) { ts.p.page=last; selclick=true;}
				if(selclick) {
					clearVals(this.id);
					populate();
				}
				return false;
			});
			}
			if(ts.p.pginput===true) {
			$('input.ui-pg-input',"#"+pgcnt).keypress( function(e) {
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					ts.p.page = ($(this).val()>0) ? $(this).val():ts.p.page;
					clearVals('user');
					populate();
					return false;
				}
				return this;
			});
			}
		},
		sortData = function (index, idxcol,reload,sor){
			if(!ts.p.colModel[idxcol].sortable) return;
			var imgs, so;
			if(ts.p.savedRow.length > 0) {return;}
			if(!reload) {
				if( ts.p.lastsort == idxcol ) {
					if( ts.p.sortorder == 'asc') {
						ts.p.sortorder = 'desc';
					} else if(ts.p.sortorder == 'desc') { ts.p.sortorder = 'asc';}
				} else { ts.p.sortorder = 'asc';}
				ts.p.page = 1;
			}
			if(sor) {
				if(ts.p.lastsort == idxcol && ts.p.sortorder == sor) return;
				else ts.p.sortorder = sor;
			}
			var thd= $("thead:first",ts.grid.hDiv).get(0);
			$("tr th:eq("+ts.p.lastsort+") span.ui-grid-ico-sort",thd).addClass('ui-state-disabled');
			$("tr th:eq("+ts.p.lastsort+")",thd).attr("aria-selected","false");
			$("tr th:eq("+idxcol+") span.ui-icon-"+ts.p.sortorder,thd).removeClass('ui-state-disabled');
			$("tr th:eq("+idxcol+")",thd).attr("aria-selected","true");
			if(!ts.p.viewsortcols[0]) {
				if(ts.p.lastsort != idxcol) {
					$("tr th:eq("+ts.p.lastsort+") span.s-ico",thd).hide();
					$("tr th:eq("+idxcol+") span.s-ico",thd).show();
				}
			}
			ts.p.lastsort = idxcol;
			index = index.substring(5);
			ts.p.sortname = ts.p.colModel[idxcol].index || index;
			so = ts.p.sortorder;
			if(onSortCol) {onSortCol(index,idxcol,so);}
			if(ts.p.datatype == "local") {
				if(ts.p.deselectAfterSort) {$(ts).resetSelection();}
			} else {
				ts.p.selrow = null;
				if(ts.p.multiselect){$("#cb_jqg",ts.grid.hDiv).attr("checked",false);}
				ts.p.selarrrow =[];
				ts.p.savedRow =[];
			}
			if(ts.p.scroll===true) {$("tbody tr",ts.grid.bDiv).remove();}
			if(ts.p.subGrid && ts.p.datatype=='local') {
				$("td.sgexpanded","#"+ts.p.id).each(function(){
					$(this).trigger("click");
				});
			}
			populate();
			if(ts.p.sortname != index && idxcol) {ts.p.lastsort = idxcol;}
		},
		setColWidth = function () {
			var initwidth = 0, brd=ts.p.cellLayout, vc=0, lvc, scw=ts.p.scrollOffset,cw,hs=false,aw,tw=0,gw=0,
			msw = ts.p.multiselectWidth, sgw=ts.p.subGridWidth, rnw=ts.p.rownumWidth, cl = ts.p.cellLayout, cr;
			$.each(ts.p.colModel, function(i) {
				if(typeof this.hidden === 'undefined') {this.hidden=false;}
				if(this.hidden===false){
					initwidth += IntNum(this.width);
					vc++;
				}
			});
			if(isNaN(ts.p.width)) {ts.p.width = grid.width = initwidth;}
			else { grid.width = ts.p.width}
			ts.p.tblwidth = initwidth;
			if(ts.p.shrinkToFit ===false && ts.p.forceFit === true) {ts.p.forceFit=false;}
			if(ts.p.shrinkToFit===true) {
				if (isSafari) { brd=0; msw +=cl; sgw += cl; rnw += cl;}
				if(ts.p.multiselect) {tw = msw; gw = msw+brd; vc--;}
				if(ts.p.subGrid) {tw += sgw; gw += sgw+brd; vc--;}
				if(ts.p.rownumbers) { tw += rnw; gw += rnw+brd; vc--;}
				aw = grid.width-brd*vc-gw;
				if(isNaN(ts.p.height)) {
				} else {
					aw -= scw;
					hs = true;
				}
				initwidth =0;
				$.each(ts.p.colModel, function(i) {
					if(this.hidden === false && this.name !== 'cb' && this.name !== 'subgrid' && this.name !== 'rn'){
						cw = Math.floor(aw/(ts.p.tblwidth-tw)*this.width);
						this.width =cw;
						initwidth += cw;
						lvc = i;
					}
				});
				cr =0;
				if (hs && grid.width-gw-(initwidth+brd*vc) !== scw) {
					cr = grid.width-gw-(initwidth+brd*vc)-scw;
				} else if(!hs && Math.abs(grid.width-gw-(initwidth+brd*vc)) !== 1) {
					cr = grid.width-gw-(initwidth+brd*vc);
				}
				ts.p.colModel[lvc].width += cr;
				ts.p.tblwidth = initwidth+tw+cr;
			}
		},
		nextVisible= function(iCol) {
			var ret = iCol, j=iCol, i;
			for (i = iCol+1;i<ts.p.colModel.length;i++){
				if(ts.p.colModel[i].hidden !== true ) {
					j=i; break;
				}
			}
			return j-ret;
		},
		getOffset = function (iCol) {
			var i, ret = {}, brd1 = isSafari ? 0 : ts.p.cellLayout;
			ret[0] =  ret[1] = ret[2] = 0;
			for(i=0;i<=iCol;i++){
				if(ts.p.colModel[i].hidden === false ) {
					ret[0] += ts.p.colModel[i].width+brd1;
				}
			}
			ret[0] = ret[0] - ts.grid.bDiv.scrollLeft;
			if($(ts.grid.cDiv).is(":visible")) {ret[1] += $(ts.grid.cDiv).height() +parseInt($(ts.grid.cDiv).css("padding-top"))+parseInt($(ts.grid.cDiv).css("padding-bottom"));}
			if(ts.p.toolbar[0]==true && (ts.p.toolbar[1]=='top' || ts.p.toolbar[1]=='both')) {ret[1] += $(ts.grid.uDiv).height()+parseInt($(ts.grid.uDiv).css("border-top-width"))+parseInt($(ts.grid.uDiv).css("border-bottom-width"));}
			ret[2] += $(ts.grid.bDiv).height() + $(ts.grid.hDiv).height();
			return ret;
		};
		this.p.id = this.id;
		if ($.inArray(ts.p.multikey,sortkeys) == -1 ) {ts.p.multikey = false;}
		ts.p.keyIndex=false;
		for (i=0; i<ts.p.colModel.length;i++) {
			if (ts.p.colModel[i].key===true) {
				ts.p.keyIndex = i;
				break;
			}
		}
		ts.p.sortorder = ts.p.sortorder.toLowerCase();
		if(this.p.treeGrid === true) {
			try { $(this).setTreeGrid();} catch (_) {}
		}
		if(this.p.subGrid) {
			try { $(ts).setSubGrid();} catch (_){}
		}
		if(this.p.multiselect) {
			this.p.colNames.unshift("<input id='cb_jqg' class='cbox' type='checkbox'/>");
			this.p.colModel.unshift({name:'cb',width:isSafari ?  ts.p.multiselectWidth+ts.p.cellLayout : ts.p.multiselectWidth,sortable:false,resizable:false,hidedlg:true,search:false,align:'center'});
		}
		if(this.p.rownumbers) {
			this.p.colNames.unshift("");
			this.p.colModel.unshift({name:'rn',width:ts.p.rownumWidth,sortable:false,resizable:false,hidedlg:true,search:false,align:'center'});
		}
		ts.p.xmlReader = $.extend({
			root: "rows",
			row: "row",
			page: "rows>page",
			total: "rows>total",
			records : "rows>records",
			repeatitems: true,
			cell: "cell",
			id: "[id]",
			userdata: "userdata",
			subgrid: {root:"rows", row: "row", repeatitems: true, cell:"cell"}
		}, ts.p.xmlReader);
		ts.p.jsonReader = $.extend({
			root: "rows",
			page: "page",
			total: "total",
			records: "records",
			repeatitems: true,
			cell: "cell",
			id: "id",
			userdata: "userdata",
			subgrid: {root:"rows", repeatitems: true, cell:"cell"}
		},ts.p.jsonReader);
		if(ts.p.scroll===true){
			ts.p.pgbuttons = false; ts.p.pginput=false; ts.p.rowList=[];
		}
		var thead = "<thead><tr class='ui-jqgrid-labels' role='rowheader'>",
		tdc, idn, w, res, sort,
		td, ptr, tbody, imgs,iac="",idc="";
		if(ts.p.shrinkToFit===true && ts.p.forceFit===true) {
			for (i=ts.p.colModel.length-1;i>=0;i--){
				if(!ts.p.colModel[i].hidden) {
					ts.p.colModel[i].resizable=false;
					break;
				}
			}
		}
		if(ts.p.viewsortcols[1] == 'horizontal') {iac=" ui-i-asc";idc=" ui-i-desc";}
		tdc = isMSIE ?  "class='ui-th-div-ie'" :"";
		imgs = "<span class='s-ico' style='display:none'><span sort='asc' class='ui-grid-ico-sort ui-icon-asc"+iac+" ui-state-disabled ui-icon ui-icon-triangle-1-n'></span>";
		imgs += "<span sort='desc' class='ui-grid-ico-sort ui-icon-desc"+idc+" ui-state-disabled ui-icon ui-icon-triangle-1-s'></span></span>";
		for(i=0;i<this.p.colNames.length;i++){
			thead += "<th role='columnheader' class='ui-state-default ui-th-column'>";
			idn = ts.p.colModel[i].index || ts.p.colModel[i].name;
			thead += "<div id='jqgh_"+ts.p.colModel[i].name+"' "+tdc+">"+ts.p.colNames[i];
			if (idn == ts.p.sortname) {
				ts.p.lastsort = i;
			} 
			thead += imgs+"</div></th>";
		}
		thead += "</tr></thead>";
		$(this).append(thead);
		$("thead tr:first th",this).hover(function(){$(this).addClass('ui-state-hover');},function(){$(this).removeClass('ui-state-hover');});
		if(this.p.multiselect) {
			var onSA = true, emp=[], chk;
			if(typeof ts.p.onSelectAll !== 'function') {onSA=false;}
			$('#cb_jqg',this).bind('click',function(){
				if (this.checked) {
					$("[id^=jqg_]",ts.rows).attr("checked",true);
					$(ts.rows).each(function(i) {
						if(!$(this).hasClass("subgrid")){
						$(this).addClass("ui-state-highlight").attr("aria-selected","true");
						ts.p.selarrrow[i]= ts.p.selrow = this.id; 
						}
					});
					chk=true;
					emp=[];
				}
				else {
					$("[id^=jqg_]",ts.rows).attr("checked",false);
					$(ts.rows).each(function(i) {
						if(!$(this).hasClass("subgrid")){
							$(this).removeClass("ui-state-highlight").attr("aria-selected","false");
							emp[i] = this.id;
						}
					});
					ts.p.selarrrow = []; ts.p.selrow = null;
					chk=false;
				}
				if(onSA) {ts.p.onSelectAll(chk ? ts.p.selarrrow : emp,chk);}
			});
		}
		
		$.each(ts.p.colModel, function(i){if(!this.width) {this.width=150;}});
		if(ts.p.autowidth===true) {
			var pw = $(eg).innerWidth();
			ts.p.width = pw > 0?  pw: 'nw';
		}
		setColWidth();
		$(eg).css("width",grid.width+"px").append("<div class='ui-jqgrid-resize-mark' id='rs_m"+ts.p.id+"'>&nbsp;</div>");
		$(gv).css("width",grid.width+"px");
		thead = $("thead:first",ts).get(0);
		var	tfoot = "<table role='grid' style='width:"+ts.p.tblwidth+"px' class='ui-jqgrid-ftable' cellspacing='0' cellpadding='0' border='0'><tbody><tr role='row' class='ui-widget-content footrow'>";
		$("tr:first th",thead).each(function ( j ) {
			var ht = $('div',this)[0];
			w = ts.p.colModel[j].width;
			if(typeof ts.p.colModel[j].resizable === 'undefined') {ts.p.colModel[j].resizable = true;}
			res = document.createElement("span");
			$(res).html("&#160;");
			if(ts.p.colModel[j].resizable){
				$(this).addClass(ts.p.resizeclass);
				$(res).mousedown(function (e) {
					if(ts.p.forceFit===true) {ts.p.nv= nextVisible(j);}
					grid.dragStart(j, e, getOffset(j));
					return false;
				}).addClass('ui-jqgrid-resize');
			} else {
				res = "";
			}
			$(this).css("width",w+"px").prepend(res);
			if( ts.p.colModel[j].hidden ) $(this).css("display","none");
			grid.headers[j] = { width: w, el: this };
			sort = ts.p.colModel[j].sortable;
			if( typeof sort !== 'boolean') {ts.p.colModel[j].sortable =  true; sort=true;}
			var nm = ts.p.colModel[j].name;
			if( !(nm == 'cb' || nm=='subgrid' || nm=='rn') ) {
				if(ts.p.viewsortcols[2] == false)
					$(".ui-grid-ico-sort",this).click(function(){sortData(ht.id,j,true,$(this).attr("sort"));return false;});
				else
					$("div",this).addClass('ui-jqgrid-sortable').click(function(){sortData(ht.id,j);return false;});
			}
			if(sort) {
				if(ts.p.viewsortcols[0]) {$("div span.s-ico",this).show(); if(j==ts.p.lastsort){ $("div span.ui-icon-"+ts.p.sortorder,this).removeClass("ui-state-disabled");}}
				else if( j == ts.p.lastsort) {$("div span.s-ico",this).show();$("div span.ui-icon-"+ts.p.sortorder,this).removeClass("ui-state-disabled");}
			}
			tfoot += "<td role='gridcell' "+formatCol(j,0)+">&nbsp;</td>";
		});
		tfoot += "</tr></tbody></table>";
		
		tbody = document.createElement("tbody");
		this.appendChild(tbody);
		$(this).addClass('ui-jqgrid-btable');
		var hTable = $("<table class='ui-jqgrid-htable' style='width:"+ts.p.tblwidth+"px' role='grid' aria-labelledby='gbox_"+this.id+"' cellspacing='0' cellpadding='0' border='0'></table>").append(thead),
		hg = (ts.p.caption && ts.p.hiddengrid===true) ? true : false,
		hb = $("<div class='ui-jqgrid-hbox'></div>");
		grid.hDiv = document.createElement("div");
		$(grid.hDiv)
			.css({ width: grid.width+"px"})
			.addClass("ui-state-default ui-jqgrid-hdiv")
			.append(hb)
			.bind("selectstart", function () { return false; });
		$(hb).append(hTable);
		if(hg) {$(grid.hDiv).hide(); ts.p.gridstate = 'hidden';}
		ts.p._height =0;
		if(ts.p.pager){
			if(typeof ts.p.pager == "string") {if(ts.p.pager.substr(0,1) !="#") ts.p.pager = "#"+ts.p.pager;}
			$(ts.p.pager).css({width: grid.width+"px"}).appendTo(eg).addClass('ui-state-default ui-jqgrid-pager');
			ts.p._height += parseInt($(ts.p.pager).height(),10);
			if(hg) {$(ts.p.pager).hide();}
			setPager();
		}
		if( ts.p.cellEdit === false && ts.p.hoverrows === true) {
		$(ts).bind('mouseover',function(e) {
			ptr = $(e.target).parents("tr.jqgrow");
			if($(ptr).attr("class") !== "subgrid") {
				$(ptr).addClass("ui-state-hover");
			}
			return false;
		}).bind('mouseout',function(e) {
			ptr = $(e.target).parents("tr.jqgrow");
			$(ptr).removeClass("ui-state-hover");
			return false;
		});
		}
		var ri,ci;
		$(ts).before(grid.hDiv).click(function(e) {
			td = e.target;
			var scb = $(td).hasClass("cbox");
			ptr = $(td,ts.rows).parents("tr.jqgrow");
			if($(ptr).length === 0 ) {
				return false;
			}
			var cSel = true;
			if(bSR) cSel = bSR(ptr[0].id);
			if (td.tagName == 'A') { return true; }
			if(cSel === true) {
				if(ts.p.cellEdit === true) {
					if(ts.p.multiselect && scb){
						$(ts).setSelection(false,true,ptr);
					} else {
						ri = ptr[0].rowIndex;
						ci = !$(td).is('td') ? $(td).parents("td:first")[0].cellIndex : td.cellIndex;
						if(isMSIE) {ci = $.jgrid.getAbsoluteIndex(ptr[0],ci);}
						try {$(ts).editCell(ri,ci,true);} catch (e) {}
					}
				} else if ( !ts.p.multikey ) {
					if(ts.p.multiselect && ts.p.multiboxonly) {
						if(scb){$(ts).setSelection(false,true,ptr);}
						else {
							$(ts.p.selarrrow).each(function(i,n){
								var ind = ts.rows.namedItem(n);
								$(ind).removeClass("ui-state-highlight");
								$("#jqg_"+n.replace(".", "\\."),ind).attr("checked",false);
							});
							ts.p.selarrrow = [];
							$("#cb_jqg",ts.grid.hDiv).attr("checked",false);
							$(ts).setSelection(false,true,ptr);
						}
					} else {
						$(ts).setSelection(false,true,ptr);
					}
				} else {
					if(e[ts.p.multikey]) {
						$(ts).setSelection(false,true,ptr);
					} else if(ts.p.multiselect && scb) {
						scb = $("[id^=jqg_]",ptr).attr("checked");
						$("[id^=jqg_]",ptr).attr("checked",!scb);
					}
				}
				if(onSC) {
					ri = ptr[0].id;
					ci = !$(td).is('td') ? $(td).parents("td:first")[0].cellIndex : td.cellIndex;
					if(isMSIE) {ci = $.jgrid.getAbsoluteIndex(ptr[0],ci);}
					onSC(ri,ci,$(td).html(),td);
				}
			}
			e.stopPropagation();
		}).bind('reloadGrid', function(e) {
			if(ts.p.treeGrid ===true) {	ts.p.datatype = ts.p.treedatatype;}
			if(ts.p.datatype=="local"){ $(ts).resetSelection();}
			else if(!ts.p.treeGrid) {
				ts.p.selrow=null;
				if(ts.p.multiselect) {ts.p.selarrrow =[];$('#cb_jqg',ts.grid.hDiv).attr("checked",false);}
				if(ts.p.cellEdit) {ts.p.savedRow = []; }
			}
			if(ts.p.scroll===true) {$("tbody tr", ts.grid.bDiv).remove();}
			ts.grid.populate();
			return false;
		});
		if( ondblClickRow ) {
			$(this).dblclick(function(e) {
				td = (e.target);
				ptr = $(td,ts.rows).parents("tr.jqgrow");
				if($(ptr).length === 0 ){return false;}
				ri = ptr[0].rowIndex;
				ci = !$(td).is('td') ? $(td).parents("td:first")[0].cellIndex : td.cellIndex;
				if(isMSIE) {ci = $.jgrid.getAbsoluteIndex(ptr[0],ci);}
				ts.p.ondblClickRow($(ptr).attr("id"),ri,ci);
				return false;
			});
		}
		if (onRightClickRow) {
			$(this).bind('contextmenu', function(e) {
				td = e.target;
				ptr = $(td,ts.rows).parents("tr.jqgrow");
				if($(ptr).length === 0 ){return false;}
				if(!ts.p.multiselect) {	$(ts).setSelection(false,true,ptr);	}
				ri = ptr[0].rowIndex;
				ci = !$(td).is('td') ? $(td).parents("td:first")[0].cellIndex : td.cellIndex;
				if(isMSIE) {ci = $.jgrid.getAbsoluteIndex(ptr[0],ci);}				
				ts.p.onRightClickRow($(ptr).attr("id"),ri,ci);
				return false;
			});
		}
		grid.bDiv = document.createElement("div");
		$(grid.bDiv)
			.append(this)
			.addClass("ui-jqgrid-bdiv")
			.css({ height: ts.p.height+(isNaN(ts.p.height)?"":"px"), width: (grid.width)+"px"})
			.scroll(function (e) {grid.scrollGrid();});
		$("table:first",grid.bDiv).css({width:ts.p.tblwidth+"px"});
		if( isMSIE ) {
			if( $("tbody",this).size() === 2 ) { $("tbody:first",this).remove();}
			if( ts.p.multikey) {$(grid.bDiv).bind("selectstart",function(){return false;});}
		} else {
			if( ts.p.multikey) {$(grid.bDiv).bind("mousedown",function(){return false;});}
		}
		if(hg) {$(grid.bDiv).hide();}
		grid.cDiv = document.createElement("div");
		var arf = ts.p.hidegrid===true ? $("<a role='link' href='javascript:void(0)'/>").addClass('ui-jqgrid-titlebar-close HeaderButton').hover(
			function(){ arf.addClass('ui-state-hover');},
			function() {arf.removeClass('ui-state-hover');})
		.append("<span class='ui-icon ui-icon-circle-triangle-n'></span>") : "";
		$(grid.cDiv).append(arf).append("<span class='ui-jqgrid-title'>"+ts.p.caption+"</span>")
		.addClass("ui-jqgrid-titlebar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix");
		$(grid.cDiv).insertBefore(grid.hDiv);
		if( ts.p.toolbar[0] ) {
			grid.uDiv = document.createElement("div");
			if(ts.p.toolbar[1] == "top") {$(grid.uDiv).insertBefore(grid.hDiv);}
			else if (ts.p.toolbar[1]=="bottom" ) {$(grid.uDiv).insertAfter(grid.hDiv);}
			if(ts.p.toolbar[1]=="both") {
				grid.ubDiv = document.createElement("div");
				$(grid.uDiv).insertBefore(grid.hDiv).addClass("ui-userdata ui-state-default").attr("id","t_"+this.id);
				$(grid.ubDiv).insertAfter(grid.hDiv).addClass("ui-userdata ui-state-default").attr("id","tb_"+this.id);
				ts.p._height += IntNum($(grid.ubDiv).height());
				if(hg)  {$(grid.ubDiv).hide();}
			} else {
				$(grid.uDiv).width(grid.width).addClass("ui-userdata ui-state-default").attr("id","t_"+this.id);
			}
			ts.p._height += IntNum($(grid.uDiv).height());
			if(hg) {$(grid.uDiv).hide();}
		}
		if(ts.p.footerrow) {
			grid.sDiv = document.createElement("div");
			hb = $("<div class='ui-jqgrid-hbox'></div>");
			$(grid.sDiv).addClass("ui-jqgrid-sdiv").append(hb).insertAfter(grid.hDiv).width(grid.width);
			$(hb).append(tfoot);
			grid.footers = $(".ui-jqgrid-ftable",grid.sDiv)[0].rows[0].cells;
			if(ts.p.rownumbers) grid.footers[0].className = 'ui-state-default jqgrid-rownum';
			if(hg) {$(grid.sDiv).hide();}
		}
		if(ts.p.caption) {
			ts.p._height += parseInt($(grid.cDiv,ts).height(),10);
			var tdt = ts.p.datatype;
			if(ts.p.hidegrid===true) {
				$(".ui-jqgrid-titlebar-close",grid.cDiv).toggle( function(){
					$(".ui-jqgrid-bdiv, .ui-jqgrid-hdiv","#gview_"+ts.p.id).slideUp("fast");
					if(ts.p.pager) {$(ts.p.pager).slideUp("fast");}
					if(ts.p.toolbar[0]===true) {
						if( ts.p.toolbar[1]=='both') {
							$(grid.ubDiv).slideUp("fast");
						}
						$(grid.uDiv).slideUp("fast");
					}
					if(ts.p.footerrow) $(".ui-jqgrid-sdiv","#gbox_"+ts.p.id).slideUp("fast");
					$("span",this).removeClass("ui-icon-circle-triangle-n").addClass("ui-icon-circle-triangle-s");
					ts.p.gridstate = 'hidden';
					if(onHdCl) {if(!hg) {ts.p.onHeaderClick(ts.p.gridstate);}}
					},
					function() {
					$(".ui-jqgrid-hdiv, .ui-jqgrid-bdiv","#gview_"+ts.p.id).slideDown("fast");
					if(ts.p.pager) {$(ts.p.pager).slideDown("fast");}
					if(ts.p.toolbar[0]===true) {
						if( ts.p.toolbar[1]=='both') {
							$(grid.ubDiv).slideDown("fast");
						}
						$(grid.uDiv).slideDown("fast");
					}
					if(ts.p.footerrow) $(".ui-jqgrid-sdiv","#gbox_"+ts.p.id).slideDown("fast");
					$("span",this).removeClass("ui-icon-circle-triangle-s").addClass("ui-icon-circle-triangle-n");
					if(hg) {ts.p.datatype = tdt;populate();hg=false;}
					ts.p.gridstate = 'visible';
					if(onHdCl) {ts.p.onHeaderClick(ts.p.gridstate)}
					}
				);
				if(hg) { $(".ui-jqgrid-titlebar-close",grid.cDiv).trigger("click"); ts.p.datatype="local";}
			}
		} else {$(grid.cDiv).hide();}
		$(grid.hDiv).after(grid.bDiv)
		.mousemove(function (e) {
			if(grid.resizing){grid.dragMove(e);}
			return false;
		});
		ts.p._height += parseInt($(grid.hDiv).height(),10);
		$(document).mouseup(function (e) {
			if(grid.resizing) {	grid.dragEnd(); return false;}
			return true;
		});
		this.updateColumns = function () {
			var r = this.rows[0], self =this;
			if(r) {
				$("td",r).each( function( k ) {
					$(this).css("width",self.grid.headers[k].width+"px");
				});
				this.grid.cols = r.cells;
			}
			return this;
		}
		ts.formatCol = function(a,b) {return formatCol(a,b);};
		ts.sortData = function(a,b,c){sortData(a,b,c);};
		ts.updatepager = function(a){updatepager(a);};
		ts.formatter = function ( rowId, cellval , colpos, rwdat, act){return formatter(rowId, cellval , colpos, rwdat, act);};
		$.extend(grid,{populate : function(){populate();}});
		this.grid = grid;
		ts.addXmlData = function(d) {addXmlData(d,ts.grid.bDiv);};
		ts.addJSONData = function(d) {addJSONData(d,ts.grid.bDiv);};
		populate();ts.p.hiddengrid=false;
		$(window).unload(function () {
			$(this).empty();
			this.grid = null;
			this.p = null;
		});
	});
};
$.fn.extend({
	getGridParam : function(pName) {
		var $t = this[0];
		if (!$t.grid) {return;}
		if (!pName) { return $t.p; }
		else {return $t.p[pName] ? $t.p[pName] : null;}
	},
	setGridParam : function (newParams){
		return this.each(function(){
			if (this.grid && typeof(newParams) === 'object') {$.extend(true,this.p,newParams);}
		});
	},
	getDataIDs : function () {
		var ids=[], i=0, len;
		this.each(function(){
			len = this.rows.length;
			if(len && len>0){
				while(i<len) {
					ids[i] = this.rows[i].id;
					i++;
				}
			}
		});
		return ids;
	},
	setSelection : function(selection,onsr,sd) {
		return this.each(function(){
			var $t = this, stat,pt, olr, ner, ia, tpsr;
			if(selection === undefined) return;
			onsr = onsr === false ? false : true;
			if(selection===false) {pt = sd;}
			else {pt=$($t.rows.namedItem(selection));}
			selection = $(pt).attr("id");
			if (!pt.html()) {return;}
			if($t.p.selrow && $t.p.scrollrows===true) {
				olr = $t.rows.namedItem($t.p.selrow).rowIndex;
				ner = $t.rows.namedItem(selection).rowIndex;
				if(ner >=0 ){
					if(ner > olr ) {
						scrGrid(ner,'d');
					} else {
						scrGrid(ner,'u');
					}
				}
			}
			if(!$t.p.multiselect) {
				if($(pt).attr("class") !== "subgrid") {
				if( $t.p.selrow ) {$("tr#"+$t.p.selrow.replace(".", "\\."),$t.grid.bDiv).removeClass("ui-state-highlight").attr("aria-selected","false") ;}
				$t.p.selrow = selection;
				$(pt).addClass("ui-state-highlight").attr("aria-selected","true");
				if( $t.p.onSelectRow && onsr) { $t.p.onSelectRow($t.p.selrow, true); }
				}
			} else {
				$t.p.selrow = selection;
				ia = $.inArray($t.p.selrow,$t.p.selarrrow);
				if (  ia === -1 ){ 
					if($(pt).attr("class") !== "subgrid") { $(pt).addClass("ui-state-highlight").attr("aria-selected","true");}
					stat = true;
					$("#jqg_"+$t.p.selrow.replace(".", "\\."),$t.rows).attr("checked",stat);
					$t.p.selarrrow.push($t.p.selrow);
					if( $t.p.onSelectRow && onsr) { $t.p.onSelectRow($t.p.selrow, stat); }
				} else {
					if($(pt).attr("class") !== "subgrid") { $(pt).removeClass("ui-state-highlight").attr("aria-selected","false");}
					stat = false;
					$("#jqg_"+$t.p.selrow.replace(".", "\\."),$t.rows).attr("checked",stat);
					$t.p.selarrrow.splice(ia,1);
					if( $t.p.onSelectRow && onsr) { $t.p.onSelectRow($t.p.selrow, stat); }
					tpsr = $t.p.selarrrow[0];
					$t.p.selrow = (tpsr === undefined) ? null : tpsr;
				}
			}
			function scrGrid(iR,tp){
				var ch = $($t.grid.bDiv)[0].clientHeight,
				st = $($t.grid.bDiv)[0].scrollTop,
				nROT = $t.rows[iR].offsetTop+$t.rows[iR].clientHeight,
				pROT = $t.rows[iR].offsetTop;
				if(tp == 'd') {
					if(nROT >= ch) { $($t.grid.bDiv)[0].scrollTop = st + nROT-pROT; }
				}
				if(tp == 'u'){
					if (pROT < st) { $($t.grid.bDiv)[0].scrollTop = st - nROT+pROT; }
				}
			}
		});
	},
	resetSelection : function(){
		return this.each(function(){
			var t = this, ind;
			if(!t.p.multiselect) {
				if(t.p.selrow) {
					$("tr#"+t.p.selrow.replace(".", "\\."),t.grid.bDiv).removeClass("ui-state-highlight");
					t.p.selrow = null;
				}
			} else {
				$(t.p.selarrrow).each(function(i,n){
					ind = t.rows.namedItem(n);
					$(ind).removeClass("ui-state-highlight");
					$("#jqg_"+n.replace(".", "\\."),ind).attr("checked",false);
				});
				$("#cb_jqg",t.grid.hDiv).attr("checked",false);
				t.p.selarrrow = [];
			}
			t.p.savedRow = [];
		});
	},
	getRowData : function( rowid ) {
		var res = {};
		this.each(function(){
			var $t = this,nm,ind;
			ind = $t.rows.namedItem(rowid);
			if(!ind) return res;
			$('td',ind).each( function(i) {
				nm = $t.p.colModel[i].name; 
				if ( nm !== 'cb' && nm !== 'subgrid') {
					if($t.p.treeGrid===true && nm == $t.p.ExpandColumn) {
						res[nm] = $.jgrid.htmlDecode($("span:first",this).html());
					} else {
						res[nm] = $.jgrid.htmlDecode($(this).html());
					}
				}
			});
		});
		return res;
	},
	delRowData : function(rowid) {
		var success = false, rowInd, ia, ri;
		this.each(function() {
			var $t = this;
			rowInd = $t.rows.namedItem(rowid);
			if(!rowInd) {return false;}
			else {
				ri = rowInd.rowIndex;
				$(rowInd).remove();
				$t.p.records--;
				$t.p.reccount--;
				$t.updatepager(true);
				success=true;
				if(rowid == $t.p.selrow) {$t.p.selrow=null;}
				ia = $.inArray(rowid,$t.p.selarrrow);
				if(ia != -1) {$t.p.selarrrow.splice(ia,1);}
			}
			if( ri == 0 && success ) {
				$t.updateColumns();
			}
			if( $t.p.altRows === true && success ) {
				var cn = $t.p.altclass;
				$($t.rows).each(function(i){
					if(i % 2 ==1) $(this).addClass(cn);
					else $(this).removeClass(cn);
				});
			}
		});
		return success;
	},
	setRowData : function(rowid, data) {
		var nm, success=false;
		this.each(function(){
			var t = this, vl, ind;
			if(!t.grid) {return false;}
			var ind = t.rows.namedItem(rowid);
			if(!ind) return false;
			if( data ) {
				$(this.p.colModel).each(function(i){
					nm = this.name;
					if( data[nm] != undefined) {
						vl = t.formatter( rowid, data[nm], i, data, 'edit');
						if(t.p.treeGrid===true && nm == t.p.ExpandColumn) {
							$("td:eq("+i+") > span:first",ind).html(vl).attr("title",$.jgrid.stripHtml(vl));
						} else {
							$("td:eq("+i+")",ind).html(vl).attr("title",$.jgrid.stripHtml(vl)); 
						}
						success = true;
					}
				});
			}
		});
		return success;
	},
	addRowData : function(rowid,data,pos,src) {
		if(!pos) {pos = "last";}
		var success = false, nm, row, gi=0, si=0, ni=0,sind, i, v, prp="";
		if(data) {
			this.each(function() {
				var t = this;
				rowid = typeof(rowid) != 'undefined' ? rowid+"": t.p.records+1;
				row = "<tr id=\""+rowid+"\" role=\"row\" class=\"ui-widget-content jqgrow\">";
				if(t.p.rownumbers===true){
					prp = t.formatCol(ni,1);
					row += "<td role=\"gridcell\" class=\"ui-state-default jqgrid-rownum\" "+prp+">0</td>";
					ni=1;
				}
				if(t.p.multiselect) {
					v = "<input type=\"checkbox\""+" id=\"jqg_"+rowid+"\" class=\"cbox\"/>";
					prp = t.formatCol(ni,1);
					row += "<td role=\"gridcell\" "+prp+">"+v+"</td>";
					gi = 1;
				}
				if(t.p.subGrid===true) {
					row += $(t).addSubGridCell(gi+ni,1);
					si=1;
				}
				for(i = gi+si+ni; i < this.p.colModel.length;i++){
					nm = this.p.colModel[i].name;
					v = t.formatter( rowid, data[nm], i, data, 'add');
					prp = t.formatCol(i,1);
					row += "<td role=\"gridcell\" "+prp+" title=\""+$.jgrid.stripHtml(v)+"\">"+v+"</td>";
				}
				row += "</tr>";
				if(t.p.subGrid===true) {
					row = $(row)[0]; 
					$(t).addSubGrid(row,gi+ni);
				}
				if(t.rows.length === 0){
					$("table:first",t.grid.bDiv).append(row);
				} else {
				switch (pos) {
					case 'last':
						$(t.rows[t.rows.length-1]).after(row);
						break;
					case 'first':
						$(t.rows[0]).before(row);
						break;
					case 'after':
						sind = t.rows.namedItem(src);
						sind != null ? $(t.rows[sind.rowIndex+1]).hasClass("ui-subgrid") ? $(t.rows[sind.rowIndex+1]).after(row) : $(sind).after(row) : "";
						break;
					case 'before':
						sind = t.rows.namedItem(src);
						sind != null ?	$(sind).before(row): "";
						break;
				}
				}
				t.p.records++;
				t.p.reccount++;
				if(pos==='first' || (pos==='before' && sind===0) ||  t.rows.length === 1 ){
					t.updateColumns();
				}
				if( t.p.altRows === true ) {
					var cn = t.p.altclass;
					if (pos == "last") {
						if (t.rows.length % 2 == 1)  {$(t.rows[t.rows.length-1]).addClass(cn);}
					} else {
						$(t.rows).each(function(i){
							if(i % 2 ==1) $(this).addClass(cn);
							else $(this).removeClass(cn);
						});
					}
				}
				try {t.p.afterInsertRow(rowid,data); } catch(e){}
				t.updatepager(true);
				success = true;
			});
		}
		return success;
	},
	footerData : function(action,data, format) {
		var nm, success=false, res={};
		function isEmpty(obj) { for(var i in obj) { return false; } return true; }
		if(typeof(action) == "undefined") action = "get";
		if(typeof(format) != "boolean") format  = true;
		action = action.toLowerCase();
		this.each(function(){
			var t = this, vl, ind;
			if(!t.grid || !t.p.footerrow) {return false;}
			if(action == "set") { if(isEmpty(data)) return false; }
			success=true;
			$(this.p.colModel).each(function(i){
				nm = this.name;
				if(action == "set") {
					if( data[nm] != undefined) {
						vl = format ? t.formatter( "", data[nm], i, data, 'edit') : data[nm];
						$("tr.footrow td:eq("+i+")",t.grid.sDiv).html(vl).attr("title",$.jgrid.stripHtml(vl)); 
						success = true;
					}
				} else if(action == "get") {
					res[nm] = $("tr.footrow td:eq("+i+")",t.grid.sDiv).html();
				}
			});
		});
		return action == "get" ? res : success;
	},
	ShowHideCol : function(colname,show) {
		return this.each(function() {
			var $t = this, fndh=false;
			if (!$t.grid ) {return;}
			if( typeof colname === 'string') {colname=[colname];}
			show = show !="none" ? "" : "none";
			var sw = show == "" ? true :false;
			$(this.p.colModel).each(function(i) {
				if ($.inArray(this.name,colname) !== -1 && this.hidden === sw) {
					$("tr",$t.grid.hDiv).each(function(){
						$("th:eq("+i+")",this).css("display",show);
					});
					$($t.rows).each(function(j){
						$("td:eq("+i+")",$t.rows[j]).css("display",show);
					});
					if($t.p.footerrow) $("td:eq("+i+")",$t.grid.sDiv).css("display", show);
					if(show == "none") $t.p.tblwidth -= this.width; else $t.p.tblwidth += this.width;
					this.hidden = !sw;
					fndh=true;
				}
			});
			if(fndh===true) {
				$("table:first",$t.grid.hDiv).width($t.p.tblwidth);
				$("table:first",$t.grid.bDiv).width($t.p.tblwidth);
				$t.grid.hDiv.scrollLeft = $t.grid.bDiv.scrollLeft;
				if($t.p.footerrow) {
					$("table:first",$t.grid.sDiv).width($t.p.tblwidth);
					$t.grid.sDiv.scrollLeft = $t.grid.bDiv.scrollLeft;
				}
			}
		});
	},
	hideCol : function (colname) {
		return this.each(function(){$(this).ShowHideCol(colname,"none");});
	},
	showCol : function(colname) {
		return this.each(function(){$(this).ShowHideCol(colname,"");});
	},
	setGridWidth : function(nwidth, shrink) {
		return this.each(function(){
			var $t = this, cw,
			initwidth = 0, brd=$t.p.cellLayout, lvc, vc=0, isSafari,hs=false, scw=$t.p.scrollOffset, aw, gw=0, tw=0,
			msw = $t.p.multiselectWidth, sgw=$t.p.subGridWidth, rnw=$t.p.rownumWidth, cl = $t.p.cellLayout,cr;
			if (!$t.grid ) {return;}
			if(typeof shrink != 'boolean') {
				shrink=$t.p.shrinkToFit;
			}
			if(isNaN(nwidth)) {return;}
			if(nwidth == $t.grid.width) {return;}
			else { $t.grid.width = $t.p.width = nwidth;}
			$("#gbox_"+$t.p.id).css("width",nwidth+"px");
			$("#gview_"+$t.p.id).css("width",nwidth+"px");
			$($t.grid.bDiv).css("width",nwidth+"px");
			$($t.grid.hDiv).css("width",nwidth+"px");
			if($t.p.pager ) {$($t.p.pager).css("width",nwidth+"px");}
			if($t.p.toolbar[0] === true){
				$($t.grid.uDiv).css("width",nwidth+"px");
				if($t.p.toolbar[1]=="both") {$($t.grid.ubDiv).css("width",nwidth+"px");}
			}
			if($t.p.footerrow) $($t.grid.sDiv).css("width",nwidth+"px");
			if(shrink ===false && $t.p.forceFit == true) {$t.p.forceFit=false;}			
			if(shrink===true) {
				$.each($t.p.colModel, function(i) {
					if(this.hidden===false){
						initwidth += parseInt(this.width,10);
						vc++;
					}
				});
				isSafari = $.browser.safari ? true : false;
				if (isSafari) { brd=0; msw +=cl; sgw += cl; rnw += cl;}
				if($t.p.multiselect) {tw = msw; gw = msw+brd; vc--;}
				if($t.p.subGrid) {tw += sgw;gw += sgw+brd; vc--;}
				if($t.p.rownumbers) { tw += rnw; gw += rnw+brd; vc--;}
				$t.p.tblwidth = initwidth;
				aw = nwidth-brd*vc-gw;
				if(!isNaN($t.p.height)) {
					if($($t.grid.bDiv)[0].clientHeight < $($t.grid.bDiv)[0].scrollHeight){
						hs = true;
						aw -= scw;
					}
				}
				initwidth =0;
				var cl = $t.grid.cols.length >0;
				$.each($t.p.colModel, function(i) {
					var tn = this.name;
					if(this.hidden === false && tn !== 'cb' && tn !== 'subgrid' && tn !== 'rn'){
						cw = Math.floor((aw)/($t.p.tblwidth-tw)*this.width);
						this.width =cw;
						initwidth += cw;
						$t.grid.headers[i].width=cw;
						$t.grid.headers[i].el.style.width=cw+"px";
						if($t.p.footerrow) $t.grid.footers[i].style.width = cw+"px";
						if(cl) $t.grid.cols[i].style.width = cw+"px";
						lvc = i;
					}
				});
				cr =0;
				if (hs && nwidth-gw-(initwidth+brd*vc) !== scw) {
					cr = nwidth-gw-(initwidth+brd*vc)-scw;
				} else if( Math.abs(nwidth-gw-(initwidth+brd*vc)) !== 1) {
					cr = nwidth-gw-(initwidth+brd*vc);
				}
				$t.p.colModel[lvc].width += cr;
				cw= $t.p.colModel[lvc].width;
				$t.grid.headers[lvc].width = cw;
				$t.grid.headers[lvc].el.style.width=cw+"px";
				if(cl>0) $t.grid.cols[lvc].style.width = cw+"px";
				$t.p.tblwidth = initwidth+tw+cr;
				$('table:first',$t.grid.bDiv).css("width",initwidth+tw+cr+"px");
				$('table:first',$t.grid.hDiv).css("width",initwidth+tw+cr+"px");
				$t.grid.hDiv.scrollLeft = $t.grid.bDiv.scrollLeft;
				if($t.p.footerrow) {
					$t.grid.footers[lvc].style.width = cw+"px";
					$('table:first',$t.grid.sDiv).css("width",initwidth+tw+cr+"px");
				}
			}
		});
	},
	setGridHeight : function (nh) {
		return this.each(function (){
			var $t = this;
			if(!$t.grid) {return;}
			$($t.grid.bDiv).css({height: nh+(isNaN(nh)?"":"px")});
			$t.p.height = nh;
		});
	},
	setCaption : function (newcap){
		return this.each(function(){
			this.p.caption=newcap;
			$("span.ui-jqgrid-title",this.grid.cDiv).html(newcap);
			$(this.grid.cDiv).show();
		});
	},
	setLabel : function(colname, nData, prop, attrp ){
		return this.each(function(){
			var $t = this, pos=-1;
			if(!$t.grid) {return;}
			if(isNaN(colname)) {
				$($t.p.colModel).each(function(i){
					if (this.name == colname) {
						pos = i;return false;
					}
				});
			} else {pos = parseInt(colname,10);}
			if(pos>=0) {
				var thecol = $("tr.ui-jqgrid-labels th:eq("+pos+")",$t.grid.hDiv);
				if (nData){
					$("div",thecol).html(nData);
					$t.p.colNames[pos] = nData;
				}
				if (prop) {
					if(typeof prop === 'string') {$(thecol).addClass(prop);} else {$(thecol).css(prop);}
				}
				if(typeof attrp === 'object') {$(thecol).attr(attrp);}
			}
		});
	},
	setCell : function(rowid,colname,nData,cssp,attrp) {
		return this.each(function(){
			var $t = this, pos =-1,v;
			if(!$t.grid) {return;}
			if(isNaN(colname)) {
				$($t.p.colModel).each(function(i){
					if (this.name == colname) {
						pos = i;return false;
					}
				});
			} else {pos = parseInt(colname,10);}
			if(pos>=0) {
				var ind = $t.rows.namedItem(rowid);
				if (ind){
					var tcell = $("td:eq("+pos+")",ind);
					if(nData !== "") {
						v = $t.formatter(rowid, nData, pos,ind,'edit');
						$(tcell).html(v).attr("title",$.jgrid.stripHtml(v));
					}
					if (cssp){
						if(typeof cssp === 'string') {$(tcell).addClass(cssp);} else {$(tcell).css(cssp);}
					}
					if(typeof attrp === 'object') {$(tcell).attr(attrp);}
				}
			}
		});
	},
	getCell : function(rowid,col) {
		var ret = false;
		this.each(function(){
			var $t=this, pos=-1;
			if(!$t.grid) {return;}
			if(isNaN(col)) {
				$($t.p.colModel).each(function(i){
					if (this.name === col) {
						pos = i;return false;
					}
				});
			} else {pos = parseInt(col,10);}
			if(pos>=0) {
				var ind = $t.rows.namedItem(rowid);
				if(ind) {
					ret = $.jgrid.htmlDecode($("td:eq("+pos+")",ind).html());
				}
			}
		});
		return ret;
	},
	getCol : function (col) {
		var ret = [];
		this.each(function(){
			var $t=this, pos=-1;
			if(!$t.grid) {return;}
			if(isNaN(col)) {
				$($t.p.colModel).each(function(i){
					if (this.name === col) {
						pos = i;return false;
					}
				});
			} else {pos = parseInt(col,10);}
			if(pos>=0) {
				var ln = $t.rows.length, i =0;
				if (ln && ln>0){
					while(i<ln){
						ret[i] = $t.rows[i].cells[pos].innerHTML;
						i++;
					}
				}
			}
		});
		return ret;
	},
	clearGridData : function(clearfooter) {
		return this.each(function(){
			var $t = this;
			if(!$t.grid) {return;}
			if(typeof clearfooter != 'boolean') clearfooter = false;
			$("tbody:first tr", $t.grid.bDiv).remove();
			if($t.p.footerrow && clearfooter) $(".ui-jqgrid-ftable td",$t.grid.sDiv).html("&nbsp;");
			$t.p.selrow = null; $t.p.selarrrow= []; $t.p.savedRow = [];
			$t.p.records = 0;$t.p.page='0';$t.p.lastpage='0';$t.p.reccount=0;
			$t.updatepager(true);
		});
	},
	getInd : function(rowid,rc){
		var ret =false,rw;
		this.each(function(){
			rw = this.rows.namedItem(rowid);
			if(rw) {
				ret = rc===true ? rw: rw.rowIndex;
			}
		});
		return ret;
	}
});
})(jQuery);
