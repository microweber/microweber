<?php only_admin_access(); ?>

<?php
$template_id = $params['data-template_id'];
$template = newsletter_get_template(array("id"=>$template_id));
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title></title>
    
    <link rel="stylesheet" href="<?php print $config['url_to_module'];?>css/grapes.min.css">
    <link rel="stylesheet" href="<?php print $config['url_to_module'];?>css/grapesjs-preset-newsletter.css">
    <script src="<?php print $config['url_to_module'];?>js/grapes.min.js"></script>
    <script src="<?php print $config['url_to_module'];?>js/grapesjs-preset-newsletter.min.js"></script>
    
	<style type="text/css">
	 body,
      html {
        height: 100%;
        margin: 0;
      }
	.gjs-pn-panel {
     height: 42px;
    }
    
	</style>
  </head>

  <body>
 
    <div id="gjs" style="height:0px; overflow:hidden;">
   		 <?php print $template['text']; ?>
	</div>
	
	<style>
	.mw_modal_container {
        padding: 0px !important;
    }
	</style>
      
    <script type="text/javascript">
      var host = 'http://artf.github.io/grapesjs/';
      var images = [
        host + 'img/grapesjs-logo.png',
        host + 'img/tmp-blocks.jpg',
        host + 'img/tmp-tgl-images.jpg',
        host + 'img/tmp-send-test.jpg',
        host + 'img/tmp-devices.jpg',
      ];

      // Set up GrapesJS editor with the Newsletter plugin
      var editor = grapesjs.init({
        height: '100%',
        //noticeOnUnload: 0,
        storageManager:{
          autoload: 0,
        },
        assetManager: {
          assets: images,
          upload: 0,
          uploadText: 'Uploading is not available in this demo',
        },
        container : '#gjs',
        fromElement: true,
        plugins: ['gjs-preset-newsletter'],
        pluginsOpts: {
          'gjs-preset-newsletter': {
            modalLabelImport: 'Paste all your code here below and click import',
            modalLabelExport: 'Copy the code and use it wherever you want',
            codeViewerTheme: 'material',
            //defaultTemplate: templateImport,
            importPlaceholder: '<table class="table"><tr><td class="cell">Hello world!</td></tr></table>',
            cellStyle: {
              'font-size': '12px',
              'font-weight': 300,
              'vertical-align': 'top',
              color: 'rgb(111, 119, 125)',
              margin: 0,
              padding: 0,
            }
          }
        }
      });
    </script>
  </body>
</html>


