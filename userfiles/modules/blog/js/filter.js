/*jshint esversion: 6 */

class ContentFilter {

    setModuleId(moduleId) {
        this.moduleId = moduleId;
    }

    replaceKeyValuesAndApplyFilters(queryParams) {

        // Update values for keys in URL QUERY

        var redirectFilterUrl = getUrlAsArray();

        var i;
        for (i = 0; i < queryParams.length; i++) {
            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, queryParams[i].key, queryParams[i].value);
        }

        this.applyFilters(redirectFilterUrl);
    }

    applyFilters(redirectFilterUrl) {

        mw.spinner({
            element: $('#'+this.moduleId+ ''),
            size:"36px",
            decorate: true
        }).show();

        var encodedDataUrl = encodeDataToURL(redirectFilterUrl);
       // console.log(encodedDataUrl);

        $('#'+this.moduleId+ '').attr('ajax_filter', encodedDataUrl);
        var scope = this;
        mw.reload_module('#' + this.moduleId + '' , function () {
            mw.spinner({
                element: $('#'+scope.moduleId+ ''),
                size:"300px",
                decorate: true
            }).remove();

        });
        window.history.pushState('', false, '?' + encodedDataUrl);
    }

    addDateRangePicker(params) {

        if (params.filter.fromDate == '') {
            params.filter.fromDate = false;
        }

        if (params.filter.toDate == '') {
            params.filter.toDate = false;
        }

        mw.lib.require("air_datepicker");

        var filterInstance = this;

        if ($.fn.datepicker) {
            $.fn.datepicker.language['en'] = {
                days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                daysMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                daysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                today: 'Today',
                clear: 'Clear',
                dateFormat: 'yyyy-mm-dd',
                firstDay: 0
            };
        }

        var datePickerBaseSetup = {
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

                var dateFromRange = d[0].getFullYear() + "-" + numericMonth(d[0]) + "-" + numericDate(d[0]);
                var dateToRange = d[1].getFullYear() + "-" + numericMonth(d[1]) + "-" + numericDate(d[1]);

                if (params.filter.fromDate && params.filter.toDate) {
                    if ((dateFromRange === params.filter.fromDate) && (dateToRange === params.filter.toDate)) {
                        return;
                    }
                }

                var redirectFilterUrl = getUrlAsArray();

                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'date_range['+params.filter.nameKey+'][from]', dateFromRange);
                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'date_range['+params.filter.nameKey+'][to]', dateToRange);

                if (filterInstance.filteringWhen == 'automatically') {
                    filterInstance.applyFilters(redirectFilterUrl);
                }

                // Update instance redirect filter
                mw.redirectFilterUrl = redirectFilterUrl;
            }
        }

        if (params.setup) {
            var datePickerSetup = {...datePickerBaseSetup, ...params.setup};
            $('#' + params.id).datepicker(datePickerSetup);
        } else {
            $('#' + params.id).datepicker(datePickerBaseSetup);
        }

        if (params.filter.fromDate && params.filter.toDate) {
            $('#' + params.id).data('datepicker').selectDate([
                 new Date(params.filter.fromDate +'T00:00:00.000Z'),
                 new Date(params.filter.toDate+'T00:00:00.000Z')
             ]);
        }
    }

    setFilteringWhen(filtering) {
        this.filteringWhen = filtering;
    }

    init() {

        var filterInstance = this;

        // Apply filter button
        $('body').on('click' , '.js-filter-apply' , function() {
            if (mw.redirectFilterUrl) {
                filterInstance.applyFilters(mw.redirectFilterUrl);
            }
        });

        // Reset all filters
        $('body').on('click' , '.js-filter-reset' , function() {
            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl.splice(0,redirectFilterUrl.length);

            filterInstance.applyFilters(redirectFilterUrl);
        });

        // Active filters
        $('body').on('click' , '.js-filter-picked' , function() {

            var key = $(this).data('key');
            var value = $(this).data('value');
            var removeKeys = key.split(',');

            var redirectFilterUrl = getUrlAsArray();

            if (removeKeys.length > 1) {
                for (var irk = 0; irk < removeKeys.length; irk++) {
                    var filterKey = removeKeys[irk];
                    filterKey = filterKey.trim();
                    for (var irfu = 0; irfu < redirectFilterUrl.length; irfu++) {
                        if (redirectFilterUrl[irfu].key == filterKey) {
                            redirectFilterUrl.splice(irfu, 1);
                        }
                    }
                }
            } else {
                var filterKey = key + '';
                filterKey = filterKey.trim();

                var filterValue = value + '';
                filterValue = filterValue.trim();

                for (var irfu = 0; irfu < redirectFilterUrl.length; irfu++) {
                    if ((redirectFilterUrl[irfu].key == filterKey) && (redirectFilterUrl[irfu].value == filterValue)) {
                        redirectFilterUrl.splice(irfu, 1);
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

            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'limit', limit);
            redirectFilterUrl = removeItemByKeyInObject(redirectFilterUrl,'page');

            filterInstance.applyFilters(redirectFilterUrl);
        });

        // Sort
        $('body').on('change' , '.js-filter-change-sort' , function() {

            $(this).attr('disabled','disabled');

            var sortOrderField = $(".js-filter-change-sort").children('option:selected');

            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'sort', sortOrderField.attr('data-sort'));
            redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'order', sortOrderField.attr('data-order'));

            redirectFilterUrl = removeItemByKeyInObject(redirectFilterUrl,'page');

            filterInstance.applyFilters(redirectFilterUrl);
        });

        // Custom fields
        $('body').on('change', '.js-filter-option-select', function(e) {

            var redirectFilterUrl = getUrlAsArray();

            redirectFilterUrl = redirectFilterUrl.filter(function(e) {
                var elementKey = e.key;
                if (!elementKey.contains("filters[") && !elementKey.contains("categories[")) {
                    return true;
                }
            });

           var filterForm = $('.js-filter-form').serializeArray();
            $.each(filterForm, function(k, field) {
                var addToUrl = true;
                if (field.value == '' || field.value == ' ') {
                    addToUrl = false;
                }
                if (addToUrl) {
                    var fieldName = field.name;
                    if (fieldName.indexOf("[]")) {
                        redirectFilterUrl.push({key: field.name, value: field.value});
                    } else {
                        redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, field.name, field.value);
                    }
                }
            });

            if (filterInstance.filteringWhen == 'automatically') {
                 filterInstance.applyFilters(redirectFilterUrl);
            }

            // Update instance redirect filter
            mw.redirectFilterUrl = redirectFilterUrl;
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

            if (typeof $(this).attr('data-category-id') == "undefined") {
                return;
            }

            e.preventDefault();

            var categoryId = $(this).attr('data-category-id');
            var queryParams = [];
            queryParams.push({
                key:'category',
                value:categoryId
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

    }
}


function removeItemByKeyInObject(object, key) {

    for (var i = 0; i < object.length; ) {
        if (object[i].key == key) {
            object.splice(i, 1);
        } else {
            i++;
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
function numericDate(dt)
{
    var date = dt.getDate();

    if (date < 10) {
        date = '0' + date;
    }

    return date;
}

$(document).ready(function(){
    // Add minus icon for collapse element which is open by default
    $(".collapse.show").each(function(){
        $(this).prev(".card-header").find(".mdi").addClass("mdi-minus").removeClass("mdi-plus");
    });
    // Toggle plus minus icon on show hide of collapse element
    $(".collapse").on('show.bs.collapse', function(){
        $(this).prev(".card-header").find(".mdi").removeClass("mdi-plus").addClass("mdi-minus");
    }).on('hide.bs.collapse', function(){
        $(this).prev(".card-header").find(".mdi").removeClass("mdi-minus").addClass("mdi-plus");
    });
});
