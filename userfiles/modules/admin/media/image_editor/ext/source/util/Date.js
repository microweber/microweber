/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Date
 *
 * The date parsing and format syntax is a subset of
 * <a href="http://www.php.net/date">PHP's date() function</a>, and the formats that are
 * supported will provide results equivalent to their PHP versions.
 *
 * The following is a list of all currently supported formats:
 *<pre>
Format  Description                                                               Example returned values
------  -----------------------------------------------------------------------   -----------------------
  d     Day of the month, 2 digits with leading zeros                             01 to 31
  D     A short textual representation of the day of the week                     Mon to Sun
  j     Day of the month without leading zeros                                    1 to 31
  l     A full textual representation of the day of the week                      Sunday to Saturday
  N     ISO-8601 numeric representation of the day of the week                    1 (for Monday) through 7 (for Sunday)
  S     English ordinal suffix for the day of the month, 2 characters             st, nd, rd or th. Works well with j
  w     Numeric representation of the day of the week                             0 (for Sunday) to 6 (for Saturday)
  z     The day of the year (starting from 0)                                     0 to 364 (365 in leap years)
  W     ISO-8601 week number of year, weeks starting on Monday                    01 to 53
  F     A full textual representation of a month, such as January or March        January to December
  m     Numeric representation of a month, with leading zeros                     01 to 12
  M     A short textual representation of a month                                 Jan to Dec
  n     Numeric representation of a month, without leading zeros                  1 to 12
  t     Number of days in the given month                                         28 to 31
  L     Whether it's a leap year                                                  1 if it is a leap year, 0 otherwise.
  o     ISO-8601 year number (identical to (Y), but if the ISO week number (W)    Examples: 1998 or 2004
        belongs to the previous or next year, that year is used instead)
  Y     A full numeric representation of a year, 4 digits                         Examples: 1999 or 2003
  y     A two digit representation of a year                                      Examples: 99 or 03
  a     Lowercase Ante meridiem and Post meridiem                                 am or pm
  A     Uppercase Ante meridiem and Post meridiem                                 AM or PM
  g     12-hour format of an hour without leading zeros                           1 to 12
  G     24-hour format of an hour without leading zeros                           0 to 23
  h     12-hour format of an hour with leading zeros                              01 to 12
  H     24-hour format of an hour with leading zeros                              00 to 23
  i     Minutes, with leading zeros                                               00 to 59
  s     Seconds, with leading zeros                                               00 to 59
  u     Milliseconds, with leading zeroes (arbitrary number of digits allowed)    Examples:
                                                                                  001 (i.e. 1ms) or
                                                                                  100 (i.e. 100ms) or
                                                                                  999 (i.e. 999ms) or
                                                                                  999876543210 (i.e. 999.876543210ms)
  O     Difference to Greenwich time (GMT) in hours and minutes                   Example: +1030
  P     Difference to Greenwich time (GMT) with colon between hours and minutes   Example: -08:00
  T     Timezone abbreviation of the machine running the code                     Examples: EST, MDT, PDT ...
  Z     Timezone offset in seconds (negative if west of UTC, positive if east)    -43200 to 50400
  c     ISO 8601 date (note: milliseconds, if present, must be specified with     Examples:
        at least 1 digit. There is no limit to how many digits the millisecond    2007-04-17T15:19:21+08:00 or
        value may contain. see http://www.w3.org/TR/NOTE-datetime for more info)  2008-03-16T16:18:22Z or
                                                                                  2009-02-15T17:17:23.9+01:00 or
                                                                                  2010-01-14T18:16:24,999876543-07:00
  U     Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)                1193432466 or -2138434463
</pre>
 *
 * Example usage (note that you must escape format specifiers with '\\' to render them as character literals):
 * <pre><code>
// Sample date:
// 'Wed Jan 10 2007 15:05:01 GMT-0600 (Central Standard Time)'

var dt = new Date('1/10/2007 03:05:01 PM GMT-0600');
document.write(dt.format('Y-m-d'));                           // 2007-01-10
document.write(dt.format('F j, Y, g:i a'));                   // January 10, 2007, 3:05 pm
document.write(dt.format('l, \\t\\he jS \\of F Y h:i:s A'));  // Wednesday, the 10th of January 2007 03:05:01 PM
 </code></pre>
 *
 * Here are some standard date/time patterns that you might find helpful.  They
 * are not part of the source of Date.js, but to use them you can simply copy this
 * block of code into any script that is included after Date.js and they will also become
 * globally available on the Date object.  Feel free to add or remove patterns as needed in your code.
 * <pre><code>
