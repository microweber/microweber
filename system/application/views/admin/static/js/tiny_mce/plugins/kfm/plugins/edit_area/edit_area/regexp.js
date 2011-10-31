
EditArea.prototype.comment_or_quote=function(){var new_class="",close_tag="",sy,arg,i;sy=parent.editAreaLoader.syntax[editArea.current_code_lang];arg=EditArea.prototype.comment_or_quote.arguments[0];for(i in sy["quotes"]){if(arg.indexOf(i)==0){new_class="quotesmarks";close_tag=sy["quotes"][i];}}
if(new_class.length==0)
{for(var i in sy["comments"]){if(arg.indexOf(i)==0){new_class="comments";close_tag=sy["comments"][i];}}}
if(close_tag=="\n"){return"µ__"+new_class+"__µ"+arg.replace(/(\r?\n)?$/m,"µ_END_µ$1");}else{reg=new RegExp(parent.editAreaLoader.get_escaped_regexp(close_tag)+"$","m");if(arg.search(reg)!=-1)
return"µ__"+new_class+"__µ"+arg+"µ_END_µ";else
return"µ__"+new_class+"__µ"+arg;}};EditArea.prototype.get_syntax_trace=function(text){if(this.settings["syntax"].length>0&&parent.editAreaLoader.syntax[this.settings["syntax"]]["syntax_trace_regexp"])
return text.replace(parent.editAreaLoader.syntax[this.settings["syntax"]]["syntax_trace_regexp"],"$3");};EditArea.prototype.colorize_text=function(text){text=" "+text;if(this.settings["syntax"].length>0)
text=this.apply_syntax(text,this.settings["syntax"]);return text.substr(1).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/µ_END_µ/g,"</span>").replace(/µ__([a-zA-Z0-9]+)__µ/g,"<span class='$1'>");};EditArea.prototype.apply_syntax=function(text,lang){var sy;this.current_code_lang=lang;if(!parent.editAreaLoader.syntax[lang])
return text;sy=parent.editAreaLoader.syntax[lang];if(sy["custom_regexp"]['before']){for(var i in sy["custom_regexp"]['before']){var convert="$1µ__"+sy["custom_regexp"]['before'][i]['class']+"__µ$2µ_END_µ$3";text=text.replace(sy["custom_regexp"]['before'][i]['regexp'],convert);}}
if(sy["comment_or_quote_reg_exp"]){text=text.replace(sy["comment_or_quote_reg_exp"],this.comment_or_quote);}
if(sy["keywords_reg_exp"]){for(var i in sy["keywords_reg_exp"]){text=text.replace(sy["keywords_reg_exp"][i],'µ__'+i+'__µ$2µ_END_µ');}}
if(sy["delimiters_reg_exp"]){text=text.replace(sy["delimiters_reg_exp"],'µ__delimiters__µ$1µ_END_µ');}
if(sy["operators_reg_exp"]){text=text.replace(sy["operators_reg_exp"],'µ__operators__µ$1µ_END_µ');}
if(sy["custom_regexp"]['after']){for(var i in sy["custom_regexp"]['after']){var convert="$1µ__"+sy["custom_regexp"]['after'][i]['class']+"__µ$2µ_END_µ$3";text=text.replace(sy["custom_regexp"]['after'][i]['regexp'],convert);}}
return text;};