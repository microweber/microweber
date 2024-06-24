<div>
@php
    $templateId = request()->get('id') ?? null;
@endphp
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


@php
    $findNewsletterTemplate = \MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate::find($templateId);
    if ($findNewsletterTemplate) {
        $templateJson = $findNewsletterTemplate->json;
    } else {
        $templateJson = json_encode([]);
    }
@endphp

@if($templateJson)
    <script>
        let jsonLoaded = false;
        window.addEventListener('editorLoaded', function (e) {
            if (!jsonLoaded) {
                jsonLoaded = true;
                const event = new CustomEvent("loadJsonTemplate", {
                    detail: {
                        json: '{{base64_encode($templateJson)}}',
                    }
                });
                window.dispatchEvent(event);
            }
        });
    </script>
@endif

<script>

    window.addEventListener('saveHtml', function (e) {
        fetch("{{route('admin.newsletter.templates.edit',$templateId)}}", {
            method: "POST",
            body: JSON.stringify({
                template: e.detail,
            })
        })
            .then((response) => response.json())
            .then((data) => {
                if(data.success) {
                    alert('Template saved');
                } else {
                    alert('Error saving template');
                }
            });

    });
    window.addEventListener('backTo', function (e) {
        window.location.href = '';
    });

    window.addEventListener('selectImage', function (selectImageEvent) {
        mw.filePickerDialog( (url) => {
            const event = new CustomEvent("selectedImage", { detail: {
                    url: url,
                    blockId: selectImageEvent.detail.blockId,
                }
            });
            window.dispatchEvent(event);
        });
    });
    window.addEventListener('selectUrl', function (e) {
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

</div>
