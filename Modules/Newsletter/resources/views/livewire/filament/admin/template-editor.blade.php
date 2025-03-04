<div>
<div>
@php
    $templateId = request()->get('id') ?? null;
    $campaignId = request()->get('campaignId') ?? null;
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

<script type="module" crossorigin src="{{asset('modules/newsletter/js/email-editor.js')}}"></script>


@php
    $findNewsletterTemplate = \Modules\Newsletter\Models\NewsletterTemplate::find($templateId);
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
                        json: {!! $templateJson !!},
                    }
                });
                window.dispatchEvent(event);
            }
        });
    </script>
@endif

<script>

    function afterSaveHtml(data)
    {
        @if($campaignId)

            window.location.href = '{{admin_url('newsletter/edit-campaign/'.$campaignId.'?step=content')}}';

        @else

        if(data.success) {
            alert('Template saved');
        } else {
            alert('Error saving template');
        }

        @endif
    }

    window.addEventListener('saveHtml', function (e) {
        fetch("{{route('admin.newsletter.templates.edit',$templateId)}}", {
            method: "POST",
            body: JSON.stringify({
                template: e.detail,
            })
        })
            .then((response) => response.json())
            .then((data) => {
                afterSaveHtml(data);
            });

    });
    window.addEventListener('backTo', function (e) {
        @if($campaignId) // give the space between if cause broke javascript

        window.location.href = '{{admin_url('newsletter/edit-campaign/'.$campaignId.'?step=content')}}';

        @else

        window.location.href = '{{admin_url('newsletter/templates')}}';

        @endif
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
</div>
