<script>
    function openLinkInModal(url) {
        let linkInModal = mw.top().dialogIframe({
            url: url,
            width:960,
            height:'auto',
            closeOnEscape:false,
            autoHeight:true,
            // skin: 'edit-post',
            // footer: true,
            beforeRemove: function(dialog) {
                console.log(dialog.container.querySelector('iframe'));
                console.log(dialog.container.querySelector('iframe').contentWindow);
                console.log(dialog.container.querySelector('iframe').contentWindow.mw.askusertostay);

                if (dialog.container.querySelector('iframe').contentWindow.mw.askusertostay) {
                    mw.top().tools.confirm('<?php _e("You have unsaved changes, are you sure want to exit?"); ?>', function() {
                        linkInModal.forceRemove();
                    });
                    return false;
                } else {
                    return true;
                }
            }
        });
        linkInModal.dialogContainer.style.paddingLeft = '0px';
        linkInModal.dialogContainer.style.paddingRight = '0px';
        linkInModal.dialogFooter.style.display = 'none';
    }
    $(document).ready(function() {
        // This is important when click pagination or change display types
        $(document).on("click","a",function(e) {
            let aEvent = e;
            let aElement = $(this);
            if (aElement.hasClass('js-open-in-modal')) {
                aEvent.preventDefault();
                openLinkInModal(aElement.attr('href'));
            }
        });
    });
</script>
