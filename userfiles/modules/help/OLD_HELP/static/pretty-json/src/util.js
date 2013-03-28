//namespaces def.
var PrettyJSON = {
    view:{},
    tpl:{}
};

/**
* @class PrettyJSON.util
* helpers def. 
*
* @author #rbarriga
* @version 0.1
*
**/
PrettyJSON.util = {
    isObject: function(v){
        return Object.prototype.toString.call(v) === '[object Object]';
    },
    pad: function(str, length){
        str = String(str);
        while(str.length < length) str = '0' + str;
            return str;
    },
    dateFormat: function(date, f){
        f = f.replace('YYYY', date.getFullYear());
        f = f.replace('YY', String(date.getFullYear()).slice(-2));
        f = f.replace('MM', PrettyJSON.util.pad(date.getMonth() + 1, 2));
        f = f.replace('DD', PrettyJSON.util.pad(date.getDate(), 2));
        f = f.replace('HH24', PrettyJSON.util.pad(date.getHours(), 2));
        f = f.replace('HH', PrettyJSON.util.pad((date.getHours() % 12), 2));
        f = f.replace('MI', PrettyJSON.util.pad(date.getMinutes(), 2));
        f = f.replace('SS', PrettyJSON.util.pad(date.getSeconds(), 2));
        return f;
    },
    getDateStr:function(year, month, day){

        var d = new Date();
        if(year)
            d.setFullYear(d.getFullYear() + year);
        if(month)
            d.setMonth(d.getMonth() + month);
        if(day)
            d.setDate(d.getDate() + day);

        return PrettyJSON.util.dateFormat(d, 'YYYY-MM-DD');
    }
}