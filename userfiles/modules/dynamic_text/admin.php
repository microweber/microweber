<?php  only_admin_access(); ?>
<?php
$dynamic_texts = db_get('dynamic_text_variables');
?>

<div class="mw-module-admin-wrap">
    <?php if (isset($params['backend'])): ?>
        <module type="admin/modules/info"/>
    <?php endif; ?>
    <div class="admin-side-content" style="max-width:100%;">

        <form>
            Variable: (example: <b>my_cool_variable</b>)<br />
            <input type="text" class="mw-ui-field" required="required" style="width: 500px" />
            <br />
            <br />
            Content: <br />
            <textarea class="mw-ui-field" required="required" style="width: 500px"></textarea>
            <br />
            <br />
            <button class="mw-ui-btn mw-ui-btn-notification">Add dynamic text</button>
        </form>

        <br />
        <br />


        <table class="mw-ui-table" width="100%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th>Variable</th>
                <th>Content</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php if(is_array($dynamic_texts)) : ?>
                <?php foreach($dynamic_texts as $dynamic_text) : ?>
                <td><?php echo $dynamic_text['variable'];?></td>
                <td><?php echo $dynamic_text['content'];?></td>
                <td>
                    <a href="javascript:;" onclick="deleteDynamicVariable(<?php echo $dynamic_text['id'];?>);" class="mw-ui-btn mw-ui-btn-medium">Delete</a>
                </td>
                <?php endforeach; ?>
                <?php endif; ?>
            </tr>
            </tbody>
        </table>


    </div>
</div>
