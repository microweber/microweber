

mw.require('uploader.js');


mw.files = {
    settings: {
            filetypes:"png,gif,jpg,jpeg,tiff,bmp,svg,webp",
            url: mw.settings.upload_url,
            type: 'explorer',
            multiple: true
    },
    filetypes:function(a, normalize) {
            var def = !!normalize ? a : mw.files.settings.filetypes;
            switch(a){
            case 'img':
            case 'image':
            case 'images':
                return mw.files.settings.filetypes;
            case 'video':
            case 'videos':
                return 'avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,wma,mov,wmv';
            case 'file':
            case 'files':
                return 'doc,docx,pdf,html,js,css,htm,rtf,txt,zip,gzip,rar,cad,xml,psd,xlsx,csv';
            case 'documents':
            case 'doc':
                return 'doc,docx,log,pdf,msg,odt,pages,rtf,tex,txt,wpd,wps,pps,ppt,pptx,xml,htm,html,xlr,xls,xlsx';
            case 'archives':
            case 'arc':
            case 'arch':
                return 'zip,zipx,gzip,rar,gz,7z,cbr,tar.gz';
             case 'audio':
                return 'mp3,wav,ogg,mp4,flac';
             case 'media':
                return (mw.files.filetypes('video') + ',' + mw.files.filetypes('audio'));
             case 'all':
                return '*';
             case '*':
                return '*';
             default:
                return def;
            }
    },
    normalize_filetypes:function(a){
        var str = '';
        a = a.replace(/\s/g, '');
        var arr = a.split(','), i=0, l=arr.length;
        for( ; i<l; i++){
            str+= mw.files.filetypes(arr[i], true) + ',';
        }
        str = str.substring(0, str.length - 1);
        return str;
    },
    safeFilename:function(url){
            if(!url) return;
            url = url.replace(/["]/g, "%22").replace(/[']/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29");
            return url;
    },
    urlAsBackground:function(url, el){
            url = this.safeFilename(url);
            var bg = 'url("'+ url +'")';
            if(!!el){
                    el.style.backgroundImage = bg;
            }
            return bg;
    },
    uploader: function (o) {
        return mw.upload(o);
    }
}

