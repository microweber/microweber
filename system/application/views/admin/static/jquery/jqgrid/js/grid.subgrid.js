;(function($){
/**
 * jqGrid extension for SubGrid Data
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.fn.extend({
setSubGrid : function () {
	return this.each(function (){
		var $t = this, cm;
		$t.p.colNames.unshift("");
		$t.p.colModel.unshift({name:'subgrid',width: $.browser.safari ?  $t.p.subGridWidth+$t.p.cellLayout : $t.p.subGridWidth,sortable: false,resizable:false,hidedlg:true,search:false});
		cm = $t.p.subGridModel;
		if(cm[0]) {
			cm[0].align = $.extend([],cm[0].align || []);
			for(i=0;i<cm[0].name.length;i++) { cm[0].align[i] = cm[0].align[i] || 'left';}
		}
	});
},
addSubGridCell :function (pos,iRow) {
	var prp='',gv;
	this.each(function(){
		prp = this.formatCol(pos,iRow);
		gv = this.p.gridview;
	});
	if( gv === false ){
		return "<td role='grid' class='ui-sgcollapsed sgcollapsed' "+prp+"><a href='javascript:void(0);'><span class='ui-icon ui-icon-plus'></span></a></td>";
	} else  {
		return "<td role='grid' " +prp +"></td>";
	}
},
addSubGrid : function(t,pos) {
	return this.each(function(){
		var ts = this;
		if (!ts.grid ) { return; }
		var res,_id, pID,atd, nhc, subdata, bfsc;
		$("td:eq("+pos+")",t).click( function(e) {
			if($(this).hasClass("sgcollapsed")) {
				pID = ts.p.id;
				res = $(this).parent();
				atd = pos >=1 ? "<td colspan='"+pos+"'>&nbsp;</td>":"";
				_id = $(res).attr("id");
				bfsc =true;
				if($.isFunction(ts.p.subGridBeforeExpand)) {
					bfsc = ts.p.subGridBeforeExpand(pID+"_"+_id,_id);
				}
				if(bfsc === false) {return false;}
				nhc = 0;
				$.each(ts.p.colModel,function(i,v){
					if(this.hidden === true || this.name == 'rn' || this.name == 'cb') {nhc++;}
				});
				subdata = "<tr role='row' class='ui-subgrid'>"+atd+"<td><span class='ui-icon ui-icon-carat-1-sw'/></td><td colspan='"+parseInt(ts.p.colNames.length-1-nhc)+"' class='ui-widget-content subgrid-data'><div id="+pID+"_"+_id+" class='tablediv'>";
				$(this).parent().after( subdata+ "</div></td></tr>" );
				if( $.isFunction(ts.p.subGridRowExpanded)) {
					ts.p.subGridRowExpanded(pID+"_"+ _id,_id);
				} else {
					populatesubgrid(res);
				}
				$(this).html("<a href='javascript:void(0);'><span class='ui-icon ui-icon-minus'></span></a>").removeClass("sgcollapsed").addClass("sgexpanded");
			} else if($(this).hasClass("sgexpanded")) {
				bfsc = true;
				if( $.isFunction(ts.p.subGridRowColapsed)) {
					res = $(this).parent();
					_id = $(res).attr("id");
					bfsc = ts.p.subGridRowColapsed(pID+"_"+_id,_id );
				};
				if(bfsc===false) {return false;}
				$(this).parent().next().remove(".ui-subgrid");
				$(this).html("<a href='javascript:void(0);'><span class='ui-icon ui-icon-plus'></span></a>").removeClass("sgexpanded").addClass("sgcollapsed");
			}
			return false;
		});
		//-------------------------
		var populatesubgrid = function( rd ) {
			var res,sid,dp, i, j;
			sid = $(rd).attr("id");
			dp = {id:sid, nd_: (new Date().getTime())};
			if(!ts.p.subGridModel[0]) { return false; }
			if(ts.p.subGridModel[0].params) {
				for(j=0; j < ts.p.subGridModel[0].params.length; j++) {
					for(i=0; i<ts.p.colModel.length; i++) {
						if(ts.p.colModel[i].name == ts.p.subGridModel[0].params[j]) {
							dp[ts.p.colModel[i].name]= $("td:eq("+i+")",rd).text().replace(/\&nbsp\;/ig,'');
						}
					}
				}
			}
			if(!ts.grid.hDiv.loading) {
				ts.grid.hDiv.loading = true;
				$("#load_"+ts.p.id).show();
				if(!ts.p.subgridtype) ts.p.subgridtype = ts.p.datatype;
				if($.isFunction(ts.p.subgridtype)) {ts.p.subgridtype(dp);}
				switch(ts.p.subgridtype) {
					case "xml":
					$.ajax({type:ts.p.mtype, url: ts.p.subGridUrl, dataType:"xml",data: dp, complete: function(sxml) { subGridXml(sxml.responseXML, sid); sxml=null; } });
					break;
					case "json":
					$.ajax({type:ts.p.mtype, url: ts.p.subGridUrl, dataType:"text",data: dp, complete: function(json) { subGridJson($.jgrid.parse(json.responseText),sid); json = null;} });
					break;
				}
			}
			return false;
		};
		var subGridCell = function(trdiv,cell,pos){
			var tddiv = $("<td align='"+ts.p.subGridModel[0].align[pos]+"'></td>").html(cell);
			$(trdiv).append(tddiv);
		};
		var subGridXml = function(sjxml, sbid){
			var tddiv, i, cur, sgmap,
			dummy = $("<table cellspacing='0' cellpadding='0' border='0'><tbody></tbody></table>"),
			trdiv = $("<tr></tr>");
			for (i = 0; i<ts.p.subGridModel[0].name.length; i++) {
				tddiv = $("<th class='ui-state-default ui-th-column'></th>");
				$(tddiv).html(ts.p.subGridModel[0].name[i]);
				$(tddiv).width( ts.p.subGridModel[0].width[i]);
				$(trdiv).append(tddiv);
			}
			$(dummy).append(trdiv);
			if (sjxml){
				sgmap = ts.p.xmlReader.subgrid;
				$(sgmap.root+" "+sgmap.row, sjxml).each( function(){
					trdiv = $("<tr class='ui-widget-content ui-subtblcell'></tr>");
					if(sgmap.repeatitems === true) {
						$(sgmap.cell,this).each( function(i) {
							subGridCell(trdiv, $(this).text() || '&nbsp;',i);
						});
					} else {
						var f = ts.p.subGridModel[0].mapping;
						if (f) {
							for (i=0;i<f.length;i++) {
								subGridCell(trdiv, $(f[i],this).text() || '&nbsp;',i);
							}
						}
					}
					$(dummy).append(trdiv);
				});
			}
			var pID = $("table:first",ts.grid.bDiv).attr("id")+"_";
			$("#"+pID+sbid).append(dummy);
			ts.grid.hDiv.loading = false;
			$("#load_"+ts.p.id).hide();
			return false;
		};
		var subGridJson = function(sjxml, sbid){
			var tddiv,result , i,cur, sgmap,
			dummy = $("<table cellspacing='0' cellpadding='0' border='0'><tbody></tbody></table>"),
			trdiv = $("<tr></tr>");
			for (i = 0; i<ts.p.subGridModel[0].name.length; i++) {
				tddiv = $("<th class='ui-state-default ui-th-column'></th>");
				$(tddiv).html(ts.p.subGridModel[0].name[i]);
				$(tddiv).width( ts.p.subGridModel[0].width[i]);
				$(trdiv).append(tddiv);
			}
			$(dummy).append(trdiv);
			if (sjxml){
				sgmap = ts.p.jsonReader.subgrid;
				result = sjxml[sgmap.root];
				if ( typeof result !== 'undefined' ) {
					for (i=0;i<result.length;i++) {
						cur = result[i];
						trdiv = $("<tr class='ui-widget-content ui-subtblcell'></tr>");
						if(sgmap.repeatitems === true) {
							if(sgmap.cell) { cur=cur[sgmap.cell]; }
							for (var j=0;j<cur.length;j++) {
								subGridCell(trdiv, cur[j] || '&nbsp;',j);
							}
						} else {
							var f = ts.p.subGridModel[0].mapping;
							if(f.length) {
								for (var j=0;j<f.length;j++) {
									subGridCell(trdiv, cur[f[j]] || '&nbsp;',j);
								}
							}
						}
						$(dummy).append(trdiv);
					}
				}
			}
			var pID = $("table:first",ts.grid.bDiv).attr("id")+"_";
			$("#"+pID+sbid).append(dummy);
			ts.grid.hDiv.loading = false;
			$("#load_"+ts.p.id).hide();
			return false;
		};
		ts.subGridXml = function(xml,sid) {subGridXml(xml,sid);};
		ts.subGridJson = function(json,sid) {subGridJson(json,sid);};
	});
},
expandSubGridRow : function(rowid) {
	return this.each(function () {
		var $t = this;
		if(!$t.grid && !rowid) {return;}
		if($t.p.subGrid===true) {
			var rc = $(this).getInd(rowid,true);
			if(rc) {
				var sgc = $("td.sgcollapsed",rc)[0];
				if(sgc) {
					$(sgc).trigger("click");
				}
			}
		}
	});
},
collapseSubGridRow : function(rowid) {
	return this.each(function () {
		var $t = this;
		if(!$t.grid && !rowid) {return;}
		if($t.p.subGrid===true) {
			var rc = $(this).getInd(rowid,true);
			if(rc) {
				var sgc = $("td.sgexpanded",rc)[0];
				if(sgc) {
					$(sgc).trigger("click");
				}
			}
		}
	});
},
toggleSubGridRow : function(rowid) {
	return this.each(function () {
		var $t = this;
		if(!$t.grid && !rowid) {return;}
		if($t.p.subGrid===true) {
			var rc = $(this).getInd(rowid,true);
			if(rc) {
				var sgc = $("td.sgcollapsed",rc)[0];
				if(sgc) {
					$(sgc).trigger("click");
				} else {
					sgc = $("td.sgexpanded",rc)[0];
					if(sgc) {
						$(sgc).trigger("click");
					}
				}
			}
		}
	});
}
});
})(jQuery);
