<h2 style="font-size: 20px;" class="blue_title">Add meta tag information for your site</h2>

 <label class="lbl">Content Meta Title:</label>
    <input style="width: 200px" name="content_meta_title" type="text" value="<?php print $form_values['content_meta_title']; ?>">

    <div style="height: 5px;overflow: hidden;clear: both"><!-- &nbsp; --></div>
     <label class="lbl">Content Meta Description:</label>
        <textarea style="width: 200px" name="content_meta_description"><?php print $form_values['content_meta_description']; ?></textarea>


     <label class="lbl">Content Meta Keywords: </label>
     <textarea style="width: 200px" name="content_meta_keywords"><?php print $form_values['content_meta_keywords']; ?></textarea>

<div style="height: 5px;overflow: hidden;clear: both"><!-- &nbsp; --></div>

<label class="lbl">Content Meta Other Code: </label>
     <textarea name="content_meta_other_code" style="width: 200px"><?php print $form_values['content_meta_other_code']; ?></textarea>
