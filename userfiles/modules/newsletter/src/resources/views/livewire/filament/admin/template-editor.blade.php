
<style>
    main {
        padding:0 !important;
    }
    #root {
        height: 100vh;
        width: 100%;
    }
</style>

<script type="module" crossorigin src="{{modules_url()}}newsletter/js/email-editor.js"></script>

<script>
    document.addEventListener('selectImage', function () {
        mw.filePickerDialog( (url) => {
            const event = new CustomEvent("selectedImage", { detail: url });
            window.dispatchEvent(event);
        });
    });
    document.addEventListener('selectUrl', function (e) {
        var linkEditor = new mw.LinkEditor({
            mode: 'dialog',
        });
        linkEditor.promise().then(function (data){
            var modal = linkEditor.dialog;
            if(data) {
                const event = new CustomEvent("selectedUrl", { detail: data.href });
                window.dispatchEvent(event);
            }
            modal.remove();
        });
    });
</script>

<div id="root"></div>

