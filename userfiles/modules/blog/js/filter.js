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

function submitQueryFilter(moduleId, queryParams)
{
    var redirectFilterUrl = getUrlAsArray();

    var i;
    for (i = 0; i < queryParams.length; i++) {
        redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, queryParams[i].key, queryParams[i].value);
    }

    mw.spinner({
        element: $('#'+moduleId+ ''),
        size:"500px",
        decorate: true
    }).show();

    $('#'+moduleId+ '').attr('ajax_filter', encodeDataToURL(redirectFilterUrl));
    mw.reload_module('#'+moduleId+ '');
    window.history.pushState('', false, '?' + encodeDataToURL(redirectFilterUrl));

    //window.location = "{!! $searchUri !!}" + keywordField.value;
}

