<style>
    .demobox {
        position: relative;
        padding: 10px 0px;
    }
    .mw-ui-field {
        width: 100%;
    }
    .helptext{
        color: #666;
    }
</style>

<script>

</script>

<form method="post">

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Name');?></label>
        <input type="text" class="mw-ui-field">
        <div class="helptext">The name is how it appears on your site.</div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Slug');?></label>
        <input type="text" class="mw-ui-field">
        <div class="helptext">The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</div>
    </div>

    <div class="demobox">
        <label class="mw-ui-label"><?php _e('Description');?></label>
        <textarea class="mw-ui-field"></textarea>
        <div class="helptext">The description is not prominent by default; however, some themes may show it.</div>
    </div>

    <button class="mw-ui-btn mw-ui-btn-info" type="submit"><i class="mw-icon-web-checkmark"></i> &nbsp; Save Tag</button>

</form>