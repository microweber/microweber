<?php

$rand_id = md5(serialize($params)); ?>

<div id="mw_email_source_code_editor<?php print $rand_id ?>">
  <fieldset class="inputs">
    <legend><span>Source code editor</span></legend>
    <?php $langs = array( "html","php", "javascript",  "css", "python",  "sql",     "java",  "perl","xml", "ruby", "c", "cpp", "csharp"); ?>
    <?php $l_sel =  get_option('source_code_language', $params['id']); ?>
    <label  class="label">Source Code Language</label>
    <select name="source_code_language" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider selectable"   type="text" data-refresh="highlight_code"  id="link_existing_bookmark">
      <option value="<?php print $l_sel ?>"><?php print $l_sel ?></option>
      <?php foreach($langs as $lan): ?>
      <option value="<?php print $lan ?>" <?php if($lan == $l_sel): ?> selected="selected" <?php endif; ?>><?php print $lan ?></option>
      <?php endforeach; ?>
    </select>


      <hr />
 <?php $theme_sel =  get_option('source_theme', $params['id']); ?>
<label  class="label">Theme</label>
<select  name="source_theme" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider selectable"  data-refresh="highlight_code">
<?php if($theme_sel != false): ?>
 <option value="<?php print $theme_sel ?>" selected="selected"><?php print $theme_sel ?></option>
   <?php endif; ?>
       <option value="emacs">emacs</option>

    <option value="acid">acid</option>
    <option value="berries-dark">berries-dark</option>
    <option value="berries-light">berries-light</option>
    <option value="bipolar">bipolar</option>
    <option value="blacknblue">blacknblue</option>
    <option value="bright">bright</option>
    <option value="contrast">contrast</option>
    <option value="darkblue">darkblue</option>
    <option value="darkness">darkness</option>
    <option value="desert">desert</option>
    <option value="dull">dull</option>
    <option value="easter">easter</option>
    <option value="golden">golden</option>
    <option value="greenlcd">greenlcd</option>
    <option value="ide-anjuta">ide-anjuta</option>
    <option value="ide-codewarrior">ide-codewarrior</option>
    <option value="ide-devcpp">ide-devcpp</option>
    <option value="ide-eclipse">ide-eclipse</option>
    <option value="ide-kdev">ide-kdev</option>
    <option value="ide-msvcpp">ide-msvcpp</option>
    <option value="kwrite">kwrite</option>
    <option value="matlab">matlab</option>
    <option value="navy">navy</option>
    <option value="nedit">nedit</option>
    <option value="neon">neon</option>
    <option value="night">night</option>
    <option value="pablo">pablo</option>
    <option value="peachpuff">peachpuff</option>
    <option value="print">print</option>
    <option value="rand01">rand01</option>
    <option value="the">the</option>
    <option value="typical">typical</option>
    <option value="vampire">vampire</option>
    <option value="vim">vim</option>
    <option value="vim-dark">vim-dark</option>
    <option value="whatis">whatis</option>
    <option value="whitengrey">whitengrey</option>
    <option value="zellner">zellner</option>
</select>

 <?php $source_show_lines =  get_option('source_show_lines', $params['id']); ?>
<label  class="label">Lines</label>
<select  name="source_show_lines" class="mw_option_field mw_tag_editor_input mw_tag_editor_input_wider selectable"  data-refresh="highlight_code">
<?php if($source_show_lines != false): ?>
 <option value="<?php print $source_show_lines ?>" selected="selected"><?php print $source_show_lines ?></option>
   <?php endif; ?>
    <option value="n">no</option>
    <option value="y">yes</option>


    <hr />
    <code>
    <textarea name="source_code" cols=""  class="mw_option_field" style="height:200px; width:300px;" data-refresh="highlight_code"     rows="2"><?php print get_option('source_code', $params['id']) ?></textarea></code>
  </fieldset>
</div>
