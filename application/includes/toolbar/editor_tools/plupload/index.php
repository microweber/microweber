


<? $here = dirname(__FILE__);
		
	$here =	pathToURL($here).'/';
	
	
	$uid =  uniqid() ; 
	 
		?>
        <style type="text/css">
        html,body,#container,#pickfiles_<? print $uid  ?>{
          position: absolute;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          background: transparent;
        }

        </style>
 
        <script type="text/javascript" src="<? print $here ?>js/plupload.js"></script>
        <script type="text/javascript" src="<? print $here ?>js/plupload.html5.js"></script>
        <script type="text/javascript" src="<? print $here ?>js/plupload.html4.js"></script>


 


        <div id="container">
            <div id="pickfiles_<? print $uid  ?>" href="javascript:;">&nbsp;</div>
        </div>


        <script type="text/javascript">

            Name = this.name;

            this_frame = parent.$("iframe[name='"+Name+"']");

            function $id(id) {
                return document.getElementById(id);
            }


            var uploader = new plupload.Uploader({
                runtimes : 'html5,html4',
                browse_button : 'pickfiles_<? print $uid  ?>',
                debug : 1,
                container: 'container',
				chunk_size : '3mb',
                url : '<? print site_url('plupload'); ?>'
            });

            uploader.init();

            uploader.bind('FilesAdded', function(up, files) {
                for (var i in files) {
                    //$id('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
                }
                uploader.start();
            });

            uploader.bind('UploadProgress', function(up, file) {
               this_frame.trigger("progress", file.percent);
            });

            uploader.bind('FileUploaded', function(up, files, info){
              this_frame.trigger("done", jQuery.parseJSON(info.response));
            });



        </script>
 