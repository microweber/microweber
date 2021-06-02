/*jshint esversion: 6 */

class ContentFilter {

    setModuleId(moduleId) {
        this.moduleId = moduleId;
    };

    replaceKeyValuesAndApplyFilters(queryParams) {

        // Update values for keys in URL QUERY

        var redirectFilterUrl = getUrlAsArray();

        var i;
        for (i = 0; i < queryParams.length; i++) {
            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, queryParams[i].key, queryParams[i].value);
        }

        this.applyFilters(redirectFilterUrl);
    };

    applyFilters(redirectFilterUrl) {

        mw.spinner({
            element: $('#'+this.moduleId+ ''),
            size:"500px",
            decorate: false
        }).show();

        var encodedDataUrl = encodeDataToURL(redirectFilterUrl);

        $('#'+this.moduleId+ '').attr('ajax_filter', encodedDataUrl);

        mw.reload_module('#' + this.moduleId + '');

        window.history.pushState('', false, '?' + encodedDataUrl);
    };

    addDateRangePicker(params) {

        if (params.fromDate == '') {
            params.fromDate = false;
        }

        if (params.toDate == '') {
            params.toDate = false;
        }

        mw.lib.require("air_datepicker");

        var filterInstance = this;

        $('#' + params.id).datepicker({
            timepicker: false,
            range: true,
            multipleDates: true,
            multipleDatesSeparator: " - ",
            /* onRenderCell: function (d, cellType) {
                 var currentDate = d.getFullYear() + "-"+ d.getMonth()  + "-"+ d.getDate();
                 if (cellType == 'day' && currentDate == '') {
                     return {
                         html: '<div style="background:#20badd2e;border-radius:4px;color:#fff;padding:10px 11px;">'+d.getDate()+'</div>'
                     }
                 }
             },*/
            onSelect: function (fd, d, picker) {

                // Do nothing if selection was cleared
                if (!d[0]) return;
                if (!d[1]) return;

                var dateFromRange = d[0].getFullYear() + "-" + numericMonth(d[0]) + "-" + d[0].getDate();
                var dateToRange = d[1].getFullYear() + "-" + numericMonth(d[1]) + "-" + d[1].getDate();

                if (params.fromDate && params.toDate) {
                    if ((dateFromRange === params.fromDate) && (dateToRange === params.toDate)) {
                        return;
                    }
                }

                var redirectFilterUrl = getUrlAsArray();

                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'filters[from_date]', dateFromRange);
                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'filters[to_date]', dateToRange);

                filterInstance.applyFilters(redirectFilterUrl);
            }
        });

        if (params.fromDate && params.toDate) {
            $('#' + params.id).data('datepicker').selectDate([new Date(params.fromDate), new Date(params.toDate)]);
        }
    };

    setFilteringWhen(filtering) {
        this.filteringWhen = filtering;
    };

    init() {

        var filterInstance = this;

        // Apply filter button
        $('body').on('click' , '.js-filter-apply' , function() {
            filterInstance.applyFilters();
        });

        // Reset all filters
        $('body').on('click' , '.js-filter-reset' , function() {
            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl.splice(0,redirectFilterUrl.length);

            filterInstance.applyFilters(redirectFilterUrl);
        });

        // Active filters
        $('body').on('click' , '.js-filter-picked' , function() {

            var keys = $(this).data('key');
            var value = $(this).data('value');
            var removeKeys = keys.split(',');

            var redirectFilterUrl = getUrlAsArray();

            for (var i = 0; i < removeKeys.length; i++) {
                var filterKey = removeKeys[i];
                filterKey = filterKey.trim();

                for (var i = 0; i < redirectFilterUrl.length; i++) {
                    if ((redirectFilterUrl[i].key == filterKey) && (redirectFilterUrl[i].value == value)) {
                        redirectFilterUrl.splice(i, 1);
                    }
                }
            }

            filterInstance.applyFilters(redirectFilterUrl);

        });

        // Tags
        $('body').on('click' , '.js-filter-tag' , function() {

            var tagSlug = $(this).data('slug');

            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'tags[]', tagSlug);

            filterInstance.applyFilters(redirectFilterUrl);
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

            filterInstance.replaceKeyValuesAndApplyFilters(queryParams);
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

            filterInstance.replaceKeyValuesAndApplyFilters(queryParams);
        });

        // Custom fields
        $('body').on('change', '.js-filter-option-select', function(e) {

            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl = redirectFilterUrl.filter(function(e) {
                var elementKey = e.key;
                if (elementKey.indexOf("[]")) {
                    return false;
                }
            });

           var filterForm = $('.js-filter-form').serializeArray();
            $.each(filterForm, function(k, field) {
                var fieldName = field.name;
              //  console.log(fieldName);
                if (fieldName.indexOf("[]")) {
                    redirectFilterUrl.push({key: field.name, value: field.value});
                } else {
                    redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, field.name, field.value);
                }
            });

           // console.log(redirectFilterUrl);

            // filterInstance.applyFilters(redirectFilterUrl);
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

            var redirectFilterUrl = getUrlAsArray();
            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'search', $('.js-filter-search-field').val());
            redirectFilterUrl = removeItemByKeyInObject(redirectFilterUrl,'page');

            filterInstance.applyFilters(redirectFilterUrl);
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
            filterInstance.replaceKeyValuesAndApplyFilters(queryParams);
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
            filterInstance.replaceKeyValuesAndApplyFilters(queryParams);
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

function numericMonth(dt)
{
    return (dt.getMonth() < 9 ? '0' : '') + (dt.getMonth() + 1);
}
