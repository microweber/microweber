function getMonthName(date) {

	var d = new Date(date);
	var month = new Array(12);

	month[0] = "January";
	month[1] = "February";
	month[2] = "March";
	month[3] = "April";
	month[4] = "May";
	month[5] = "June";
	month[6] = "July";
	month[7] = "August";
	month[8] = "September";
	month[9] = "October";
	month[10] = "November";
	month[11] = "December";

	return month[d.getMonth()];
}

function getDayName(date) {

	var d = new Date(date);
	var weekday = getDaysOfWeek();

	return weekday[d.getDay()];
}

function getDaysOfWeek() {
	return ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
}

function getWeekOfMonthName(date) {

	var date = new Date(date);
	var days = getDaysOfWeek();
	var prefixes = ['First', 'Second', 'Third', 'Fourth', 'Last'];

	return prefixes[0 | date.getDate() / 7] + ' ' + days[date.getDay()];
}

Date.prototype.getWeek = function () {
	var onejan = new Date(this.getFullYear(), 0, 1);
	return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
};

Date.prototype.getWeekOfMonth = function(exact) {
    var month = this.getMonth()
        , year = this.getFullYear()
        , firstWeekday = new Date(year, month, 1).getDay()
        , lastDateOfMonth = new Date(year, month + 1, 0).getDate()
        , offsetDate = this.getDate() + firstWeekday - 1
        , index = 1 // start index at 0 or 1, your choice
        , weeksInMonth = index + Math.ceil((lastDateOfMonth + firstWeekday - 7) / 7)
        , week = index + Math.floor(offsetDate / 7)
    ;
    if (exact || week < 2 + index) return week;
    return week === weeksInMonth ? index + 5 : week;
};