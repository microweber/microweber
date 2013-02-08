mw.files = {
    settings:{
        filetypes:"png,gif,jpg,jpeg,tiff,bmp",
        url:mw.settings.upload_url,
        type:'explorer', // ... or filedrag
        multiple:true
    },
    filetypes:function(a, normalize){
        var def = !!normalize ? a : mw.files.settings.filetypes;
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
        case 'documents':
          return 'doc,docx,log,msg,odt,pages,rtf,tex,txt,wpd,wps,pps,ppt,pptx,xml,htm,html,xlr,xls,xlsx';
          break;
        case 'archives':
          return 'zip,zipx,gzip,rar,gz,7z,cbr,tar.gz';
          break;
        case 'all':
          return '*';
          break;
        case '*':
          return '*';
          break;
        default:
          return def;
        }
    },
    normalize_filetypes:function(a){

      var str = '';
      var a = a.replace(/\s/g, '');
      var arr = a.split(','), i=0, l=arr.length;
      for( ; i<l; i++){
        str+= mw.files.filetypes(arr[i], true) + ',';
      }
      var str = str.substring(0, str.length - 1);
      return str;
    },
    uploader:function(obj){
        var obj = $.extend({}, mw.files.settings, obj);

        var frame = mwd.createElement('iframe');
        frame.className = 'mw-uploader mw-uploader-'+obj.type;
        frame.scrolling = 'no';
        frame.style.backgroundColor = "transparent";
        frame.setAttribute('frameborder', 0);
        frame.setAttribute('allowtransparency', 'true');
        var params = "?type="+obj.type+"&filters="+mw.files.normalize_filetypes(obj.filetypes)+'&multiple='+obj.multiple;
        frame.src = mw.external_tool('plupload'+params);
        frame.name = obj.name || 'mw-uploader-frame-'+mw.random();



        frame.style.background = "transparent";
        frame.setAttribute('frameborder', 0);
        frame.setAttribute('frameBorder', 0);
        frame.setAttribute('allowtransparency', 'true');
        frame.setAttribute('allowTransparency', 'true');


        return frame;
    },
    unzip:function(url, callback){
       var loader = new ZipLoader(url);
       var data = loader.getEntries(url);
       data.forEach(function(entry){
          if(!entry.isDirectory()){
            var obj = {
                src:loader.loadImage(url+'://'+entry.name()),
                name:entry.name()
            }
            callback.call(obj);
          }
        });

    }
}

