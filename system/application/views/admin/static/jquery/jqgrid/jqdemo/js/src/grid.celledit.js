;(function($){
/*
**
 * jqGrid extension for cellediting Grid Data
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/ 
/**
 * all events and options here are aded anonynous and not in the base grid
 * since the array is to big. Here is the order of execution.
 * From this point we use jQuery isFunction
 * formatCell
 * beforeEditCell,
 * onSelectCell (used only for noneditable cels)
 * afterEditCell,
 * beforeSaveCell, (called before validation of values if any)
 * beforeSubmitCell (if cellsubmit remote (ajax))
 * afterSubmitCell(if cellsubmit remote (ajax)),
 * afterSaveCell,
 * errorCell,
 * Options
 * cellsubmit (remote,clientArray) (added in grid options)
 * cellurl
* */
$.fn.extend({
	editCell : function (iRow,iCol, ed){
		return this.each(function (){
			var $t = this, nm, tmp,cc;
			if (!$t.grid || $t.p.cellEdit !== true) {return;}
			iCol = parseInt(iCol,10);
			// select the row that can be used for other methods
			$t.p.selrow = $t.rows[iRow].id;
			if (!$t.p.knv) {$($t).GridNav();}
			// check to see if we have already edited cell
			if ($t.p.savedRow.length>0) {
				// prevent second click on that field and enable selects
				if (ed===true ) {
					if(iRow == $t.p.iRow && iCol == $t.p.iCol){
						return;
					}
				}
				// if so check to see if the content is changed
				var vl = $("td:eq("+$t.p.savedRow[0].ic+")>#"+$t.p.savedRow[0].id+"_"+$t.p.savedRow[0].name.replace('.',"\\."),$t.rows[$t.p.savedRow[0].id]).val();
				if ($t.p.savedRow[0].v !=  vl) {
					// save it
					$($t).saveCell($t.p.savedRow[0].id,$t.p.savedRow[0].ic)
				} else {
					// restore it
					$($t).restoreCell($t.p.savedRow[0].id,$t.p.savedRow[0].ic);
				}
			} else {
				window.setTimeout(function () { $("#"+$t.p.knv).attr("tabindex","-1").focus();},0);
			}
			nm = $t.p.colModel[iCol].name;
			if (nm=='subgrid' || nm=='cb' || nm=='rn') {return;}
			if ($t.p.colModel[iCol].editable===true && ed===true) {
				cc = $("td:eq("+iCol+")",$t.rows[iRow]);
				if(parseInt($t.p.iCol)>=0  && parseInt($t.p.iRow)>=0) {
					$("td:eq("+$t.p.iCol+")",$t.rows[$t.p.iRow]).removeClass("edit-cell ui-state-highlight");
					$($t.rows[$t.p.iRow]).removeClass("selected-row ui-state-hover");
				}
				$(cc).addClass("edit-cell ui-state-highlight");
				$($t.rows[iRow]).addClass("selected-row ui-state-hover");
				try {
					tmp =  $.unformat(cc,{colModel:$t.p.colModel[iCol]},iCol);
				} catch (_) {
					tmp = $(cc).html();
				}
				var opt = $.extend({}, $t.p.colModel[iCol].editoptions || {} ,{id:iRow+"_"+nm,name:nm});
				if (!$t.p.colModel[iCol].edittype) {$t.p.colModel[iCol].edittype = "text";}
				$t.p.savedRow.push({id:iRow,ic:iCol,name:nm,v:tmp});
				if($.isFunction($t.p.formatCell)) {
					var tmp2 = $t.p.formatCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
					if(tmp2) {tmp = tmp2;}
				}
				var elc = createEl($t.p.colModel[iCol].edittype,opt,tmp,cc);
				if ($.isFunction($t.p.beforeEditCell)) {
					$t.p.beforeEditCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
				}
				$(cc).html("").append(elc).attr("tabindex","0");
				window.setTimeout(function () { $(elc).focus();},0);
				$("input, select, textarea",cc).bind("keydown",function(e) { 
					if (e.keyCode === 27) {$($t).restoreCell(iRow,iCol);} //ESC
					if (e.keyCode === 13) {$($t).saveCell(iRow,iCol);}//Enter
					if (e.keyCode == 9)  {
						if (e.shiftKey) {$($t).prevCell(iRow,iCol);} //Shift TAb
						else {$($t).nextCell(iRow,iCol);} //Tab
					}
					e.stopPropagation();
				});
				if ($.isFunction($t.p.afterEditCell)) {
					$t.p.afterEditCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
				}
			} else {
				if (parseInt($t.p.iCol)>=0  && parseInt($t.p.iRow)>=0) {
					$("td:eq("+$t.p.iCol+")",$t.rows[$t.p.iRow]).removeClass("edit-cell ui-state-highlight");
					$($t.rows[$t.p.iRow]).removeClass("selected-row ui-state-hover");
				}
				$("td:eq("+iCol+")",$t.rows[iRow]).addClass("edit-cell ui-state-highlight");
				$($t.rows[iRow]).addClass("selected-row ui-state-hover"); 
				if ($.isFunction($t.p.onSelectCell)) {
					tmp = $("td:eq("+iCol+")",$t.rows[iRow]).html().replace(/\&nbsp\;/ig,'');
					$t.p.onSelectCell($t.rows[iRow].id,nm,tmp,iRow,iCol);
				}
			}
			$t.p.iCol = iCol; $t.p.iRow = iRow;
		});
	},
	saveCell : function (iRow, iCol){
		return this.each(function(){
			var $t= this, fr;
			if (!$t.grid || $t.p.cellEdit !== true) {return;}
			if ( $t.p.savedRow.length >= 1) {fr = 0;} else {fr=null;} 
			if(fr != null) {
				var cc = $("td:eq("+iCol+")",$t.rows[iRow]),v,v2,
				nm = $t.p.colModel[iCol].name.replace('.',"\\.");
				switch ($t.p.colModel[iCol].edittype) {
					case "select":
						if(!$t.p.colModel[iCol].editoptions.multiple) {
							v = $("#"+iRow+"_"+nm.replace('.',"\\.")+">option:selected",$t.rows[iRow]).val();
							v2 = $("#"+iRow+"_"+nm.replace('.',"\\.")+">option:selected",$t.rows[iRow]).text();
						} else {
							var sel = $("#"+iRow+"_"+nm.replace('.',"\\."),$t.rows[iRow]), selectedText = [];
							v = $(sel).val();
							if(v) v.join(","); else v="";
							$("option:selected",sel).each(
								function(i,selected){
									selectedText[i] = $(selected).text();
								}
							);
							v2 = selectedText.join(",");
						}
						break;
					case "checkbox":
						var cbv  = ["Yes","No"];
						if($t.p.colModel[iCol].editoptions){
							cbv = $t.p.colModel[iCol].editoptions.value.split(":");
						}
						v = $("#"+iRow+"_"+nm.replace('.',"\\."),$t.rows[iRow]).attr("checked") ? cbv[0] : cbv[1];
						v2=v;
						break;
					case "password":
					case "text":
					case "textarea":
					case "button" :
						v = !$t.p.autoencode ? $("#"+iRow+"_"+nm.replace('.',"\\."),$t.rows[iRow]).val() : $.jgrid.htmlEncode($("#"+iRow+"_"+nm.replace('.',"\\."),$t.rows[iRow]).val());
						v2=v;
						break;
				}
				// The common approach is if nothing changed do not do anything
				if (v2 != $t.p.savedRow[fr].v){
					if ($.isFunction($t.p.beforeSaveCell)) {
						var vv = $t.p.beforeSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
						if (vv) {v = vv;}
					}
					var cv = checkValues(v,iCol,$t);
					if(cv[0] === true) {
						var addpost = {};
						if ($.isFunction($t.p.beforeSubmitCell)) {
							addpost = $t.p.beforeSubmitCell($t.rows[iRow].id,nm, v, iRow,iCol);
							if (!addpost) {addpost={};}
						}
						if ($t.p.cellsubmit == 'remote') {
							if ($t.p.cellurl) {
								var postdata = {};
								postdata[nm] = v;
								postdata["id"] = $t.rows[iRow].id;
								postdata = $.extend(addpost,postdata);
								$.ajax({
									url: $t.p.cellurl,
									data :postdata,
									type: "POST",
									complete: function (result, stat) {
										if (stat == 'success') {
											if ($.isFunction($t.p.afterSubmitCell)) {
												var ret = $t.p.afterSubmitCell(result,postdata.id,nm,v,iRow,iCol);
												if(ret[0] === true) {
													$(cc).empty();
													$($t).setCell($t.rows[iRow].id, iCol, v2);
													$(cc).addClass("dirty-cell");
													$($t.rows[iRow]).addClass("edited");
													if ($.isFunction($t.p.afterSaveCell)) {
														$t.p.afterSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
													}
													$t.p.savedRow.splice(0,1);
												} else {
													info_dialog($.jgrid.errors.errcap,ret[1],$.jgrid.edit.bClose);
													$($t).restoreCell(iRow,iCol);
												}
											} else {
												$(cc).empty();
												$($t).setCell($t.rows[iRow].id, iCol, v2);
												$(cc).addClass("dirty-cell");
												$($t.rows[iRow]).addClass("edited");
												if ($.isFunction($t.p.afterSaveCell)) {
													$t.p.afterSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
												}
												$t.p.savedRow.splice(0,1);
											}
										}
									},
									error:function(res,stat){
										if ($.isFunction($t.p.errorCell)) {
											$t.p.errorCell(res,stat);
											$($t).restoreCell(iRow,iCol);
										} else {
											info_dialog($.jgrid.errors.errcap,res.status+" : "+res.statusText+"<br/>"+stat,$.jgrid.edit.bClose);
											$($t).restoreCell(iRow,iCol);
										}
									}
								});
							} else {
								try {
									info_dialog($.jgrid.errors.errcap,$.jgrid.errors.nourl,$.jgrid.edit.bClose);
									$($t).restoreCell(iRow,iCol);
								} catch (e) {}
							}
						}
						if ($t.p.cellsubmit == 'clientArray') {
							$(cc).empty();
							$($t).setCell($t.rows[iRow].id,iCol, v2);
							$(cc).addClass("dirty-cell");
							$($t.rows[iRow]).addClass("edited");
							if ($.isFunction($t.p.afterSaveCell)) {
								$t.p.afterSaveCell($t.rows[iRow].id,nm, v, iRow,iCol);
							}
							$t.p.savedRow.splice(0,1);
						}
					} else {
						try {
							window.setTimeout(function(){info_dialog($.jgrid.errors.errcap,v+" "+cv[1],$.jgrid.edit.bClose)},100);
							$($t).restoreCell(iRow,iCol);
						} catch (e) {}
					}
				} else {
					$($t).restoreCell(iRow,iCol);
				}
			}
			if ($.browser.opera) {
				$("#"+$t.p.knv).attr("tabindex","-1").focus();
			} else {
				window.setTimeout(function () { $("#"+$t.p.knv).attr("tabindex","-1").focus();},0);
			}
		});
	},
	restoreCell : function(iRow, iCol) {
		return this.each(function(){
			var $t= this, fr;
			if (!$t.grid || $t.p.cellEdit !== true ) {return;}
			if ( $t.p.savedRow.length >= 1) {fr = 0;} else {fr=null;}
			if(fr != null) {
				var cc = $("td:eq("+iCol+")",$t.rows[iRow]);
				if($.isFunction($.fn['datepicker'])) {
				try {
					$.datepicker('hide');
				} catch (e) {
					try {
						$.datepicker.hideDatepicker();
					} catch (e) {}
				}
				}
				$(cc).empty().attr("tabindex","-1");
				$($t).setCell($t.rows[iRow].id, iCol, $t.p.savedRow[fr].v);
				$t.p.savedRow.splice(0,1);
				
			}
			window.setTimeout(function () { $("#"+$t.p.knv).attr("tabindex","-1").focus();},0);
		});
	},
	nextCell : function (iRow,iCol) {
		return this.each(function (){
			var $t = this, nCol=false;
			if (!$t.grid || $t.p.cellEdit !== true) {return;}
			// try to find next editable cell
			for (var i=iCol+1; i<$t.p.colModel.length; i++) {
				if ( $t.p.colModel[i].editable ===true) {
					nCol = i; break;
				}
			}
			if(nCol !== false) {
				$($t).editCell(iRow,nCol,true);
			} else {
				if ($t.p.savedRow.length >0) {
					$($t).saveCell(iRow,iCol);
				}
			}
		});
	},
	prevCell : function (iRow,iCol) {
		return this.each(function (){
			var $t = this, nCol=false;
			if (!$t.grid || $t.p.cellEdit !== true) {return;}
			// try to find next editable cell
			for (var i=iCol-1; i>=0; i--) {
				if ( $t.p.colModel[i].editable ===true) {
					nCol = i; break;
				}
			}
			if(nCol !== false) {
				$($t).editCell(iRow,nCol,true);
			} else {
				if ($t.p.savedRow.length >0) {
					$($t).saveCell(iRow,iCol);
				}
			}
		});
	},
	GridNav : function() {
		return this.each(function () {
			var  $t = this;
			if (!$t.grid || $t.p.cellEdit !== true ) {return;}
			// trick to process keydown on non input elements
			$t.p.knv = $("table:first",$t.grid.bDiv).attr("id") + "_kn";
			var selection = $("<span style='width:0px;height:0px;background-color:black;' tabindex='0'><span tabindex='-1' style='width:0px;height:0px;background-color:grey' id='"+$t.p.knv+"'></span></span>"),
			i;
			$(selection).insertBefore($t.grid.cDiv);
			$("#"+$t.p.knv).focus();
			$("#"+$t.p.knv).keydown(function (e){
				switch (e.keyCode) {
					case 38:
						if ($t.p.iRow-1 >=0 ) {
							scrollGrid($t.p.iRow-1,$t.p.iCol,'vu');
							$($t).editCell($t.p.iRow-1,$t.p.iCol,false);
						}
					break;
					case 40 :
						if ($t.p.iRow+1 <=  $t.rows.length-1) {
							scrollGrid($t.p.iRow+1,$t.p.iCol,'vd');
							$($t).editCell($t.p.iRow+1,$t.p.iCol,false);
						}
					break;
					case 37 :
						if ($t.p.iCol -1 >=  0) {
							i = findNextVisible($t.p.iCol-1,'lft');
							scrollGrid($t.p.iRow, i,'h');
							$($t).editCell($t.p.iRow, i,false);
						}
					break;
					case 39 :
						if ($t.p.iCol +1 <=  $t.p.colModel.length-1) {
							i = findNextVisible($t.p.iCol+1,'rgt');
							scrollGrid($t.p.iRow,i,'h');
							$($t).editCell($t.p.iRow,i,false);
						}
					break;
					case 13:
						if (parseInt($t.p.iCol,10)>=0 && parseInt($t.p.iRow,10)>=0) {
							$($t).editCell($t.p.iRow,$t.p.iCol,true);
						}
					break;
				}
				return false;
			});
			function scrollGrid(iR, iC, tp){
				if (tp.substr(0,1)=='v') {
					var ch = $($t.grid.bDiv)[0].clientHeight,
					st = $($t.grid.bDiv)[0].scrollTop,
					nROT = $t.rows[iR].offsetTop+$t.rows[iR].clientHeight,
					pROT = $t.rows[iR].offsetTop;
					if(tp == 'vd') {
						if(nROT >= ch) {
							$($t.grid.bDiv)[0].scrollTop = $($t.grid.bDiv)[0].scrollTop + $t.rows[iR].clientHeight;
						}
					}
					if(tp == 'vu'){					
						if (pROT < st) {
							$($t.grid.bDiv)[0].scrollTop = $($t.grid.bDiv)[0].scrollTop - $t.rows[iR].clientHeight;
						}
					}
				}
				if(tp=='h') {
					var cw = $($t.grid.bDiv)[0].clientWidth,
					sl = $($t.grid.bDiv)[0].scrollLeft,
					nCOL = $t.rows[iR].cells[iC].offsetLeft+$t.rows[iR].cells[iC].clientWidth,
					pCOL = $t.rows[iR].cells[iC].offsetLeft;
					if(nCOL >= cw+parseInt(sl)) {
						$($t.grid.bDiv)[0].scrollLeft = $($t.grid.bDiv)[0].scrollLeft + $t.rows[iR].cells[iC].clientWidth;
					} else if (pCOL < sl) {
						$($t.grid.bDiv)[0].scrollLeft = $($t.grid.bDiv)[0].scrollLeft - $t.rows[iR].cells[iC].clientWidth;
					}
				}
			};
			function findNextVisible(iC,act){
				var ind, i;
				if(act == 'lft') {
					ind = iC+1;
					for (i=iC;i>=0;i--){
						if ($t.p.colModel[i].hidden !== true) {
							ind = i;
							break;
						}
					}
				}
				if(act == 'rgt') {
					ind = iC-1;
					for (i=iC; i<$t.p.colModel.length;i++){
						if ($t.p.colModel[i].hidden !== true) {
							ind = i;
							break;
						}						
					}
				}
				return ind;
			};
		});
	},
	getChangedCells : function (mthd) {
		var ret=[];
		if (!mthd) {mthd='all';}
		this.each(function(){
			var $t= this,nm;
			if (!$t.grid || $t.p.cellEdit !== true ) {return;}
			$($t.rows).each(function(j){
				var res = {};
				if ($(this).hasClass("edited")) {
					$('td',this).each( function(i) {
						nm = $t.p.colModel[i].name;
						if ( nm !== 'cb' && nm !== 'subgrid') {
							if (mthd=='dirty') {
								if ($(this).hasClass('dirty-cell')) {
									res[nm] = $.jgrid.htmlDecode($(this).html());
								}
							} else {
								res[nm] = $.jgrid.htmlDecode($(this).html());
							}
						}
					});
					res["id"] = this.id;
					ret.push(res);
				}
			});
		});
		return ret;
	}
/// end  cell editing
});
})(jQuery);
