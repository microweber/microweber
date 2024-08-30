/*!
 * Bootstrap-select v1.13.12 (https://developer.snapappointments.com/bootstrap-select)
 *
 * Copyright 2012-2019 SnapAppointments, LLC
 * Licensed under MIT (https://github.com/snapappointments/bootstrap-select/blob/master/LICENSE)
 */

(function (root, factory) {
  if (root === undefined && window !== undefined) root = window;
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module unless amdModuleId is set
    define(["jquery"], function (a0) {
      return (factory(a0));
    });
  } else if (typeof module === 'object' && module.exports) {
    // Node. Does not work with strict CommonJS, but
    // only CommonJS-like environments that support module.exports,
    // like Node.
    module.exports = factory(require("jquery"));
  } else {
    factory(root["jQuery"]);
  }
}(this, function (jQuery) {

(function ($) {
  $.fn.selectpicker.defaults = {
    noneSelectedText: 'Hautapenik ez',
    noneResultsText: 'Emaitzarik ez {0}',
    countSelectedText: '{1}(e)tik {0} hautatuta',
    maxOptionsText: ['Mugara iritsita ({n} {var} gehienez)', 'Taldearen mugara iritsita ({n} {var} gehienez)', ['elementu', 'elementu']],
    multipleSeparator: ', ',
    selectAllText: 'Hautatu Guztiak',
    deselectAllText: 'Desautatu Guztiak'
  };
})(jQuery);


}));
//# disabled_sourceMappingURL=defaults-eu.js.map