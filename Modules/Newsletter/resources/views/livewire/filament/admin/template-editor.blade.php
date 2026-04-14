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
    .newsletter-template-editor-shell {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .newsletter-template-editor-breadcrumbs {
        border-bottom: 1px solid rgba(229, 231, 235, 1);
        background: rgba(255, 255, 255, 0.96);
        padding: 0.75rem 1rem;
    }
    .dark .newsletter-template-editor-breadcrumbs {
        border-bottom-color: rgba(31, 41, 55, 1);
        background: rgba(3, 7, 18, 0.96);
    }
    .newsletter-template-editor-mobile-note {
        border-bottom: 1px solid rgba(229, 231, 235, 1);
        background: rgba(249, 250, 251, 0.98);
        padding: 0.875rem 1rem;
    }
    .dark .newsletter-template-editor-mobile-note {
        border-bottom-color: rgba(31, 41, 55, 1);
        background: rgba(17, 24, 39, 0.98);
    }
    #root {
        flex: 1 1 auto;
        min-height: 0;
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

<div class="newsletter-template-editor-shell">
    <div class="newsletter-template-editor-breadcrumbs" data-testid="newsletter-template-editor-breadcrumbs">
        <x-filament::breadcrumbs :breadcrumbs="$this->getBreadcrumbs()" />
    </div>

    <div class="newsletter-template-editor-mobile-note lg:hidden" data-testid="newsletter-template-editor-mobile-note">
        <p class="text-sm font-medium text-gray-900 dark:text-white">The email designer works best on a larger screen.</p>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">On mobile you can review the current design, but for full drag-and-drop editing please switch to desktop or tablet.</p>
    </div>

    <div id="root"></div>
</div>

</div>
</div>
