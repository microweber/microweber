<script>
export default {

    data() {

    },
    mounted() {
        var liveEditSearchContentField = new mw.autoComplete({
            element: "#mw-live-edit-search-content",
            placeholder: "Search content",
            ajaxConfig: {
                method: 'get',
                url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&keyword=${val}'
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

        $(liveEditSearchContentField).on("change", function (e, val) {
            var urlTogo = false;
            if (typeof (val[0]) !== 'undefined') {
                urlTogo = val[0].url;
                mw.app.canvas.getFrame().src = urlTogo  ;
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
