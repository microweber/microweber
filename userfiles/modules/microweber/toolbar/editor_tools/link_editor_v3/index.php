
<div id="link-editor"></div>

<script>
    mw.lib.require("bootstrap_select");
    mw.require("admin-new.css");
    mw.require("editor.js");

    var linkEditor = new mw.LinkEditor({
        element: document.querySelector('#link-editor'),
        mode: 'element'
    });
    linkEditor.promise().then(function (data){
        var modal = mw.dialog.get(window.frameElement);

        if(data) {

            modal.result(data);
        }
        modal.remove()
    });

</script>


<style>
    #link-editor .mw-ui-form-controller-root > div:first-of-type{
        max-height: none;
    }
</style>