Date.patterns = {
    ISO8601Long:"Y-m-d H:i:s",
    ISO8601Short:"Y-m-d",
    ShortDate: "n/j/Y",
    LongDate: "l, F d, Y",
    FullDateTime: "l, F d, Y g:i:s A",
    MonthDay: "F d",
    ShortTime: "g:i A",
    LongTime: "g:i:s A",
    SortableDateTime: "Y-m-d\\TH:i:s",
    UniversalSortableDateTime: "Y-m-d H:i:sO",
    YearMonth: "F, Y"
};
</code></pre>
 *
 * Example usage:
 * <pre><code>
var dt = new Date();
document.write(dt.format(Date.patterns.ShortDate));
 </code></pre>
 */

/*
 * Most of the date-formatting functions below are the excellent work of Baron Schwartz.
 * (see http://www.xaprb.com/blog/2005/12/12/javascript-closures-for-runtime-efficiency/)
 * They generate precompiled functions from format patterns instead of parsing and
 * processing each pattern every time a date is formatted. These functions are available
 * on every Date object.
 */

(function() {
// private
Date.formatCodeToRegex = function(character, currentGroup) {
    // Note: currentGroup - position in regex result array (see notes for Date.parseCodes below)
    var p = Date.parseCodes[character];

    if (p) {
      p = Ext.type(p) == 'function'? p() : p;
      Date.parseCodes[character] = p; // reassign function result to prevent repeated execution
    }

    return p? Ext.applyIf({
      c: p.c? String.format(p.c, currentGroup || "{0}") : p.c
    }, p) : {
        g:0,
        c:null,
        s:Ext.escapeRe(character) // treat unrecognised characters as literals
    }
}

// private shorthand for Date.formatCodeToRegex since we'll be using it fairly often
var $f = Date.formatCodeToRegex;

Ext.apply(Date, {
    // private
    parseFunctions: {count:0},
    parseRegexes: [],
    formatFunctions: {count:0},
    daysInMonth : [31,28,31,30,31,30,31,31,30,31,30,31],
    y2kYear : 50,

    /**
     * Date interval constant
     * @static
     * @type String
     */
    MILLI : "ms",

    /**
     * Date interval constant
     * @static
     * @type String
     */
    SECOND : "s",

    /**
     * Date interval constant
     * @static
     * @type String
     */
    MINUTE : "mi",

    /** Date interval constant
     * @static
     * @type String
     */
    HOUR : "h",

    /**
     * Date interval constant
     * @static
     * @type String
     */
    DAY : "d",

    /**
     * Date interval constant
     * @static
     * @type String
     */
    MONTH : "mo",

    /**
     * Date interval constant
     * @static
     * @type String
     */
    YEAR : "y",

    /**
     * An array of textual day names.
     * Override these values for international dates.
     * Example:
     *<pre><code>
    Date.dayNames = [
      'SundayInYourLang',
      'MondayInYourLang',
      ...
    ];
    </code></pre>
     * @type Array
     * @static
     */
    dayNames : [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    ],

    /**
     * An array of textual month names.
     * Override these values for international dates.
     * Example:
     *<pre><code>
    Date.monthNames = [
      'JanInYourLang',
      'FebInYourLang',
      ...
    ];
    </code></pre>
     * @type Array
     * @static
     */
    monthNames : [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ],

    /**
     * An object hash of zero-based javascript month numbers (with short month names as keys. note: keys are case-sensitive).
     * Override these values for international dates.
     * Example:
     *<pre><code>
    Date.monthNumbers = {
      'ShortJanNameInYourLang':0,
      'ShortFebNameInYourLang':1,
      ...
    };
    </code></pre>
     * @type Object
     * @static
     */
    monthNumbers : {
        Jan:0,
        Feb:1,
        Mar:2,
        Apr:3,
        May:4,
        Jun:5,
        Jul:6,
        Aug:7,
        Sep:8,
        Oct:9,
        Nov:10,
        Dec:11
    },

    /**
     * Get the short month name for the given month number.
     * Override this function for international dates.
     * @param {Number} month A zero-based javascript month number.
     * @return {String} The short month name.
     * @static
     */
    getShortMonthName : function(month) {
        return Date.monthNames[month].substring(0, 3);
    },

    /**
     * Get the short day name for the given day number.
     * Override this function for international dates.
     * @param {Number} day A zero-based javascript day number.
     * @return {String} The short day name.
     * @static
     */
    getShortDayName : function(day) {
        return Date.dayNames[day].substring(0, 3);
    },

    /**
     * Get the zero-based javascript month number for the given short/full month name.
     * Override this function for international dates.
     * @param {String} name The short/full month name.
     * @return {Number} The zero-based javascript month number.
     * @static
     */
    getMonthNumber : function(name) {
        // handle camel casing for english month names (since the keys for the Date.monthNumbers hash are case sensitive)
        return Date.monthNumbers[name.substring(0, 1).toUpperCase() + name.substring(1, 3).toLowerCase()];
    },

    /**
     * The base format-code to formatting-function hashmap used by the {@link #format} method.
     * Formatting functions are strings (or functions which return strings) which
     * will return the appropriate value when evaluated in the context of the Date object
     * from which the {@link #format} method is called.
     * Add to / override these mappings for custom date formatting.
     * Note: Date.format() treats characters as literals if an appropriate mapping cannot be found.
     * Example:
    <pre><code>
    Date.formatCodes.x = "String.leftPad(this.getDate(), 2, '0')";
    (new Date()).format("X"); // returns the current day of the month
    </code></pre>
     * @type Object
     * @static
     */
    formatCodes : {
        d: "String.leftPad(this.getDate(), 2, '0')",
        D: "Date.getShortDayName(this.getDay())", // get localised short day name
        j: "this.getDate()",
        l: "Date.dayNames[this.getDay()]",
        N: "(this.getDay() ? this.getDay() : 7)",
        S: "this.getSuffix()",
        w: "this.getDay()",
        z: "this.getDayOfYear()",
        W: "String.leftPad(this.getWeekOfYear(), 2, '0')",
        F: "Date.monthNames[this.getMonth()]",
        m: "String.leftPad(this.getMonth() + 1, 2, '0')",
        M: "Date.getShortMonthName(this.getMonth())", // get localised short month name
        n: "(this.getMonth() + 1)",
        t: "this.getDaysInMonth()",
        L: "(this.isLeapYear() ? 1 : 0)",
        o: "(this.getFullYear() + (this.getWeekOfYear() == 1 && this.getMonth() > 0 ? +1 : (this.getWeekOfYear() >= 52 && this.getMonth() < 11 ? -1 : 0)))",
        Y: "this.getFullYear()",
        y: "('' + this.getFullYear()).substring(2, 4)",
        a: "(this.getHours() < 12 ? 'am' : 'pm')",
        A: "(this.getHours() < 12 ? 'AM' : 'PM')",
        g: "((this.getHours() % 12) ? this.getHours() % 12 : 12)",
        G: "this.getHours()",
        h: "String.leftPad((this.getHours() % 12) ? this.getHours() % 12 : 12, 2, '0')",
        H: "String.leftPad(this.getHours(), 2, '0')",
        i: "String.leftPad(this.getMinutes(), 2, '0')",
        s: "String.leftPad(this.getSeconds(), 2, '0')",
        u: "String.leftPad(this.getMilliseconds(), 3, '0')",
        O: "this.getGMTOffset()",
        P: "this.getGMTOffset(true)",
        T: "this.getTimezone()",
        Z: "(this.getTimezoneOffset() * -60)",
        c: function() { // ISO-8601 -- GMT format
            for (var c = "Y-m-dTH:i:sP", code = [], i = 0, l = c.length; i < l; ++i) {
                var e = c.charAt(i);
                code.push(e == "T" ? "'T'" : Date.getFormatCode(e)); // treat T as a character literal
            }
            return code.join(" + ");
        },
        /*
        c: function() { // ISO-8601 -- UTC format
            return [
              "this.getUTCFullYear()", "'-'",
              "String.leftPad(this.getUTCMonth() + 1, 2, '0')", "'-'",
              "String.leftPad(this.getUTCDate(), 2, '0')",
              "'T'",
              "String.leftPad(this.getUTCHours(), 2, '0')", "':'",
              "String.leftPad(this.getUTCMinutes(), 2, '0')", "':'",
              "String.leftPad(this.getUTCSeconds(), 2, '0')",
              "'Z'"
            ].join(" + ");
        },
        */
        U: "Math.round(this.getTime() / 1000)"
    },

    /**
     * Parses the passed string using the specified format. Note that this function expects dates in normal calendar
     * format, meaning that months are 1-based (1 = January) and not zero-based like in JavaScript dates.  Any part of
     * the date format that is not specified will default to the current date value for that part.  Time parts can also
     * be specified, but default to 0.  Keep in mind that the input date string must precisely match the specified format
     * string or the parse operation will fail.
     * Example Usage:
     *<pre><code>
    //dt = Fri May 25 2007 (current date)
    var dt = new Date();

    //dt = Thu May 25 2006 (today's month/day in 2006)
    dt = Date.parseDate("2006", "Y");

    //dt = Sun Jan 15 2006 (all date parts specified)
    dt = Date.parseDate("2006-01-15", "Y-m-d");

    //dt = Sun Jan 15 2006 15:20:01 GMT-0600 (CST)
    dt = Date.parseDate("2006-01-15 3:20:01 PM", "Y-m-d h:i:s A" );
    </code></pre>
     * @param {String} input The unparsed date as a string.
     * @param {String} format The format the date is in.
     * @return {Date} The parsed date.
     * @static
     */
    parseDate : function(input, format) {
        var p = Date.parseFunctions;
        if (p[format] == null) {
            Date.createParser(format);
        }
        var func = p[format];
        return Date[func](input);
    },

    // private
    getFormatCode : function(character) {
        var f = Date.formatCodes[character];

        if (f) {
          f = Ext.type(f) == 'function'? f() : f;
          Date.formatCodes[character] = f; // reassign function result to prevent repeated execution
        }

        // note: unknown characters are treated as literals
        return f || ("'" + String.escape(character) + "'");
    },

    // private
    createNewFormat : function(format) {
        var funcName = "format" + Date.formatFunctions.count++;
        Date.formatFunctions[format] = funcName;
        var code = "Date.prototype." + funcName + " = function(){return ";
        var special = false;
        var ch = '';
        for (var i = 0; i < format.length; ++i) {
            ch = format.charAt(i);
            if (!special && ch == "\\") {
                special = true;
            }
            else if (special) {
                special = false;
                code += "'" + String.escape(ch) + "' + ";
            }
            else {
                code += Date.getFormatCode(ch) + " + ";
            }
        }
        eval(code.substring(0, code.length - 3) + ";}");
    },

    // private
    createParser : function(format) {
        var funcName = "parse" + Date.parseFunctions.count++;
        var regexNum = Date.parseRegexes.length;
        var currentGroup = 1;
        Date.parseFunctions[format] = funcName;

        var code = "Date." + funcName + " = function(input){\n"
            + "var y, m, d, h = 0, i = 0, s = 0, ms = 0, o, z, u, v;\n"
            + "input = String(input);\n"
            + "d = new Date();\n"
            + "y = d.getFullYear();\n"
            + "m = d.getMonth();\n"
            + "d = d.getDate();\n"
            + "var results = input.match(Date.parseRegexes[" + regexNum + "]);\n"
            + "if (results && results.length > 0) {";
        var regex = "";

        var special = false;
        var ch = '';
        for (var i = 0; i < format.length; ++i) {
            ch = format.charAt(i);
            if (!special && ch == "\\") {
                special = true;
            }
            else if (special) {
                special = false;
                regex += String.escape(ch);
            }
            else {
                var obj = Date.formatCodeToRegex(ch, currentGroup);
                currentGroup += obj.g;
                regex += obj.s;
                if (obj.g && obj.c) {
                    code += obj.c;
                }
            }
        }

        code += "if (u){\n"
            + "v = new Date(u * 1000);\n" // give top priority to UNIX time
            + "}else if (y >= 0 && m >= 0 && d > 0 && h >= 0 && i >= 0 && s >= 0 && ms >= 0){\n"
            + "v = new Date(y, m, d, h, i, s, ms);\n"
            + "}else if (y >= 0 && m >= 0 && d > 0 && h >= 0 && i >= 0 && s >= 0){\n"
            + "v = new Date(y, m, d, h, i, s);\n"
            + "}else if (y >= 0 && m >= 0 && d > 0 && h >= 0 && i >= 0){\n"
            + "v = new Date(y, m, d, h, i);\n"
            + "}else if (y >= 0 && m >= 0 && d > 0 && h >= 0){\n"
            + "v = new Date(y, m, d, h);\n"
            + "}else if (y >= 0 && m >= 0 && d > 0){\n"
            + "v = new Date(y, m, d);\n"
            + "}else if (y >= 0 && m >= 0){\n"
            + "v = new Date(y, m);\n"
            + "}else if (y >= 0){\n"
            + "v = new Date(y);\n"
            + "}\n}\nreturn (v && (z || o))?" // favour UTC offset over GMT offset
            +     " (Ext.type(z) == 'number' ? v.add(Date.SECOND, -v.getTimezoneOffset() * 60 - z) :" // reset to UTC, then add offset
            +         " v.add(Date.MINUTE, -v.getTimezoneOffset() + (sn == '+'? -1 : 1) * (hr * 60 + mn))) : v;\n" // reset to GMT, then add offset
            + "}";

        Date.parseRegexes[regexNum] = new RegExp("^" + regex + "$", "i");
        eval(code);
    },

    // private
    parseCodes : {
        /*
         * Notes:
         * g = {Number} calculation group (0 or 1. only group 1 contributes to date calculations.)
         * c = {String} calculation method (required for group 1. null for group 0. {0} = currentGroup - position in regex result array)
         * s = {String} regex pattern. all matches are stored in results[], and are accessible by the calculation mapped to 'c'
         */
        d: {
            g:1,
            c:"d = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" // day of month with leading zeroes (01 - 31)
        },
        j: {
            g:1,
            c:"d = parseInt(results[{0}], 10);\n",
            s:"(\\d{1,2})" // day of month without leading zeroes (1 - 31)
        },
        D: function() {
            for (var a = [], i = 0; i < 7; a.push(Date.getShortDayName(i)), ++i); // get localised short day names
            return {
                g:0,
                c:null,
                s:"(?:" + a.join("|") +")"
            }
        },
        l: function() {
            return {
                g:0,
                c:null,
                s:"(?:" + Date.dayNames.join("|") + ")"
            }
        },
        N: {
            g:0,
            c:null,
            s:"[1-7]" // ISO-8601 day number (1 (monday) - 7 (sunday))
        },
        S: {
            g:0,
            c:null,
            s:"(?:st|nd|rd|th)"
        },
        w: {
            g:0,
            c:null,
            s:"[0-6]" // javascript day number (0 (sunday) - 6 (saturday))
        },
        z: {
            g:0,
            c:null,
            s:"(?:\\d{1,3}" // day of the year (0 - 364 (365 in leap years))
        },
        W: {
            g:0,
            c:null,
            s:"(?:\\d{2})" // ISO-8601 week number (with leading zero)
        },
        F: function() {
            return {
                g:1,
                c:"m = parseInt(Date.getMonthNumber(results[{0}]), 10);\n", // get localised month number
                s:"(" + Date.monthNames.join("|") + ")"
            }
        },
        M: function() {
            for (var a = [], i = 0; i < 12; a.push(Date.getShortMonthName(i)), ++i); // get localised short month names
            return Ext.applyIf({
                s:"(" + a.join("|") + ")"
            }, $f("F"));
        },
        m: {
            g:1,
            c:"m = parseInt(results[{0}], 10) - 1;\n",
            s:"(\\d{2})" // month number with leading zeros (01 - 12)
        },
        n: {
            g:1,
            c:"m = parseInt(results[{0}], 10) - 1;\n",
            s:"(\\d{1,2})" // month number without leading zeros (1 - 12)
        },
        t: {
            g:0,
            c:null,
            s:"(?:\\d{2})" // no. of days in the month (28 - 31)
        },
        L: {
            g:0,
            c:null,
            s:"(?:1|0)"
        },
        o: function() {
            return $f("Y");
        },
        Y: {
            g:1,
            c:"y = parseInt(results[{0}], 10);\n",
            s:"(\\d{4})" // 4-digit year
        },
        y: {
            g:1,
            c:"var ty = parseInt(results[{0}], 10);\n"
                + "y = ty > Date.y2kYear ? 1900 + ty : 2000 + ty;\n", // 2-digit year
            s:"(\\d{1,2})"
        },
        a: {
            g:1,
            c:"if (results[{0}] == 'am') {\n"
                + "if (h == 12) { h = 0; }\n"
                + "} else { if (h < 12) { h += 12; }}",
            s:"(am|pm)"
        },
        A: {
            g:1,
            c:"if (results[{0}] == 'AM') {\n"
                + "if (h == 12) { h = 0; }\n"
                + "} else { if (h < 12) { h += 12; }}",
            s:"(AM|PM)"
        },
        g: function() {
            return $f("G");
        },
        G: {
            g:1,
            c:"h = parseInt(results[{0}], 10);\n",
            s:"(\\d{1,2})" // 24-hr format of an hour without leading zeroes (0 - 23)
        },
        h: function() {
            return $f("H");
        },
        H: {
            g:1,
            c:"h = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" //  24-hr format of an hour with leading zeroes (00 - 23)
        },
        i: {
            g:1,
            c:"i = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" // minutes with leading zeros (00 - 59)
        },
        s: {
            g:1,
            c:"s = parseInt(results[{0}], 10);\n",
            s:"(\\d{2})" // seconds with leading zeros (00 - 59)
        },
        u: {
            g:1,
            c:"ms = results[{0}]; ms = parseInt(ms, 10)/Math.pow(10, ms.length - 3);\n",
            s:"(\\d+)" // milliseconds with leading zeroes (arbitrary number of digits allowed) e.g. 001, 100, 999, 999876543210
        },
        O: {
            g:1,
            c:[
                "o = results[{0}];",
                "var sn = o.substring(0,1);", // get + / - sign
                "var hr = o.substring(1,3)*1 + Math.floor(o.substring(3,5) / 60);", // get hours (performs minutes-to-hour conversion also, just in case)
                "var mn = o.substring(3,5) % 60;", // get minutes
                "o = ((-12 <= (hr*60 + mn)/60) && ((hr*60 + mn)/60 <= 14))? (sn + String.leftPad(hr, 2, '0') + String.leftPad(mn, 2, '0')) : null;\n" // -12hrs <= GMT offset <= 14hrs
            ].join("\n"),
            s: "([+\-]\\d{4})" // GMT offset in hrs and mins
        },
        P: {
            g:1,
            c:[
                "o = results[{0}];",
                "var sn = o.substring(0,1);", // get + / - sign
                "var hr = o.substring(1,3)*1 + Math.floor(o.substring(4,6) / 60);", // get hours (performs minutes-to-hour conversion also, just in case)
                "var mn = o.substring(4,6) % 60;", // get minutes
                "o = ((-12 <= (hr*60 + mn)/60) && ((hr*60 + mn)/60 <= 14))? (sn + String.leftPad(hr, 2, '0') + String.leftPad(mn, 2, '0')) : null;\n" // -12hrs <= GMT offset <= 14hrs
            ].join("\n"),
            s: "([+\-]\\d{2}:\\d{2})" // GMT offset in hrs and mins (with colon separator)
        },
        T: {
            g:0,
            c:null,
            s:"[A-Z]{1,4}" // timezone abbrev. may be between 1 - 4 chars
        },
        Z: {
            g:1,
            c:"z = results[{0}] * 1;\n" // -43200 <= UTC offset <= 50400
                  + "z = (-43200 <= z && z <= 50400)? z : null;\n",
            s:"([+\-]?\\d{1,5})" // leading '+' sign is optional for UTC offset
        },
        c: function() {
            var calc = [];
            var arr = [
                $f("Y", 1), // year
                $f("m", 2), // month
                $f("d", 3), // day
                $f("h", 4), // hour
                $f("i", 5), // minute
                $f("s", 6), // second
                {c:"ms = (results[7] || '.0').substring(1); ms = parseInt(ms, 10)/Math.pow(10, ms.length - 3);\n"}, // millisecond decimal fraction (with leading zeroes + arbitrary no. of digits)
                {c:"if(results[9] == 'Z'){\no = 0;\n}else{\n" + $f("P", 9).c + "\n}"} // allow both "Z" (i.e. UTC) and "+08:00" (i.e. GMT) time zone delimiters
            ];
            for (var i = 0, l = arr.length; i < l; ++i) {
                calc.push(arr[i].c);
            }

            return {
                g:1,
                c:calc.join(""),
                s:arr[0].s + "-" + arr[1].s + "-" + arr[2].s + "T" + arr[3].s + ":" + arr[4].s + ":" + arr[5].s
                      + "((\.|,)\\d+)?" // ",998465" or ".998465" millisecond decimal fraction
                      + "(" + $f("P", null).s + "|Z)" // "Z" (UTC) or "GMT+08:00" (GMT offset)
            }
        },
        U: {
            g:1,
            c:"u = parseInt(results[{0}], 10);\n",
            s:"(-?\\d+)" // leading minus sign indicates seconds before UNIX epoch
        }
    }
});

}());

