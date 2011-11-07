
(function($){$.fn.hoverClass=function(c){return this.each(function(){$(this).hover(function(){$(this).addClass(c);},function(){$(this).removeClass(c);});});};})(jQuery)
var $j=jQuery.noConflict();$j.tablesorter.addParser({id:'kfmobject',is:function(s){return false;},format:function(s){return $j(s).text().toLowerCase();},type:'text'});