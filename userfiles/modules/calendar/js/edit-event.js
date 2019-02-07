$(document).ready(function () {

    // Edit event fill the form and select the options
    if (typeof event_data.recurrence_type !== 'undefined') {
        $(".js-select-recurrence").val(event_data.recurrence_type).change();

        if (event_data.recurrence_type == 'custom') {
            $('.js-custom-recurrence-wrapper').show();
        }
        if (typeof event_data.recurrence_repeat_type !== 'undefined') {
            $(".js-recurrence-repeat-type").val(event_data.recurrence_repeat_type).change();

            if (event_data.recurrence_repeat_type == 'week') {
                $('.js-recurrence-repeat-on').show();

                var recurrence_repeat_on = $.parseJSON(event_data.recurrence_repeat_on);
                $.each(recurrence_repeat_on, function (key, value) {
                    if (value == 1) { // its on
                        $("input[name*='recurrence_repeat_on[" + key.toLowerCase() + "]']").prop("checked", true);
                    }
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

    // Global Variables
    var selectedWeekdays = new Array();

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
    $('.js-start-date').change(function () {
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

    $('.js-select-recurrence').change(function () {

        // Enable click on recurrence repeat type
        enableRecurrenceRepeatTypeDayMonthYear();
        enableRecurrenceRepeatTypeDayWeekYear();

        // Reset repeat type
        $(".js-recurrence-repeat-type").val("day").change();
        $('.js-custom-recurrence-wrapper').hide();
        $('.js-recurrence-monthly-on').hide();

        if (this.value == "custom") {
            $('.js-custom-recurrence-wrapper').show();
        } else if (this.value == "annually_on_custom_day") {
            $('.js-custom-recurrence-wrapper').show();
        } else if (this.value == "weekly_on_the_day_name") {

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

        } else if (this.value == "weekly_on_the_days_names") {
            $('.js-custom-recurrence-wrapper').show();
            changeRecurrenceRepeatTypeToWeek();



        } else if (this.value == "weekly_on_all_days") {
            $('.js-custom-recurrence-wrapper').show();

            // Disable to click on other options
            disableRecurrenceRepeatTypeDayMonthYear();

            changeRecurrenceRepeatTypeToWeek();
            toggleEveryWeekday(true);
            toggleWeekendDays(true);
            refreshWeeklyOnTheDaysNamesOption();

        } else if (this.value == "every_weekday") {
            // Reset globals
            selectedWeekdays.length = 0;

            toggleEveryWeekday(false);
            toggleWeekendDays(false);

            $('.js-custom-recurrence-wrapper').show();
            // Disable to click on other options
            disableRecurrenceRepeatTypeDayMonthYear();

            changeRecurrenceRepeatTypeToWeek();
            toggleEveryWeekday(true);
        } else if (this.value == "monthly_on_the_day_number") {
            changeRecurrenceRepeatTypeToMonth();
            $('.js-custom-recurrence-wrapper').show();
            disableRecurrenceRepeatTypeDayWeekYear();

            $(".js-recurrence-monthly-on").val("the_day_number").change();

        } else if (this.value == "monthly_on_the_week_number_day_name") {
            changeRecurrenceRepeatTypeToMonth();
            $('.js-custom-recurrence-wrapper').show();
            disableRecurrenceRepeatTypeDayWeekYear();

            $(".js-recurrence-monthly-on").val("the_week_number_day_name").change();
        }

    });


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
            $(".js-recurrence-repeat-type").val("month").change();
            $('.js-recurrence-monthly-on').show();
        }
    }

    function changeRecurrenceRepeatTypeToWeek() {
        if ($(".js-recurrence-repeat-type").val() !== "week") {
            $(".js-recurrence-repeat-type").val("week").change();
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

    $('.js-recurrence-repeat-type').change(function () {

        var currentRecurrence = $(".js-select-recurrence").val();
        if (currentRecurrence == "weekly_on_all_days") {
            toggleEveryWeekday(true);
            toggleWeekendDays(true);
        } else if (currentRecurrence == "every_weekday") {
            toggleEveryWeekday(true);
        }

        $('.js-recurrence-repeat-on').slideUp();
        $('.js-recurrence-monthly-on').slideUp();
        if (this.value == "week") {
            $('.js-recurrence-repeat-on').slideDown();
        } else if (this.value == "month") {
            $('.js-recurrence-monthly-on').slideDown();
        }
    });

    $("input[name*='recurrence_repeat_on']").change(function () {

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
});