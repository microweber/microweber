/*
 * Copyright 2010 Mark Watkinson
 * 
 * This file is part of Luminous.
 * 
 * Luminous is free software: you can redistribute it and/or
 * modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Luminous is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Luminous.  If not, see <http://www.gnu.org/licenses/>.
 */

function luminous($) {
  // we bind a couple of things:
  // 1) A single click event on the line numbers to highlight a line
  // 2) a double click event on the line numbers to hide the line number bar
  $('.luminous').each(function() {
    $l = $(this);
    if ($l.data('luminoused')) return;
    $l.data('luminoused', true);
    $lines = $('span.line', $l);
    
    var click_timer = null;
    // we bind both a double and single click event. We distinguish between
    // these with a timer
    // this is the single-click highlight line event
    $('.line_number', this).each(function(i) {
      if (i >= $lines.length) return false;
      $(this).click(function() {
        clearTimeout(click_timer);
        click_timer = setTimeout(function() {
          $($lines.get(i)).toggleClass('highlighted_line');
        }, 150); // is 150ms okay for double click?
      });
    });
    // ... and the double-click show/hide line numbers event
    var line_nos_expanded = true;
    $('td.line_number_bar', $l).dblclick(function() {
      clearTimeout(click_timer);
      if (line_nos_expanded) $(this).css({'overflow':'hidden', 
        'max-width':'6px'});
      else $(this).css('max-width', 'none');
      line_nos_expanded = !line_nos_expanded;
    });

  });
}

if (typeof jQuery !== 'undefined') {
  jQuery(document).ready(function() {
    luminous(jQuery);
  });
}

