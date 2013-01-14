 <span class="mw_editor_btn mw_editor_element" data-command="mw_code_editor" onClick="mw_code_editor();"><span class="ed-ico"></span></span>
 
<script src=<? print $config['url_to_module'] ?>lib/codemirror.js></script>
    <script src=<? print $config['url_to_module'] ?>mode/xml/xml.js></script>
    <script src=<? print $config['url_to_module'] ?>mode/javascript/javascript.js></script>
    <script src=<? print $config['url_to_module'] ?>mode/css/css.js></script>
    <script src=<? print $config['url_to_module'] ?>mode/htmlmixed/htmlmixed.js></script>
    <link rel=stylesheet href=<? print $config['url_to_module'] ?>lib/codemirror.css>
     <style type=text/css>
      .CodeMirror {
        float: left;
        width: 50%;
        border: 1px solid black;
		
		
		
      }
	  
	   #module-<? print $config['module_class'] ?> {
		  display: none;
		  position:fixed;
		  bottom:0px;
 background-color:white;
	   }
    
	
	   #module-<? print $config['module_class'] ?> iframe {
        width: 49%;
        float: left;
        height: 300px;
        border: 1px solid black;
        border-left: 0px;
      }
	  
	  
    </style>
    
    
    
    
    
    <div id="module-<? print $config['module_class'] ?>">
   
  
     <textarea id="module-<? print $config['module_class'] ?>-code" name="module-<? print $config['module_class'] ?>-code">Select element to edit its HTML content</textarea>
      
    <iframe id="module-<? print $config['module_class'] ?>-preview"></iframe>
    
     <div>
     <button onclick="mw_source_code_applyBack();" class="mw-ui-btn">Apply</button>
     
   </div>
    </div>
    <script>
	
	 function mw_code_editor() {
		  $("#module-<? print $config['module_class'] ?>").toggle();
		 
		 
	 }
    
        $(document).ready(function () {
	  mw_source_code_makeEditor();
	   });
	       function mw_source_code_makeEditor() {
			   
			     var delay;
      // Initialize CodeMirror editor with a nice html5 canvas demo.
        mw_source_code_editor = CodeMirror.fromTextArea(document.getElementById('module-<? print $config['module_class'] ?>-code'), {
        mode: 'text/html',
        tabMode: 'indent'
      });
      mw_source_code_editor.on("change", function() {
        clearTimeout(delay);
        delay = setTimeout(mw_source_code_updatePreview, 300);
      });
   
      }
	  
	  function mw_source_code_applyBack() {
 var new_val = mw_source_code_editor.getValue();
 mw.log(new_val);
   mw.log($mw_code_mirror_el);
  $($mw_code_mirror_el).html(new_val)
      
   
      }
	  
      
      function mw_source_code_updatePreview() {
        var previewFrame = document.getElementById('module-<? print $config['module_class'] ?>-preview');
        var preview =  previewFrame.contentDocument ||  previewFrame.contentWindow.document;
        preview.open();
        preview.write(mw_source_code_editor.getValue());
        preview.close();
      }
 	  
	  
	      
      $(window).bind("onElementClick", function(e, el){
   $mw_code_mirror_el = el;
  $mw_code_mirror_src = $(el).html();
   mw_source_code_editor.setValue( $mw_code_mirror_src)

   /* if($(el).hasClass("lipsum")){
       $(el).removeClass("lipsum");
       mw.wysiwyg.select_all(el);
    }*/
  });
  
  
  
  
    </script>