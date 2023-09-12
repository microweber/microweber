@php
$rand = time().rand(1111,9999);
@endphp

<label class="form-control-live-edit-label-wrapper">


    <input type="text"
           id="js-btn-link-value{{$rand}}"
           class="form-control-live-edit-input js-open-link-editor{{$rand}}-field"
           placeholder="https://example.com" readonly="readonly" />

    <textarea style="display:none" id="js-btn-link-value-json{{$rand}}" {!! $attributes->merge([]) !!}></textarea>

    <span class="form-control-live-edit-bottom-effect"></span>

    <span class="form-control-live-edit-settings-btn js-open-link-editor{{$rand}}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M1.911 7.383a8.491 8.491 0 0 1 1.78-3.08a.5.5 0 0 1 .54-.135l1.918.686a1 1 0 0 0 1.32-.762l.366-2.006a.5.5 0 0 1 .388-.4a8.532 8.532 0 0 1 3.554 0a.5.5 0 0 1 .388.4l.366 2.006a1 1 0 0 0 1.32.762l1.919-.686a.5.5 0 0 1 .54.136a8.491 8.491 0 0 1 1.78 3.079a.5.5 0 0 1-.153.535l-1.555 1.32a1 1 0 0 0 0 1.524l1.555 1.32a.5.5 0 0 1 .152.535a8.491 8.491 0 0 1-1.78 3.08a.5.5 0 0 1-.54.135l-1.918-.686a1 1 0 0 0-1.32.762l-.366 2.007a.5.5 0 0 1-.388.399a8.528 8.528 0 0 1-3.554 0a.5.5 0 0 1-.388-.4l-.366-2.006a1 1 0 0 0-1.32-.762l-1.918.686a.5.5 0 0 1-.54-.136a8.49 8.49 0 0 1-1.78-3.079a.5.5 0 0 1 .152-.535l1.555-1.32a1 1 0 0 0 0-1.524l-1.555-1.32a.5.5 0 0 1-.152-.535Zm1.06-.006l1.294 1.098a2 2 0 0 1 0 3.05l-1.293 1.098c.292.782.713 1.51 1.244 2.152l1.596-.57a2 2 0 0 1 2.64 1.525l.305 1.668a7.556 7.556 0 0 0 2.486 0l.304-1.67a1.998 1.998 0 0 1 2.641-1.524l1.596.571a7.492 7.492 0 0 0 1.245-2.152l-1.294-1.098a1.998 1.998 0 0 1 0-3.05l1.294-1.098a7.491 7.491 0 0 0-1.245-2.152l-1.596.57a2 2 0 0 1-2.64-1.524l-.305-1.669a7.555 7.555 0 0 0-2.486 0l-.304 1.669a2 2 0 0 1-2.64 1.525l-1.597-.571a7.491 7.491 0 0 0-1.244 2.152ZM7.502 10a2.5 2.5 0 1 1 5 0a2.5 2.5 0 0 1-5 0Zm1 0a1.5 1.5 0 1 0 3 0a1.5 1.5 0 0 0-3 0Z"/></svg>
    </span>
</label>

<script>
    var currentLinkPickerValue = {
        url: '',
        text: null,
        target:  '_self',
        id: 1,
        type: 'category'
    }
    $(document).ready(function() {

        function _setCurrentLinkPickerValue(currentValueLink)
        {
            currentLinkPickerValue.url = currentValueLink.url;
            if (currentValueLink.data) {
                if (currentValueLink.data.id) {
                    currentLinkPickerValue.id = currentValueLink.data.id;
                }
                if (currentValueLink.data.title) {
                    currentLinkPickerValue.text = currentValueLink.data.title;
                }
                if (currentValueLink.data.content_type) {
                    currentLinkPickerValue.type = currentValueLink.data.content_type;
                }
                if (currentValueLink.data.type) {
                    currentLinkPickerValue.type = currentValueLink.data.type;
                }
            }
            $('.js-open-link-editor{{$rand}}-field').val(currentLinkPickerValue.url);
        }

        function _handleLinkSelect(e){
            if(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            var linkItemsConfig =  {text: false, target: false}

            const linkEditor = new mw.LinkEditor({
                mode: 'dialog',
                controllers: [
                    { type: 'url', config: linkItemsConfig},
                    { type: 'page', config: linkItemsConfig },
                    { type: 'post', config: linkItemsConfig },
                    { type: 'file', config: linkItemsConfig },
                    { type: 'email',  config: linkItemsConfig},
                    { type: 'layout', config: linkItemsConfig },
                ],
            });

            if(currentLinkPickerValue.type === 'category' || currentLinkPickerValue.type === 'page') {
                linkEditor.selectController('page')
            }
            linkEditor
            .setValue(currentLinkPickerValue)
            .promise()
            .then(function (result){

                let linkValueElementJson = document.getElementById('js-btn-link-value-json{{$rand}}');
                linkValueElementJson.value = JSON.stringify(result);
                linkValueElementJson.dispatchEvent(new Event('input'));

                let linkValueElement = document.getElementById('js-btn-link-value{{$rand}}');
                linkValueElement.value = result.url;
                linkValueElement.dispatchEvent(new Event('input'));

                _setCurrentLinkPickerValue(result);

            });
        }

        let currentValueLink = document.getElementById('js-btn-link-value-json{{$rand}}').value;
        if (currentValueLink) {
            try {
                currentValueLink = JSON.parse(currentValueLink);
                _setCurrentLinkPickerValue(currentValueLink);
            } catch (e) {
              //  console.log(e);
            }
        }

        $('.js-open-link-editor{{$rand}}-field').on('focus', function (e) {
            _handleLinkSelect(e)
        })

        $('.js-open-link-editor{{$rand}}').on('click', function (e) {
            _handleLinkSelect(e)
        });
    });
</script>
