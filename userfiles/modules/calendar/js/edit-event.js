


// Global Variables
var selectedWeekdays = [];

var repType = function () {
    var val = $('.js-recurrence-repeat-type').val()
    var currentRecurrence = $(".js-select-recurrence").val();
    if (currentRecurrence == "weekly_on_all_days") {
        toggleEveryWeekday(true);
        toggleWeekendDays(true);
    } else if (currentRecurrence == "every_weekday") {
        toggleEveryWeekday(true);
    }

    console.log(val)
    if (val === "week") {
        $('.js-recurrence-repeat-on').slideDown();
    } else {
        $('.js-recurrence-repeat-on').slideUp();
    }


    if (this.value === "month") {
        $('.js-recurrence-monthly-on').slideDown();
    } else {
        $('.js-recurrence-monthly-on').slideUp();
    }
};

function disableRecurrenceRepeatTypeDayWeekYear() {
    $("option[value='day']").attr('disabled', 'disabled');
    $("option[value='week']").attr('disabled', 'disabled');
    $("option[value='year']").attr('disabled', 'disabled');
}

function enableRecurrenceRepeatTypeDayWeekYear() {
    $("option[value='day']").removeAttr('disabled');
    $("option[value='week']").removeAttr('disabled');
    $("option[value='year']").removeAttr('disabled');
}

function disableRecurrenceRepeatTypeDayMonthYear() {
    $("option[value='day']").attr('disabled', 'disabled');
    $("option[value='month']").attr('disabled', 'disabled');
    $("option[value='year']").attr('disabled', 'disabled');
}

function enableRecurrenceRepeatTypeDayMonthYear() {
    $("option[value='day']").removeAttr('disabled');
    $("option[value='month']").removeAttr('disabled');
    $("option[value='year']").removeAttr('disabled');
}

function changeOptionsTextOnDaysNames(date) {

    var dateInstance = new Date(date);
    var dayName = getDayName(date);
    var dayNumber = dateInstance.getDate();
    var monthNameAndDayNumber = getMonthName(date) + " " + dayNumber;

    // Weekly on Saturday(dayName)
    $("option[value='weekly_on_the_day_name']").text("Weekly on " + dayName);

    // Monthly on the day 2(dayNumber)
    $("option[value='monthly_on_the_day_number']").text("Monthly on day " + dayNumber);

    // Monthly on the first(weekNumber) Saturday(dayName)
    $("option[value='monthly_on_the_week_number_day_name']").text("Monthly on the " + getWeekOfMonthName(date));

    // Annualy on March(monthName) 2(dayNumber)
    $("option[value='annually_on_the_month_name_day_number']").text("Annually on " + monthNameAndDayNumber);

    // Monthly on the day 2(dayNumber)
    $("option[value='the_day_number']").text("Monthly on day " + dayNumber);

    // Monthly on the first(weekNumber) Saturday(dayName)
    $("option[value='the_week_number_day_name']").text("Monthly on the " + getWeekOfMonthName(date));
}

function changeRecurrenceRepeatTypeToMonth() {
    if ($(".js-recurrence-repeat-type").val() !== "month") {
        $(".js-recurrence-repeat-type").val("month").trigger('change');
        $('.js-recurrence-monthly-on').show();
    }
}

function changeRecurrenceRepeatTypeToWeek() {
    if ($(".js-recurrence-repeat-type").val() !== "week") {
        $(".js-recurrence-repeat-type").val("week").trigger('change');
    }
}

function toggleEveryWeekday(checked) {

    if (checked) {
        addWeekDayGlobal('Monday');
        addWeekDayGlobal('Tuesday');
        addWeekDayGlobal('Wednesday');
        addWeekDayGlobal('Thursday');
        addWeekDayGlobal('Friday');
    } else {
        removeWeekDayGlobal('Monday');
        removeWeekDayGlobal('Tuesday');
        removeWeekDayGlobal('Wednesday');
        removeWeekDayGlobal('Thursday');
        removeWeekDayGlobal('Friday');
    }

    $("input[name*='recurrence_repeat_on[monday]']").prop("checked", checked);
    $("input[name*='recurrence_repeat_on[tuesday]']").prop("checked", checked);
    $("input[name*='recurrence_repeat_on[wednesday]']").prop("checked", checked);
    $("input[name*='recurrence_repeat_on[thursday]']").prop("checked", checked);
    $("input[name*='recurrence_repeat_on[friday]']").prop("checked", checked);
}

