mw.files = {
    settings:{
        filetypes:"png|gif|jpg|jpeg|tiff|bmp",
        url:mw.settings.upload_url,
        type:'explorer' // ... or filedrag
    },
    uploader:function(obj){
        var obj = $.extend({}, mw.files.settings, obj);
        var frame = mwd.createElement('iframe');
        frame.className = 'mw-uploader mw-uploader-'+obj.type;
        frame.scrolling = 'no';
        frame.setAttribute('frameborder', 0);
        var params = "?type="+obj.type+"";
        frame.src = mw.external_tool('plupload'+params);
        frame.name = obj.name || 'mw-uploader-frame-'+mw.random();
        return frame;
    }
}