Ext.override(Date, {
    // private
    dateFormat : function(format) {
        if (Date.formatFunctions[format] == null) {
            Date.createNewFormat(format);
        }
        var func = Date.formatFunctions[format];
        return this[func]();
    },

    /**
     * Get the timezone abbreviation of the current date (equivalent to the format specifier 'T').
     *
     * Note: The date string returned by the javascript Date object's toString() method varies
     * between browsers (e.g. FF vs IE) and system region settings (e.g. IE in Asia vs IE in America).
     * For a given date string e.g. "Thu Oct 25 2007 22:55:35 GMT+0800 (Malay Peninsula Standard Time)",
     * getTimezone() first tries to get the timezone abbreviation from between a pair of parentheses
     * (which may or may not be present), failing which it proceeds to get the timezone abbreviation
     * from the GMT offset portion of the date string.
     * @return {String} The abbreviated timezone name (e.g. 'CST', 'PDT', 'EDT', 'MPST' ...).
     */
    getTimezone : function() {
        // the following list shows the differences between date strings from different browsers on a WinXP SP2 machine from an Asian locale:
        //
        // Opera  : "Thu, 25 Oct 2007 22:53:45 GMT+0800" -- shortest (weirdest) date string of the lot
        // Safari : "Thu Oct 25 2007 22:55:35 GMT+0800 (Malay Peninsula Standard Time)" -- value in parentheses always gives the correct timezone (same as FF)
        // FF     : "Thu Oct 25 2007 22:55:35 GMT+0800 (Malay Peninsula Standard Time)" -- value in parentheses always gives the correct timezone
        // IE     : "Thu Oct 25 22:54:35 UTC+0800 2007" -- (Asian system setting) look for 3-4 letter timezone abbrev
        // IE     : "Thu Oct 25 17:06:37 PDT 2007" -- (American system setting) look for 3-4 letter timezone abbrev
        //
        // this crazy regex attempts to guess the correct timezone abbreviation despite these differences.
        // step 1: (?:\((.*)\) -- find timezone in parentheses
        // step 2: ([A-Z]{1,4})(?:[\-+][0-9]{4})?(?: -?\d+)?) -- if nothing was found in step 1, find timezone from timezone offset portion of date string
        // step 3: remove all non uppercase characters found in step 1 and 2
        return this.toString().replace(/^.* (?:\((.*)\)|([A-Z]{1,4})(?:[\-+][0-9]{4})?(?: -?\d+)?)$/, "$1$2").replace(/[^A-Z]/g, "");
    },

    /**
     * Get the offset from GMT of the current date (equivalent to the format specifier 'O').
     * @param {Boolean} colon true to separate the hours and minutes with a colon (defaults to false).
     * @return {String} The 4-character offset string prefixed with + or - (e.g. '-0600').
     */
    getGMTOffset : function(colon) {
        return (this.getTimezoneOffset() > 0 ? "-" : "+")
            + String.leftPad(Math.abs(Math.floor(this.getTimezoneOffset() / 60)), 2, "0")
            + (colon ? ":" : "")
            + String.leftPad(Math.abs(this.getTimezoneOffset() % 60), 2, "0");
    },

    /**
     * Get the numeric day number of the year, adjusted for leap year.
     * @return {Number} 0 to 364 (365 in leap years).
     */
    getDayOfYear : function() {
        var num = 0;
        Date.daysInMonth[1] = this.isLeapYear() ? 29 : 28;
        for (var i = 0; i < this.getMonth(); ++i) {
            num += Date.daysInMonth[i];
        }
        return num + this.getDate() - 1;
    },

    /**
     * Get the numeric ISO-8601 week number of the year.
     * (equivalent to the format specifier 'W', but without a leading zero).
     * @return {Number} 1 to 53
     */
    getWeekOfYear : function() {
        // adapted from http://www.merlyn.demon.co.uk/weekcalc.htm
        var ms1d = 864e5; // milliseconds in a day
        var ms7d = 7 * ms1d; // milliseconds in a week
        var DC3 = Date.UTC(this.getFullYear(), this.getMonth(), this.getDate() + 3) / ms1d; // an Absolute Day Number
        var AWN = Math.floor(DC3 / 7); // an Absolute Week Number
        var Wyr = new Date(AWN * ms7d).getUTCFullYear();
        return AWN - Math.floor(Date.UTC(Wyr, 0, 7) / ms7d) + 1;
    },

    /**
     * Whether or not the current date is in a leap year.
     * @return {Boolean} True if the current date is in a leap year, else false.
     */
    isLeapYear : function() {
        var year = this.getFullYear();
        return !!((year & 3) == 0 && (year % 100 || (year % 400 == 0 && year)));
    },

    /**
     * Get the first day of the current month, adjusted for leap year.  The returned value
     * is the numeric day index within the week (0-6) which can be used in conjunction with
     * the {@link #monthNames} array to retrieve the textual day name.
     * Example:
     *<pre><code>
    var dt = new Date('1/10/2007');
    document.write(Date.dayNames[dt.getFirstDayOfMonth()]); //output: 'Monday'
    </code></pre>
     * @return {Number} The day number (0-6).
     */
    getFirstDayOfMonth : function() {
        var day = (this.getDay() - (this.getDate() - 1)) % 7;
        return (day < 0) ? (day + 7) : day;
    },

    /**
     * Get the last day of the current month, adjusted for leap year.  The returned value
     * is the numeric day index within the week (0-6) which can be used in conjunction with
     * the {@link #monthNames} array to retrieve the textual day name.
     * Example:
     *<pre><code>
    var dt = new Date('1/10/2007');
    document.write(Date.dayNames[dt.getLastDayOfMonth()]); //output: 'Wednesday'
    </code></pre>
     * @return {Number} The day number (0-6).
     */
    getLastDayOfMonth : function() {
        var day = (this.getDay() + (Date.daysInMonth[this.getMonth()] - this.getDate())) % 7;
        return (day < 0) ? (day + 7) : day;
    },


    /**
     * Get the date of the first day of the month in which this date resides.
     * @return {Date}
     */
    getFirstDateOfMonth : function() {
        return new Date(this.getFullYear(), this.getMonth(), 1);
    },

    /**
     * Get the date of the last day of the month in which this date resides.
     * @return {Date}
     */
    getLastDateOfMonth : function() {
        return new Date(this.getFullYear(), this.getMonth(), this.getDaysInMonth());
    },

    /**
     * Get the number of days in the current month, adjusted for leap year.
     * @return {Number} The number of days in the month.
     */
    getDaysInMonth : function() {
        Date.daysInMonth[1] = this.isLeapYear() ? 29 : 28;
        return Date.daysInMonth[this.getMonth()];
    },

    /**
     * Get the English ordinal suffix of the current day (equivalent to the format specifier 'S').
     * @return {String} 'st, 'nd', 'rd' or 'th'.
     */
    getSuffix : function() {
        switch (this.getDate()) {
            case 1:
            case 21:
            case 31:
                return "st";
            case 2:
            case 22:
                return "nd";
            case 3:
            case 23:
                return "rd";
            default:
                return "th";
        }
    },

    /**
     * Creates and returns a new Date instance with the exact same date value as the called instance.
     * Dates are copied and passed by reference, so if a copied date variable is modified later, the original
     * variable will also be changed.  When the intention is to create a new variable that will not
     * modify the original instance, you should create a clone.
     *
     * Example of correctly cloning a date:
     * <pre><code>
    //wrong way:
    var orig = new Date('10/1/2006');
    var copy = orig;
    copy.setDate(5);
    document.write(orig);  //returns 'Thu Oct 05 2006'!

    //correct way:
    var orig = new Date('10/1/2006');
    var copy = orig.clone();
    copy.setDate(5);
    document.write(orig);  //returns 'Thu Oct 01 2006'
    </code></pre>
     * @return {Date} The new Date instance.
     */
    clone : function() {
        return new Date(this.getTime());
    },

    /**
     * Clears any time information from this date.
     @param {Boolean} clone true to create a clone of this date, clear the time and return it (defaults to false).
     @return {Date} this or the clone.
     */
    clearTime : function(clone){
        if(clone){
            return this.clone().clearTime();
        }
        this.setHours(0);
        this.setMinutes(0);
        this.setSeconds(0);
        this.setMilliseconds(0);
        return this;
    },

    /**
     * Provides a convenient method of performing basic date arithmetic.  This method
     * does not modify the Date instance being called - it creates and returns
     * a new Date instance containing the resulting date value.
     *
     * Examples:
     * <pre><code>
    //Basic usage:
    var dt = new Date('10/29/2006').add(Date.DAY, 5);
    document.write(dt); //returns 'Fri Oct 06 2006 00:00:00'

    //Negative values will subtract correctly:
    var dt2 = new Date('10/1/2006').add(Date.DAY, -5);
    document.write(dt2); //returns 'Tue Sep 26 2006 00:00:00'

    //You can even chain several calls together in one line!
    var dt3 = new Date('10/1/2006').add(Date.DAY, 5).add(Date.HOUR, 8).add(Date.MINUTE, -30);
    document.write(dt3); //returns 'Fri Oct 06 2006 07:30:00'
     </code></pre>
     *
     * @param {String} interval   A valid date interval enum value.
     * @param {Number} value      The amount to add to the current date.
     * @return {Date} The new Date instance.
     */
    add : function(interval, value){
        var d = this.clone();
        if (!interval || value === 0) return d;

        switch(interval.toLowerCase()){
            case Date.MILLI:
                d.setMilliseconds(this.getMilliseconds() + value);
                break;
            case Date.SECOND:
                d.setSeconds(this.getSeconds() + value);
                break;
            case Date.MINUTE:
                d.setMinutes(this.getMinutes() + value);
                break;
            case Date.HOUR:
                d.setHours(this.getHours() + value);
                break;
            case Date.DAY:
                d.setDate(this.getDate() + value);
                break;
            case Date.MONTH:
                var day = this.getDate();
                if(day > 28){
                    day = Math.min(day, this.getFirstDateOfMonth().add('mo', value).getLastDateOfMonth().getDate());
                }
                d.setDate(day);
                d.setMonth(this.getMonth() + value);
                break;
            case Date.YEAR:
                d.setFullYear(this.getFullYear() + value);
                break;
        }
        return d;
    },

    /**
     * Checks if this date falls on or between the given start and end dates.
     * @param {Date} start Start date
     * @param {Date} end End date
     * @return {Boolean} true if this date falls on or between the given start and end dates.
     */
    between : function(start, end){
        var t = this.getTime();
        return start.getTime() <= t && t <= end.getTime();
    }
});


/**
 * Formats a date given the supplied format string.
 * @param {String} format The format string.
 * @return {String} The formatted date.
 * @method format
 */
Date.prototype.format = Date.prototype.dateFormat;


// private
// safari setMonth is broken
if(Ext.isSafari){
    Date.brokenSetMonth = Date.prototype.setMonth;
    Date.prototype.setMonth = function(num){
        if(num <= -1){
            var n = Math.ceil(-num);
            var back_year = Math.ceil(n/12);
            var month = (n % 12) ? 12 - n % 12 : 0 ;
            this.setFullYear(this.getFullYear() - back_year);
            return Date.brokenSetMonth.call(this, month);
        } else {
            return Date.brokenSetMonth.apply(this, arguments);
        }
    };
}