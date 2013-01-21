mw.files = {
    settings:{
        filetypes:"png,gif,jpg,jpeg,tiff,bmp",
        url:mw.settings.upload_url,
        type:'explorer' // ... or filedrag
    },
    filetypes:function(a){
        switch(a){
        case 'images':
          return mw.files.settings.filetypes;
          break;
        case 'videos':
          return 'avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,wma,mov';
          break;
        case 'files':
          return 'doc,docx,pdf,html,js,css,htm,rtf,txt,zip,gzip,rar,cad,xml,psd,xlsx,csv';
          break;
        case 'all':
          return '*';
          break;
        case '*':
          return '*';
          break;
        default:
          return mw.files.settings.filetypes;
        }
    },
    uploader:function(obj){
        var obj = $.extend({}, mw.files.settings, obj);
        var frame = mwd.createElement('iframe');
        frame.className = 'mw-uploader mw-uploader-'+obj.type;
        frame.scrolling = 'no';
        frame.setAttribute('frameborder', 0);
        var params = "?type="+obj.type+"&filters="+mw.files.filetypes(obj.filetypes);
        frame.src = mw.external_tool('plupload'+params);
        frame.name = obj.name || 'mw-uploader-frame-'+mw.random();
        return frame;
    }
}

