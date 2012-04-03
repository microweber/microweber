/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

// generates a renderer function to be used for textual date groups
Ext.util.Format.createTextDateRenderer = function(){
    // create the cache of ranges to be reused
    var today = new Date().clearTime(true);
    var year = today.getFullYear();
    var todayTime = today.getTime();
    var yesterday = today.add('d', -1).getTime();
    var tomorrow = today.add('d', 1).getTime();
    var weekDays = today.add('d', 6).getTime();
    var lastWeekDays = today.add('d', -6).getTime();

    var weekAgo1 = today.add('d', -13).getTime();
    var weekAgo2 = today.add('d', -20).getTime();
    var weekAgo3 = today.add('d', -27).getTime();

    var f = function(date){
        if(!date) {
            return '(No Date)';
        }
        var notime = date.clearTime(true).getTime();

        if (notime == todayTime) {
            return 'Today';
        }
        if(notime > todayTime){
            if (notime == tomorrow) {
                return 'Tomorrow';
            }
            if (notime <= weekDays) {
                return date.format('l');
            }
        }else {
        	if(notime == yesterday) {
            	return 'Yesterday';
            }
            if(notime >= lastWeekDays) {
                return 'Last ' + date.format('l');
            }
        }            
        return date.getFullYear() == year ? date.format('D m/d') : date.format('D m/d/Y');
   };
   
   f.date = today;
   return f;
};