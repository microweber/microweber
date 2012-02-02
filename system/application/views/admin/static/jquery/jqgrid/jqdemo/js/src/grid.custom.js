;(function($){
/**
 * jqGrid extension for custom methods
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
$.fn.extend({
	getColProp : function(colname){
		var ret ={}, $t = this[0];
		if ( !$t.grid ) { return; }
		var cM = $t.p.colModel;
		for ( var i =0;i<cM.length;i++ ) {
			if ( cM[i].name == colname ) {
				ret = cM[i];
				break;
			}
		};
		return ret;
	},
	setColProp : function(colname, obj){
		//do not set width will not work
		return this.each(function(){
			if ( this.grid ) {
				if ( obj ) {
					var cM = this.p.colModel;
					for ( var i =0;i<cM.length;i++ ) {
						if ( cM[i].name == colname ) {
							$.extend(this.p.colModel[i],obj);
							break;
						}
					}
				}
			}
		});
	},
	sortGrid : function(colname,reload){
		return this.each(function(){
			var $t=this,idx=-1;
			if ( !$t.grid ) { return;}
			if ( !colname ) { colname = $t.p.sortname; }
			for ( var i=0;i<$t.p.colModel.length;i++ ) {
				if ( $t.p.colModel[i].index == colname || $t.p.colModel[i].name==colname ) {
					idx = i;
					break;
				}
			}
			if ( idx!=-1 ){
				var sort = $t.p.colModel[idx].sortable;
				if ( typeof sort !== 'boolean' ) { sort =  true; }
				if ( typeof reload !=='boolean' ) { reload = false; }
				if ( sort ) { $t.sortData("jqgh_"+colname, idx, reload); }
			}
		});
	},
	GridDestroy : function () {
		return this.each(function(){
			if ( this.grid ) { 
				if ( this.p.pager ) { // if not part of grid
					$(this.p.pager).remove();
				}
				var gid = this.id;
				try {
					$("#gbox_"+gid).remove();
				} catch (_) {}
			}
		});
	},
	GridUnload : function(){
		return this.each(function(){
			if ( !this.grid ) {return;}
			var defgrid = {id: $(this).attr('id'),cl: $(this).attr('class')};
			if (this.p.pager) {
				$(this.p.pager).empty().removeClass("ui-state-default ui-jqgrid-pager corner-bottom");
			}
			var newtable = document.createElement('table');
			$(newtable).attr({id:defgrid['id']});
			newtable.className = defgrid['cl'];
			var gid = this.id;
			$(newtable).removeClass("ui-jqgrid-btable");
			if( $(this.p.pager).parents("#gbox_"+gid).length === 1 ) {
				$(newtable).insertBefore("#gbox_"+gid).show();
				$(this.p.pager).insertBefore("#gbox_"+gid);
			} else {
				$(newtable).insertBefore("#gbox_"+gid).show();
			}
			$("#gbox_"+gid).remove();
		});
	},
	updateGridRows : function (data, rowidname, jsonreader) {
		var nm, success=false;
		this.each(function(){
			var t = this, vl, ind, srow, sid;
			if(!t.grid) {return false;}
			if(!rowidname) rowidname = "id";
			if( data  && data.length >0 ) {
				$(data).each(function(j){
					srow = this;
					ind = t.rows.namedItem(srow[rowidname]);
					if(ind) {
						sid = srow[rowidname];
						if(jsonreader === true){
							if(t.p.jsonReader.repeatitems === true) {
								if(t.p.jsonReader.cell) {srow = srow[t.p.jsonReader.cell];}
								for (var k=0;k<srow.length;k++) {
									vl = t.formatter( sid, srow[k], k, srow, 'edit');
									if(t.p.treeGrid===true && nm == t.p.ExpandColumn) {
										$("td:eq("+k+") > span:first",ind).html(vl).attr("title",$.jgrid.stripHtml(vl));
									} else {
										$("td:eq("+k+")",ind).html(vl).attr("title",$.jgrid.stripHtml(vl)); 
									}
								}
								success = true;
								return true;
							}
						} 
						$(t.p.colModel).each(function(i){
							nm = jsonreader===true ? this.jsonmap || this.name :this.name;
							if( srow[nm] != undefined) {
								vl = t.formatter( sid, srow[nm], i, srow, 'edit');
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
			}
		});
		return success;
	},
	filterGrid : function(gridid,p){
		p = $.extend({
			gridModel : false,
			gridNames : false,
			gridToolbar : false,
			filterModel: [], // label/name/stype/defval/surl/sopt
			formtype : "horizontal", // horizontal/vertical
			autosearch: true, // if set to false a serch button should be enabled.
			formclass: "filterform",
			tableclass: "filtertable",
			buttonclass: "filterbutton",
			searchButton: "Search",
			clearButton: "Clear",
			enableSearch : false,
			enableClear: false,
			beforeSearch: null,
			afterSearch: null,
			beforeClear: null,
			afterClear: null,
			url : '',
			marksearched: true
		},p  || {});
		return this.each(function(){
			var self = this;
			this.p = p;
			if(this.p.filterModel.length == 0 && this.p.gridModel===false) { alert("No filter is set"); return;}
			if( !gridid) {alert("No target grid is set!"); return;}
			this.p.gridid = gridid.indexOf("#") != -1 ? gridid : "#"+gridid;
			var gcolMod = $(this.p.gridid).getGridParam('colModel');
			if(gcolMod) {
				if( this.p.gridModel === true) {
					var thegrid = $(this.p.gridid)[0];
					var sh;
					// we should use the options search, edittype, editoptions
					// additionally surl and defval can be added in grid colModel
					$.each(gcolMod, function (i,n) {
						var tmpFil = [];
						this.search = this.search === false ? false : true;
						if(this.editrules && this.editrules.searchhidden === true) {
							sh = true;
						} else {
							if(this.hidden === true ) {
								sh = false;
							} else {
								sh = true;
							}
						}
						if( this.search === true && sh === true) {
							if(self.p.gridNames===true) {
								tmpFil.label = thegrid.p.colNames[i];
							} else {
								tmpFil.label = '';
							}
							tmpFil.name = this.name;
							tmpFil.index = this.index || this.name;
							// we support only text and selects, so all other to text
							tmpFil.stype = this.edittype || 'text';
							if(tmpFil.stype != 'select' ) {
								tmpFil.stype = 'text';
							}
							tmpFil.defval = this.defval || '';
							tmpFil.surl = this.surl || '';
							tmpFil.sopt = this.editoptions || {};
							tmpFil.width = this.width;
							self.p.filterModel.push(tmpFil);
						}
					});
				} else {
					$.each(self.p.filterModel,function(i,n) {
						for(var j=0;j<gcolMod.length;j++) {
							if(this.name == gcolMod[j].name) {
								this.index = gcolMod[j].index || this.name;
								break;
							}
						}
						if(!this.index) {
							this.index = this.name;
						}
					});
				}
			} else {
				alert("Could not get grid colModel"); return;
			}
			var triggerSearch = function() {
				var sdata={}, j=0, v;
				var gr = $(self.p.gridid)[0], nm;
                gr.p.searchdata = {};
				if($.isFunction(self.p.beforeSearch)){self.p.beforeSearch();}
				$.each(self.p.filterModel,function(i,n){
                    nm = this.index;
					switch (this.stype) {
						case 'select' :
							v = $("select[name="+nm+"]",self).val();
							if(v) {
								sdata[nm] = v;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
                                try {
                                    delete gr.p.postData[this.index];
                                } catch (e) {}
							}
							break;
						default:
							v = $("input[name="+nm+"]",self).val();
							if(v) {
								sdata[nm] = v;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
                                try {
                                    delete gr.p.postData[this.index];
                                } catch(e) {}
							}
					}
				});
				var sd =  j>0 ? true : false;
                $.extend(gr.p.postData,sdata);
				var saveurl;
				if(self.p.url) {
					saveurl = $(gr).getGridParam('url');
					$(gr).setGridParam({url:self.p.url});
				}
				$(gr).setGridParam({search:sd,page:1}).trigger("reloadGrid");
				if(saveurl) {$(gr).setGridParam({url:saveurl});}
				if($.isFunction(self.p.afterSearch)){self.p.afterSearch();}
			};
			var clearSearch = function(){
				var sdata={}, v, j=0;
				var gr = $(self.p.gridid)[0], nm;
				if($.isFunction(self.p.beforeClear)){self.p.beforeClear();}
				$.each(self.p.filterModel,function(i,n){
                    nm = this.index;
					v = (this.defval) ? this.defval : "";
					if(!this.stype){this.stype=='text';}
					switch (this.stype) {
						case 'select' :
							var v1;
							$("select[name="+nm+"] option",self).each(function (i){
                                if(i==0) this.selected = true;
								if ($(this).text() == v) {
									this.selected = true;
									v1 = $(this).val();
									return false;
								}
							});
							if(v1) {
								// post the key and not the text
								sdata[nm] = v1;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
                                try {
                                    delete gr.p.postData[this.index];
                                } catch (e) {}
							}
							break;
						case 'text':
							$("input[name="+nm+"]",self).val(v);
							if(v) {
								sdata[nm] = v;
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).addClass("dirty-cell");
								}
								j++;
							} else {
								if(self.p.marksearched){
									$("#jqgh_"+this.name,gr.grid.hDiv).removeClass("dirty-cell");
								}
                                try {
                                    delete gr.p.postData[this.index];
                                } catch (e) {}
							}
                            break;
					}
				});
				var sd =  j>0 ? true : false;
                $.extend(gr.p.postData,sdata);
				var saveurl;
				if(self.p.url) {
					saveurl = $(gr).getGridParam('url');
					$(gr).setGridParam({url:self.p.url});
				}
				$(gr).setGridParam({search:sd,page:1}).trigger("reloadGrid");
				if(saveurl) {$(gr).setGridParam({url:saveurl});}
				if($.isFunction(self.p.afterClear)){self.p.afterClear();}
			};
			var formFill = function(){
				var tr = document.createElement("tr");
				var tr1, sb, cb,tl,td, td1;
				if(self.p.formtype=='horizontal'){
					$(tbl).append(tr);
				}
				$.each(self.p.filterModel,function(i,n){
					tl = document.createElement("td");
					$(tl).append("<label for='"+this.name+"'>"+this.label+"</label>");
					td = document.createElement("td");
					var $t=this;
					if(!this.stype) { this.stype='text';}
					switch (this.stype)
					{
					case "select":
						if(this.surl) {
							// data returned should have already constructed html select
							$(td).load(this.surl,function(){
								if($t.defval) $("select",this).val($t.defval);
								$("select",this).attr({name:$t.index || $t.name, id: "sg_"+$t.name});
								if($t.sopt) $("select",this).attr($t.sopt);
								if(self.p.gridToolbar===true && $t.width) {
									$("select",this).width($t.width);
								}
								if(self.p.autosearch===true){
									$("select",this).change(function(e){
										triggerSearch();
										return false;
									});
								}
							});
						} else {
							// sopt to construct the values
							if($t.sopt.value) {
								var oSv = $t.sopt.value;
								var elem = document.createElement("select");
								$(elem).attr({name:$t.index || $t.name, id: "sg_"+$t.name}).attr($t.sopt);
								if(typeof oSv === "string") {
									var so = oSv.split(";"), sv, ov;
									for(var k=0; k<so.length;k++){
										sv = so[k].split(":");
										ov = document.createElement("option");
										ov.value = sv[0]; ov.innerHTML = sv[1];
										if (sv[1]==$t.defval) ov.selected ="selected";
										elem.appendChild(ov);
									}
								} else if(typeof oSv === "object" ) {
									for ( var key in oSv) {
										i++;
										ov = document.createElement("option");
										ov.value = key; ov.innerHTML = oSv[key];
										if (oSv[key]==$t.defval) ov.selected ="selected";
										elem.appendChild(ov);
									}
								}
								if(self.p.gridToolbar===true && $t.width) {
									$(elem).width($t.width);
								}
								$(td).append(elem);
								if(self.p.autosearch===true){
									$(elem).change(function(e){
										triggerSearch();
										return false;
									});
								}
							}
						}
						break;
					case 'text':
						var df = this.defval ? this.defval: "";
						$(td).append("<input type='text' name='"+(this.index || this.name)+"' id='sg_"+this.name+"' value='"+df+"'/>");
						if($t.sopt) $("input",td).attr($t.sopt);
						if(self.p.gridToolbar===true && $t.width) {
							if($.browser.msie) {
								$("input",td).width($t.width-4);
							} else {
								$("input",td).width($t.width-2);
							}
						}
						if(self.p.autosearch===true){
							$("input",td).keypress(function(e){
								var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
								if(key == 13){
									triggerSearch();
									return false;
								}
								return this;
							});
						}
						break;
					}
					if(self.p.formtype=='horizontal'){
						if(self.p.gridToolbar===true && self.p.gridNames===false) {
							$(tr).append(td);
						} else {
							$(tr).append(tl).append(td);
						}
						$(tr).append(td);
					} else {
						tr1 = document.createElement("tr");
						$(tr1).append(tl).append(td);
						$(tbl).append(tr1);
					}
				});
				td = document.createElement("td");
				if(self.p.enableSearch === true){
					sb = "<input type='button' id='sButton' class='"+self.p.buttonclass+"' value='"+self.p.searchButton+"'/>";
					$(td).append(sb);
					$("input#sButton",td).click(function(){
						triggerSearch();
						return false;
					});
				}
				if(self.p.enableClear === true) {
					cb = "<input type='button' id='cButton' class='"+self.p.buttonclass+"' value='"+self.p.clearButton+"'/>";
					$(td).append(cb);
					$("input#cButton",td).click(function(){
						clearSearch();
						return false;
					});
				}
				if(self.p.enableClear === true || self.p.enableSearch === true) {
					if(self.p.formtype=='horizontal') {
						$(tr).append(td);
					} else {
						tr1 = document.createElement("tr");
						$(tr1).append("<td>&nbsp;</td>").append(td);
						$(tbl).append(tr1);
					}
				}
			};
			var frm = $("<form name='SearchForm' style=display:inline;' class='"+this.p.formclass+"'></form>");
			var tbl =$("<table class='"+this.p.tableclass+"' cellspacing='0' cellpading='0' border='0'><tbody></tbody></table>");
			$(frm).append(tbl);
			formFill();
			$(this).append(frm);
			this.triggerSearch = function () {triggerSearch();};
			this.clearSearch = function () {clearSearch();};
		});
	},
	filterToolbar : function(p){
		p = $.extend({
			autosearch: true, 
			beforeSearch: null,
			afterSearch: null,
			beforeClear: null,
			afterClear: null,
			searchurl : ''
		},p  || {});
		return this.each(function(){
			var $t = this;
			var triggerToolbar = function() {
				var sdata={}, j=0, v, nm;
                $t.p.searchdata = {};
				if($.isFunction(p.beforeSearch)){p.beforeSearch();}
				$.each($t.p.colModel,function(i,n){
                    nm = this.index || this.name;
					switch (this.stype) {
						case 'select' :
							v = $("select[name="+nm+"]",$t.grid.hDiv).val();
							if(v) {
								sdata[nm] = v;
								j++;
							} else {
                                try {
                                    delete $t.p.postData[nm];
                                } catch (e) {}
                            }
							break;
						case 'text':
							v = $("input[name="+nm+"]",$t.grid.hDiv).val();
							if(v) {
								sdata[nm] = v;
								j++;
							} else {
                                try {
                                    delete $t.p.postData[nm];
                                } catch (e) {}
                            }
                            break;
					}
				});
				var sd =  j>0 ? true : false;
                $.extend($t.p.postData,sdata);
				var saveurl;
				if($t.p.searchurl) {
					saveurl = $t.p.url;
					$($t).setGridParam({url:$t.p.searchurl});
				}
				$($t).setGridParam({search:sd,page:1}).trigger("reloadGrid");
				if(saveurl) {$($t).setGridParam({url:saveurl});}
				if($.isFunction(p.afterSearch)){p.afterSearch();}
			};
			var clearToolbar = function(){
				var sdata={}, v, j=0, nm;
				if($.isFunction(p.beforeClear)){p.beforeClear();}
				$.each($t.p.colModel,function(i,n){
					v = (this.searchoptions && this.searchoptions.defaultValue) ? this.searchoptions.defaultValue : "";
                    nm = this.index || this.name;
					switch (this.stype) {
						case 'select' :
							var v1;
							$("select[name="+nm+"] option",$t.grid.hDiv).each(function (i){
                                if(i==0) this.selected = true;
								if ($(this).text() == v) {
									this.selected = true;
									v1 = $(this).val();
									return false;
								}
							});
                            if (v1) {
                                // post the key and not the text
                                sdata[nm] = v1;
                                j++;
                            } else {
                                try {
                                    delete $t.p.postData[nm];
                                } catch(e) {}
                            }
							break;
						case 'text':
							$("input[name="+nm+"]",$t.grid.hDiv).val(v);
							if(v) {
								sdata[nm] = v;
								j++;
							} else {
                                try {
                                    delete $t.p.postData[nm];
                                } catch (e){}
                            }
                            break;
					}
				});
				var sd =  j>0 ? true : false;
                $.extend($t.p.postData,sdata);
				var saveurl;
				if($t.p.searchurl) {
					saveurl = $t.p.url;
					$($t).setGridParam({url:$t.p.searchurl});
				}
				$($t).setGridParam({search:sd,page:1}).trigger("reloadGrid");
				if(saveurl) {$($t).setGridParam({url:saveurl});}
				if($.isFunction(p.afterClear)){p.afterClear();}
			};
			var toggleToolbar = function(){
                var trow = $("tr.ui-search-toolbar",$t.grid.hDiv);
                if(trow.css("display")=='none') trow.show();
                else trow.hide();
			};
			// create the row
			function bindEvents(selector, events) {
				var jElem = $(selector);
				if (jElem[0] != null) {
				    jQuery.each(events, function() {
				        if (this.data != null)
				            jElem.bind(this.type, this.data, this.fn);
				        else
				            jElem.bind(this.type, this.fn);
				    });
				}				
			}
			var tr = $("<tr class='ui-search-toolbar' role='rowheader'></tr>"), th,thd, soptions;
			$.each($t.p.colModel,function(i,n){
				var cm=this;
				th = $("<th role='columnheader' class='ui-state-default ui-th-column'></th>");
				thd = $("<div style='width:100%;position:relative;'></div>");
				if(this.hidden===true) { $(th).css("display","none");}
				this.search = this.search === false ? false : true;
				if(typeof this.stype == 'undefined' ) {this.stype='text';}
				soptions = $.extend({},this.searchoptions || {});
				if(this.search){
					switch (this.stype)
					{
					case "select":
						if(this.surl) {
							// data returned should have already have constructed html select
							$(thd).load(this.surl,{_nsd : (new Date().getTime())},function(){
								if(soptions.defaultValue) $("select",this).val(soptions.defaultValue);
								$("select",this).attr({name:cm.index || cm.name, id: "gs_"+cm.name});
								if(soptions.attr) {$("select",this).attr(soptions.attr);}
								$("select",this).css({width: "100%"});
								// preserve autoserch
								if(soptions.dataInit != null) soptions.dataInit($("select",this)[0]);
								if(soptions.dataEvents != null) bindEvents($("select",this)[0],soptions.dataEvents);
								if(p.autosearch===true){
									$("select",this).change(function(e){
										triggerToolbar();
										return false;
									});
								}
							});
						} else {
							if(cm.editoptions && cm.editoptions.value) {
								var oSv = cm.editoptions.value,
								elem = document.createElement("select");
								elem.style.width = "100%";
								$(elem).attr({name:cm.index || cm.name, id: "gs_"+cm.name});
								if(typeof oSv === "string") {
									var so = oSv.split(";"), sv, ov;
									for(var k=0; k<so.length;k++){
										sv = so[k].split(":");
										ov = document.createElement("option");
										ov.value = sv[0]; ov.innerHTML = sv[1];
										elem.appendChild(ov);
									}
								} else if(typeof oSv === "object" ) {
									for ( var key in oSv) {
										i++;
										ov = document.createElement("option");
										ov.value = key; ov.innerHTML = oSv[key];
										elem.appendChild(ov);
									}
								}
								if(soptions.defaultValue) $(elem).val(soptions.defaultValue);
								if(soptions.attr) {$(elem).attr(soptions.attr);}
								if(soptions.dataInit != null) soptions.dataInit(elem);
								if(soptions.dataEvents != null) bindEvents(elem, soptions.dataEvents);
								$(thd).append(elem);
								if(p.autosearch===true){
									$(elem).change(function(e){
										triggerToolbar();
										return false;
									});
								}
							}
						}
						break;
					case 'text':
						var df = soptions.defaultValue ? soptions.defaultValue: "";
						$(thd).append("<input type='text' style='width:95%;padding:0px;' name='"+(cm.index || cm.name)+"' id='gs_"+cm.name+"' value='"+df+"'/>");
						if(soptions.attr) {$("input",thd).attr(soptions.attr);}
						if(soptions.dataInit != null) soptions.dataInit($("input",thd)[0]);
						if(soptions.dataEvents != null) bindEvents($("input",thd)[0], soptions.dataEvents);
						if(p.autosearch===true){
							$("input",thd).keypress(function(e){
								var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
								if(key == 13){
									triggerToolbar();
									return false;
								}
								return this;
							});
						}
						break;
					}
				}
				$(th).append(thd);
				$(tr).append(th);
			});
			$("table thead",$t.grid.hDiv).append(tr);
			this.triggerToolbar = function () {triggerToolbar();};
			this.clearToolbar = function () {clearToolbar();};
			this.toggleToolbar = function() {toggleToolbar();};
		});
	}
});
})(jQuery);