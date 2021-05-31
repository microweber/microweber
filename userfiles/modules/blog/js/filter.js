/*jshint esversion: 6 */

class ContentFilter {

    setModuleId(moduleId) {
        this.moduleId = moduleId;
    }

    submitQueryFilter(queryParams) {
        var redirectFilterUrl = getUrlAsArray();
        var i;
        for (i = 0; i < queryParams.length; i++) {
            if(queryParams[i].remove) {
                redirectFilterUrl = redirectFilterUrl.filter(function (item) {
                    return item.key !== queryParams[i].key;
                });
            } else {
                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, queryParams[i].key, queryParams[i].value);
            }
        }
        this.reloadFilter(redirectFilterUrl);
    }

    reloadFilter(redirectFilterUrl) {

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


    getFilterOptions() {
        const nodes = document.querySelectorAll('.js-filter-option-select'),
        l = nodes.length,
        queryParams = [];
        let i = 0;
        for ( ; i < l; i++) {
            const item = nodes[i];
            const toAdd = {
                key:item.name,
                value:item.value
            };
            if (item.type === 'radio' || item.type === 'checkbox') {
              if (!item.checked) {
                  toAdd.remove = true;
              }
            }
            queryParams.push(toAdd);
        }
        return queryParams;
    }

    init() {

        var filterInstance = this;

        // Active filters
        $('body').on('click' , '.js-filter-active-filters' , function() {

            var keys = $(this).data('key');
            var removeKeys = keys.split(',');

            var redirectFilterUrl = getUrlAsArray();

            for (var i = 0; i < removeKeys.length; i++) {
                var filterKey = removeKeys[i];
                filterKey = filterKey.trim();
                redirectFilterUrl = removeItemByKeyInObject(redirectFilterUrl, filterKey);
            }

            filterInstance.reloadFilter(redirectFilterUrl);

        });

        // Limit
        $('body').on('change' , '.js-filter-change-limit' , function() {

            $(this).attr('disabled','disabled');

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

            $(this).attr('disabled','disabled');

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

            filterInstance.submitQueryFilter(queryParams);
        });

        // Custom fields
        $('body').on('change', '.js-filter-option-select', function(e) {
           filterInstance.submitQueryFilter(filterInstance.getFilterOptions());
        });

        // Search
        $(document).keypress(function(e) {
            if(e.which == 13) {
               $('.js-filter-search-field').trigger('change');
            }
        });

        $('body').on('submit', '.js-filter-search-submit', function(e) {

            $(this).attr('disabled','disabled');

            $('.js-filter-search-field').trigger('change');
        });

        $('body').on('change', '.js-filter-search-field', function(e) {

            $(this).attr('disabled','disabled');

            var queryParams = [];
            queryParams.push({
                key:'search',
                value: $('.js-filter-search-field').val()
            });
            filterInstance.submitQueryFilter(queryParams);
        });

        // Categories
        $('body').on('click', '.js-filter-category-link', function(e) {
            e.preventDefault();
            var targetPageNum = $(this).attr('href').split('category=')[1];
            var queryParams = [];
            queryParams.push({
                key:'category',
                value:targetPageNum
            });
            filterInstance.submitQueryFilter(queryParams);
        });

        // Pagination
        $('body').on('click', '.page-link', function(e) {
            e.preventDefault();
            $(this).attr('disabled','disabled');

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


function removeItemByKeyInObject(object, key) {

    for (var i = 0; i < object.length; i++) {
        if (object[i].key == key) {
            object.splice(i, 1);
        }
    }

    return object;
}

function findOrReplaceInObject(object, key, value) {
    var findKey = false;
    for (var i = 0; i < object.length; i++) {
        if (object[i].key === key) {
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

function decodeUrlParamsToObject(url) {
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
    return decodeUrlParamsToObject(location.href);
}

const encodeDataToURL = (data) => {
    return data.map(value => `${value.key}=${encodeURIComponent(value.value)}`).join('&');
};

