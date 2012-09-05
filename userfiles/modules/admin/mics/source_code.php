<? $rand_id = md5(serialize($params)); ?>
<div id="mw_email_source_code_editor<? print $rand_id ?>">
<fieldset class="inputs">
        <legend><span>Source code editor</span></legend>
        <ol>
          <li class="select input optional" id="">
          
          <? $langs = array( "xhtml","php", "js",  "rb", "bsh", "c", "cc", "cpp", "cs", "csh", "cyc", "cv", "htm", "html",
    "java",  "m", "mxml", "perl", "pl", "pm", "py",  "sh", "xml", "xsl"); ?>
          
          <?php $l_sel =  option_get('source_code_language', $params['module_id']); ?>  
            <label  class="label">Source Code Language</label>
             
            
            
            
            <select name="source_code_language" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider selectable" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="forms/mail_form"  id="link_existing_bookmark">
             <option value="<? print $l_sel ?>"><? print $l_sel ?></option>
            <? foreach($langs as $lan): ?>
            
            <option value="<? print $lan ?>" <? if($lan == $l_sel): ?> selected="selected" <? endif; ?>><? print $lan ?></option>
            <? endforeach; ?>
            </select>
            
            
            
          </li>
          <li class="">
      
          
          <textarea name="source_code" cols=""  class="mw_option_field mw_tag_editor_textarea textarea" style="height:400px;" refresh_modules="mics/source_code"   option_group="<? print $params['module_id'] ?>" rows="2"><?php print option_get('source_code', $params['module_id']) ?></textarea>
         
         
         
         
         
         
          </li>
        </ol>
      </fieldset>
 
</div>
