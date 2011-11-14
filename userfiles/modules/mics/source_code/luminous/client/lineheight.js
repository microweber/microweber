/**
 * Debug script to check the line numbers and lines are in synch and identify
 * which lines are out, if they are not.
 *
 * Earlier in development making sure that lines and line heights were synched
 * was a big problem due to some fonts changing height when they were rendered
 * as italics. This still seems to pop up intermittently and it's pretty hard
 * to track down, so this script is here to hopefully illuminate the situation
 * should it re-appear.
 */
$(document).ready(function() {
  var numbers = $('span.line_number'),
      lines = $('span.line');
  var mismatches = [];
  numbers.each(function(i) {
    var number = $(this),
        line = $(lines.get(i));
    var h1 = number.outerHeight(),
        h2 = line.outerHeight();
    if (h2 !== h1) {
      mismatches.push(number.html() + '=' + h1 + ', ' + line.html() + '=' + h2);
    }
  });

  $('body').append($('<h1>Mismatches</h1>'));
  $('body').append($('<div>' + (mismatches.length? mismatches.join('<br>') : 'All fine') + '</div>'));
});
