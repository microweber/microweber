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
    noneSelectedText: 'Нічого не вибрано',
    noneResultsText: 'Збігів не знайдено {0}',
    countSelectedText: 'Вибрано {0} із {1}',
    maxOptionsText: ['Досягнута межа ({n} {var} максимум)', 'Досягнута межа в групі ({n} {var} максимум)', ['items', 'item']],
    multipleSeparator: ', ',
    selectAllText: 'Вибрати все',
    deselectAllText: 'Скасувати вибір усі'
  };
})(jQuery);


}));
//# disabled_sourceMappingURL=defaults-ua_UA.js.map