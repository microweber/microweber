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
    noneSelectedText: 'Vyberte zo zoznamu',
    noneResultsText: 'Pre výraz {0} neboli nájdené žiadne výsledky',
    countSelectedText: 'Vybrané {0} z {1}',
    maxOptionsText: ['Limit prekročený ({n} {var} max)', 'Limit skupiny prekročený ({n} {var} max)', ['položiek', 'položka']],
    selectAllText: 'Vybrať všetky',
    deselectAllText: 'Zrušiť výber',
    multipleSeparator: ', '
  };
})(jQuery);


}));
//# disabled_sourceMappingURL=defaults-sk_SK.js.map