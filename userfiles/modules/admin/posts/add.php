<? $rand_id = md5(serialize($params)); ?>

<div id="mw_email_source_code_editor<? print $rand_id ?>">
  <fieldset class="inputs">
    <legend><span>Source code editor</span></legend>
    <ol>
      <li class="select input optional" id="">
        <? $langs = array( "xhtml","php", "js",  "rb", "bsh", "c", "cc", "cpp", "cs", "csh", "cyc", "cv", "htm", "html",
    "java",  "m", "mxml", "perl", "pl", "pm", "py",  "sh", "xml", "xsl"); ?>
        <?php $l_sel =  option_get('source_code_language', $params['module_id']); ?>
        <label  class="label">Select category</label>
         <microweber module="admin/content/category_selector" active_category="<? print implode(',',option_get('category', $params['module_id'])); ?>" update_field="#category<? print $rand_id ?>"   />
<input name="category" id="category<? print $rand_id ?>" value=" <?php print  option_get('category', $params['module_id']); ?>" />
      </li>
      <li class="">
        <textarea name="source_code" cols=""  class="mw_option_field mw_tag_editor_textarea textarea" style="height:400px;" refresh_modules="mics/source_code"   option_group="<? print $params['module_id'] ?>" rows="2"><?php print option_get('source_code', $params['module_id']) ?></textarea>
      </li>
    </ol>
  </fieldset>
</div>