function toggleWeekendDays(checked) {

    if (checked) {
        addWeekDayGlobal('Sunday');
        addWeekDayGlobal('Saturday');
    } else {
        removeWeekDayGlobal('Sunday');
        removeWeekDayGlobal('Saturday');
    }

    $("input[name*='recurrence_repeat_on[sunday]']").prop("checked", checked);
    $("input[name*='recurrence_repeat_on[saturday]']").prop("checked", checked);
}


function addWeekDayGlobal(dayName) {
    // if not found in global array
    if (isInArray(dayName, selectedWeekdays) == false) {
        selectedWeekdays.push(dayName);
    }
}

function removeWeekDayGlobal(dayName) {
    if (isInArray(dayName, selectedWeekdays)) {
        removeElementFromArray(selectedWeekdays, dayName);
    }
}

function refreshWeeklyOnTheDaysNamesOption() {

    $("option[value='weekly_on_the_days_names']").attr('hidden');

    if (selectedWeekdays.length == 7) {
        // change to all days


    } else if (selectedWeekdays.length == 1) {
        // change to one day

    } else if (selectedWeekdays.length > 1 && selectedWeekdays.length < 7) {
        $("option[value='weekly_on_the_days_names']").removeAttr('hidden');
        $("option[value='weekly_on_the_days_names']").text("Weekly on " + selectedWeekdays.join(', '));
    }
}

var __recTime = null;

var handleRecChange = function () {

    clearTimeout(__recTime);

    __recTime = setTimeout(function (){

        var val = $('select.js-select-recurrence').val();

        // Enable click on recurrence repeat type
        enableRecurrenceRepeatTypeDayMonthYear();
        enableRecurrenceRepeatTypeDayWeekYear();

        // Reset repeat type
        $(".js-recurrence-repeat-type").val("day").trigger('change');
        $('.js-custom-recurrence-wrapper').hide();
        $('.js-recurrence-monthly-on').hide();

        if (val === "custom") {
            $('.js-custom-recurrence-wrapper').show();
        } else if (val === "annually_on_custom_day") {
            $('.js-custom-recurrence-wrapper').show();
        } else if (val === "weekly_on_the_day_name") {

            // Reset globals
            selectedWeekdays.length = 0;

            toggleEveryWeekday(false);
            toggleWeekendDays(false);

            $('.js-custom-recurrence-wrapper').show();
            changeRecurrenceRepeatTypeToWeek();

            // Disable to click on other options
            disableRecurrenceRepeatTypeDayMonthYear();

            // Change to day of selected day
            var startDate = $('.js-start-date').val();
            var dayName = getDayName(startDate);
            $("input[name*='recurrence_repeat_on[" + dayName.toLowerCase() + "]']").click();

        } else if (val === "weekly_on_the_days_names") {
            $('.js-custom-recurrence-wrapper').show();
            changeRecurrenceRepeatTypeToWeek();
        } else if (val === "weekly_on_all_days") {
            $('.js-custom-recurrence-wrapper').show();


            // Disable to click on other options
            disableRecurrenceRepeatTypeDayMonthYear();

            changeRecurrenceRepeatTypeToWeek();
            toggleEveryWeekday(true);
            toggleWeekendDays(true);
            refreshWeeklyOnTheDaysNamesOption();

            var recurrence_repeat_on = $.parseJSON(event_data.recurrence_repeat_on);
            $("input[name*='recurrence_repeat_on[']").each(function (){

                this.checked = recurrence_repeat_on[this.name.split('[')[1].split(']')[0]] == 1;
            });


        } else if (val === "every_weekday") {
            // Reset globals
            selectedWeekdays.length = 0;

            toggleEveryWeekday(false);
            toggleWeekendDays(false);

            $('.js-custom-recurrence-wrapper').show();
            // Disable to click on other options
            disableRecurrenceRepeatTypeDayMonthYear();

            changeRecurrenceRepeatTypeToWeek();
            toggleEveryWeekday(true);
        } else if (val === "monthly_on_the_day_number") {
            changeRecurrenceRepeatTypeToMonth();
            $('.js-custom-recurrence-wrapper').show();
            disableRecurrenceRepeatTypeDayWeekYear();
            $(".js-recurrence-monthly-on").val("the_day_number").trigger('change');
        } else if (val === "monthly_on_the_week_number_day_name") {
            changeRecurrenceRepeatTypeToMonth();
            $('.js-custom-recurrence-wrapper').show();
            disableRecurrenceRepeatTypeDayWeekYear();

            $(".js-recurrence-monthly-on").val("the_week_number_day_name").trigger('change');
        }
    }, 78);
}



