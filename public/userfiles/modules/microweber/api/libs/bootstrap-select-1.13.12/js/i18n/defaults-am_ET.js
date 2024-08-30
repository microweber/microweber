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
    noneSelectedText: 'ምንም አልተመረጠም',
    noneResultsText: 'ከ{0} ጋር ተመሳሳይ ውጤት የለም',
    countSelectedText: function (numSelected, numTotal) {
      return (numSelected == 1) ? '{0} ምርጫ ተመርጧል' : '{0} ምርጫዎች ተመርጠዋል';
    },
    maxOptionsText: function (numAll, numGroup) {
      return [
        (numAll == 1) ? 'ገደብ ላይ ተደርሷል  (ቢበዛ {n} ምርጫ)' : 'ገደብ ላይ ተደርሷል  (ቢበዛ {n} ምርጫዎች)',
        (numGroup == 1) ? 'የቡድን ገደብ ላይ ተደርሷል (ቢበዛ {n} ምርጫ)' : 'የቡድን ገደብ ላይ ተደርሷል (ቢበዛ {n} ምርጫዎች)'
      ];
    },
    selectAllText: 'ሁሉም ይመረጥ',
    deselectAllText: 'ሁሉም አይመረጥ',
    multipleSeparator: ' ፣ '
  };
})(jQuery);


}));
//# disabled_sourceMappingURL=defaults-am_ET.js.map