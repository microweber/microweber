/*
 * Bootstrap Datetimepicker → native `<input type="datetime-local">`
 * adapter.
 *
 * Replaces the jQuery Bootstrap Datetimepicker plugin (used by
 * Modules/CustomFields *time.blade.php skins) with a vanilla
 * adapter that swaps `<input type="text">` for HTML5
 * `<input type="datetime-local">` (or `time` / `date` depending
 * on options). Browsers ship the native picker — no jQuery
 * library required.
 *
 * Module skins call:
 *   $('.js-bootstrap4-timepicker').datetimepicker({pickDate:false,
 *     minuteStep:15, format:'HH:ii p', ...});
 *
 * Translation:
 *   - pickDate: false → input type="time"
 *   - pickTime: false → input type="date"
 *   - default → input type="datetime-local"
 *   - minuteStep → step (in seconds)
 *   - other options are ignored (no native equivalent needed —
 *     the browser picker handles UX)
 */

(function () {
    if (typeof window === 'undefined') return;
    if (window.__mwNativeDatetimepickerAdapterLoaded) return;
    window.__mwNativeDatetimepickerAdapterLoaded = true;

    function registerAdapter() {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn) {
            return false;
        }
        var $ = window.jQuery;
        if ($.fn.__mwNativeDatetimepickerInstalled) return true;
        $.fn.__mwNativeDatetimepickerInstalled = true;

        $.fn.datetimepicker = function (options) {
            // Imperative API — no-op for native picker.
            if (typeof options === 'string') {
                return this;
            }
            var opts = options || {};
            return this.each(function () {
                if (this.__mwNativeDatetimepickerApplied) return;
                this.__mwNativeDatetimepickerApplied = true;

                if (this.tagName !== 'INPUT') return;
                var inputType = 'datetime-local';
                if (opts.pickDate === false) inputType = 'time';
                else if (opts.pickTime === false) inputType = 'date';

                this.type = inputType;
                if (opts.minuteStep) {
                    // step in seconds for time/datetime-local
                    this.step = String(parseInt(opts.minuteStep, 10) * 60);
                }
                if (opts.startDate) {
                    this.min = opts.startDate;
                }
                if (opts.endDate) {
                    this.max = opts.endDate;
                }
            });
        };

        return true;
    }

    if (!registerAdapter()) {
        window.__mwRegisterJqueryExtensions = window.__mwRegisterJqueryExtensions || [];
        window.__mwRegisterJqueryExtensions.push(registerAdapter);
    }
})();