$(document).ready(function () {

    // Form scripting
    $('[name="start_date"], [name="end_date"]').datepicker({
        zIndex: 1105,
        format: 'yyyy-mm-dd'
    });
    $('[name="start_time"], [name="end_time"]').timepicker({
        scrollDefault: 'now',
        format: 'yyyy-mm-dd'
    });

    var startDate = $('.js-start-date').val();
    changeOptionsTextOnDaysNames(startDate);

    // Chage texts on options
    $('.js-start-date').on('change', function () {
        var startDate = $('.js-start-date').val();
        changeOptionsTextOnDaysNames(startDate);
        if ($(".js-select-recurrence").val() == "weekly_on_the_day_name") {
            // Remove old selected
            toggleEveryWeekday(false);
            toggleWeekendDays(false);
            var dayName = getDayName(startDate);
            $("input[name*='recurrence_repeat_on[" + dayName.toLowerCase() + "]']").prop("checked", true);
        }
    });

    $('.js-all-day').click(function () {
        if ($(this).prop("checked")) {
            $('.js-start-time-wrapper').hide();
            $('.js-end-time-wrapper').hide();
        } else {
            $('.js-start-time-wrapper').show();
            $('.js-end-time-wrapper').show();
        }
    });

    $('.js-select-recurrence').on('change', function (){
        handleRecChange();
    });


    $('select.js-recurrence-repeat-type').on('change', function () {
        repType();
    });

    $("input[name*='recurrence_repeat_on']").on('change', function () {

        var dayName = $(this).attr('name');
        dayName = dayName.replace("recurrence_repeat_on[", "");
        dayName = dayName.replace("]", "");
        dayName = capitalize(dayName);

        if (this.checked) {
            addWeekDayGlobal(dayName);
        } else {
            removeWeekDayGlobal(dayName);
        }

        refreshWeeklyOnTheDaysNamesOption();
    });


});

$(document).ready( function (){console.log(event_data)

    setTimeout(function (){

        if (typeof event_data.recurrence_type !== 'undefined') {

            $(".js-select-recurrence").val(event_data.recurrence_type).trigger('change');

            if (event_data.recurrence_type == 'custom') {
                $('.js-custom-recurrence-wrapper').show();
            }
            if (typeof event_data.recurrence_repeat_type !== 'undefined') {
                $("select.js-recurrence-repeat-type").val(event_data.recurrence_repeat_type).trigger('change');

                if (event_data.recurrence_repeat_type == 'week') {
                    $('.js-recurrence-repeat-on').show();
                    var recurrence_repeat_on = $.parseJSON(event_data.recurrence_repeat_on);
                    $("input[name*='recurrence_repeat_on[']").each(function (){

                        this.checked = recurrence_repeat_on[this.name.split('[')[1].split(']')[0]] == 1;
                    });
                 }
                if (event_data.recurrence_repeat_type == 'month') {
                    $('.js-recurrence-monthly-on').show();
                }
            }
        }

        if (typeof event_data.all_day !== 'undefined') {
            if (event_data.all_day == 1) {
                $('.js-all-day').click();
                $('.js-start-time-wrapper').hide();
                $('.js-end-time-wrapper').hide();
            }
        }
    }, 333)

    // repType();
})
