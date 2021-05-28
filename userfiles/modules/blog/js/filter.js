class ContentFilter {

    setModuleId(moduleId) {
        this.moduleId = moduleId;
    };

    submitQueryFilter(queryParams)
    {
        var redirectFilterUrl = getUrlAsArray();

        var i;
        for (i = 0; i < queryParams.length; i++) {
            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, queryParams[i].key, queryParams[i].value);
        }

        mw.spinner({
            element: $('#'+this.moduleId+ ''),
            size:"500px",
            decorate: true
        }).show();

        $('#'+this.moduleId+ '').attr('ajax_filter', encodeDataToURL(redirectFilterUrl));
        mw.reload_module('#'+this.moduleId+ '');
        window.history.pushState('', false, '?' + encodeDataToURL(redirectFilterUrl));

        //window.location = "{!! $searchUri !!}" + keywordField.value;
    };

    init() {

        var filterInstance = this;

        // Limit
        $('body').on('change' , '.js-filter-change-limit' , function() {
            var queryParams = [];

            var limit = $(".js-filter-change-limit").val();
            queryParams.push({
                key:'limit',
                value:limit
            });

            filterInstance.submitQueryFilter( queryParams);
        });

        // Sort
        $('body').on('change' , '.js-filter-change-sort' , function() {
            var queryParams = [];

            var sort = $(".js-filter-change-sort").children('option:selected').attr('data-sort');
            queryParams.push({
                key:'sort',
                value:sort
            });

            var order = $(".js-filter-change-sort").children('option:selected').attr('data-order');
            queryParams.push({
                key:'order',
                value:order
            });

            filterInstance.submitQueryFilter( queryParams);
        });

        // Pagination
        $('body').on('click', '.page-link', function(e) {
            e.preventDefault();
            var targetPageNum = $(this).attr('href').split('page=')[1];
            var queryParams = [];
            queryParams.push({
                key:'page',
                value:targetPageNum
            });
            filterInstance.submitQueryFilter(queryParams);
        });

    };
}


function numericMonth(dt) {
    return (dt.getMonth() < 9 ? '0' : '') + (dt.getMonth() + 1);
}

function findOrReplaceInObject(object, key, value) {
    var findKey = false;
    for (var i = 0; i < object.length; i++) {
        if (object[i].key == key) {
            object[i].value = value;
            findKey = true;
            break;
        }
    }
    if (findKey === false) {
        object.push({key: key, value: value});
    }
    return object;
}

function decodeUrlParamsToObject(url)
{
    if (url.indexOf('?') === -1) {
        return [];
    }
    var request = [];
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
        if (!pairs[i])
            continue;
        var pair = pairs[i].split('=');
        request.push({key: decodeURIComponent(pair[0]), value: decodeURIComponent(pair[1])});
    }
    return request;
}

function getUrlAsArray() {
    let url = window.location.href;
    return decodeUrlParamsToObject(url);
}

encodeDataToURL = (data) => {
    return data.map(value => `${value.key}=${encodeURIComponent(value.value)}`).join('&');
};

