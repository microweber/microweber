<script>
export default {

    data() {
        return {
            selectedContentId: 0,
            selectedContentTitle: '',
            liveEditSearchContentField: null,
        }
    },
    mounted() {
        this.liveEditSearchContentField = new mw.autoComplete({
            element: "#mw-live-edit-search-content",
            placeholder: "Search content",
            ajaxConfig: {
                method: 'get',
                url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&is_active=1&is_deleted=0&keyword=${val}'
            },
            map: {
                value: 'id',
                title: 'title',
                image: 'picture'
            },
            selected: [
                {
                    id: 0,
                    title: '',
                    placeholder: 'All'
                }
            ]
        });

        $(this.liveEditSearchContentField).on("change", function (e, val) {
            var urlTogo = false;
            if (typeof (val[0]) !== 'undefined') {
                urlTogo = val[0].url;
                mw.app.canvas.getFrame().src = urlTogo  ;
            }
        })

        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            var liveEditIframe = (mw.app.canvas.getWindow());
            if (liveEditIframe
                && typeof liveEditIframe.mw !== 'undefined'
                && typeof liveEditIframe.mw.liveEditIframeData !== 'undefined'
                && liveEditIframe.mw.liveEditIframeData
                && liveEditIframe.mw.liveEditIframeData.content
                && liveEditIframe.mw.liveEditIframeData.content.id) {
                this.selectedContentId = liveEditIframe.mw.liveEditIframeData.content.id;
                this.selectedContentTitle = liveEditIframe.mw.liveEditIframeData.content.title;
                this.liveEditSearchContentField.select(
                    {
                        id: this.selectedContentId,
                        title: this.selectedContentTitle,
                    }
                ,false);
            }
        })






    },
    methods: {


    }
}
</script>


<template>
    <div id="mw-live-edit-search-content"></div>
</template>
