


<? $here = dirname(__FILE__);
		
	$here =	pathToURL($here).'/';
	

	$uid =  uniqid() ;
	 
		?>

        <script>mw.require('tools.js');</script>
        <script>mw.require('url.js');</script>
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



            Params = mw.url.getUrlParams(window.location.href);

           $(document).ready(function(){

            var multi = Params.multiple.contains('true');


            var filters = [ {title:"", extensions : Params.filters} ]

           this_frame = parent.mw.$("iframe[name='"+Name+"']");





            var uploader = new plupload.Uploader({
                runtimes : 'html5,html4',
                browse_button : 'pickfiles_<? print $uid  ?>',
                debug : 1,
                container: 'container',
				chunk_size : '3mb',
                url : '<? print site_url('plupload'); ?>',
                filters:filters,
                multi_selection:multi
            });

            uploader.init();


            uploader.bind('FilesAdded', function(up, files) {
                uploader.start();
            });

            uploader.bind('UploadProgress', function(up, file) {
               this_frame.trigger("progress", file);

            });

            uploader.bind('FileUploaded', function(up, files, info){
              var json =  jQuery.parseJSON(info.response);
              if(typeof json.error == 'undefined'){
                 this_frame.trigger("FileUploaded", json);
              }
              else{
                this_frame.trigger("responseError", json);
              }

            });

            uploader.bind('UploadComplete', function(up, files){
              this_frame.trigger("done", files);
            });

            uploader.bind('FilesAdded', function(up, files){
              this_frame.trigger("FilesAdded", [files, up.runtime]);
            });

            uploader.bind('Error', function(up, err){
             this_frame.trigger("error", err.file);
	        });


                 $(document.body).click(function(){
                       this_frame.trigger("click");
                 });
            });

        </script>
 