/**
* This simply adds some extras to Luminous elements via a jQUery
* plugin. The extras are currently a toggleable line-highlighting
* on click
*/

(function($) {
    "use strict";
    
    var LINE_SELECTOR = 'td .code > span ';
    
    if (typeof $ === 'undefined') { return; }
    
    /****************************************************************
     * UTILITY FUNCTIONS *
     ****************************************************************/
    
    // determines if the given element is a line element of luminous
    function isLine($line) {
        return $line.is(LINE_SELECTOR) && $line.parents('.luminous').length > 0;
    }
    
    function isLineNumber($element) {
        return $element.is('.luminous .line-numbers span');
    }
    
    function highlightLine($line) {
        $line.toggleClass('highlight');
    }
    
    function highlightLineByIndex($luminous, index) {
        var $line = $luminous.find(LINE_SELECTOR).eq(index);
        highlightLine($line);
    }
    
    function highlightLineByNumber($luminous, number) {
        // the line's index must take into account the initial line number
        var offset = parseInt($luminous.find('.code').data('startline'), 10);
        if (isNaN(offset)) offset = 0;
        highlightLineByIndex($luminous, number - offset);
    }
    
    function toggleHighlightAndPlain($luminous, forceState) {
        var data = $luminous.data('luminous'),
            state = data.code.active,
            $elem = $luminous.find('.code'),
            toSetCode, toSetState;
        
        if (forceState === 'plain') state = 'highlighted';
        else if (forceState === 'highlighted') state = 'plain';
        
        toSetCode = (state === 'plain')? data.code.highlighted : data.code.plain;
        toSetState = (state === 'plain')? 'highlighted' : 'plain';
        
        $elem.html(toSetCode);
    }
    
    
    function toggleLineNumbers($luminous, forceState) {
        var data = $luminous.data('luminous'),
            show = (typeof forceState !== 'undefined')? forceState : 
                !data.lineNumbers.visible;
        
        data.lineNumbers.visible = show;
        
        
        var $numberContainer = $luminous.find('.line-numbers'),
            $control = $luminous.find('.line-number-control');
        
        if (!show) {
            $numberContainer.addClass('collapsed');
            $control.addClass('show-line-numbers');
            $luminous.addClass('collapsed-line-numbers');
        } else {
            $numberContainer.removeClass('collapsed');
            $control.removeClass('show-line-numbers');
        }
        $luminous.data('luminous', data);
        
    }
    
    // binds the event handlers to a luminous element
    function bindLuminousExtras($element) {
        var highlightLinesData, highlightLines, data = {},
            hasLineNumbers = $element.find('td .line-numbers').length > 0,
            schedule = [];

        if (!$element.is('.luminous')) { return false; }
        else if ($element.is('.bound')) { return true; }
        
        $element.addClass('bound');
        
        // highlight lines on click
        $element.find('td .code').click(function(ev) {
            var $t = $(ev.target);
            var $lines = $t.parents().add($t).
                    filter(function() { return isLine($(this)); }),
                 $line
                 ;

            if ($lines.length > 0) {
                $line = $lines.eq(0);
                highlightLine($line);
            }
        });
        // highlight lines on clicking the line number        
        $element.find('td .line-numbers').click(function(ev) {
            var $t = $(ev.target),
                 index;
            if ($t.is('span')) {
                index = $t.prevAll().length;
                highlightLineByIndex($element, index);
            }
        });
        
        data.lineNumbers = {visible: false};
        
        if (hasLineNumbers) {
            /** 
              * Line numbering is semi complicated because we can make it better
              * with javascript!
              * TODO: probably refactor this into a sub-function
              */
            
            // the control is a show/hide line numbers, we can fade it
            // in/out when the user hovers over the line numbers.
            // We can also fix the line numbers so they move left 
            // as the widget is hoz-scrolled.
            var $control, controlHeight, controlWidth, gutterWidth, 
              controlIsVisible = false,
              $lineNumbers = $element.find('pre.line-numbers'),
              defaultLineNumberWidth = $lineNumbers.outerWidth(),
              mouseY = 0,
              controlCalculateLeftCss = function() {
                  var visible = $element.data('luminous').lineNumbers.visible,
                      base = visible? gutterWidth - controlWidth : 0,
                      total = 0;
                  total = $element.scrollLeft() + base;
                  return total + 'px';
                  
              };
            
            data.lineNumbers.visible = true;
            data.lineNumbers.setControlPosition = function() {
                $control.css('top', Math.max(0, mouseY - (controlHeight/2)) + 'px');
            }
            
            $control = $('<a class="line-number-control"></a>');
            $control.click(function() {
                $element.luminous('showLineNumbers');
                $control.css('left', controlCalculateLeftCss());
                if (!$element.data('luminous').lineNumbers.visible) {
                    $element.find('pre.code').css('padding-left', '');
                } else {
                    $element.find('pre.code').css('padding-left', defaultLineNumberWidth + 'px');
                }

            });
            
            $control.appendTo($element);
            $control.show();
            controlWidth = $control.outerWidth();
            controlHeight = $control.outerHeight();
            gutterWidth = $element.find('.line-numbers').outerWidth();
            $control.css('left', gutterWidth - controlWidth + 'px');
            $control.hide();
            $element.mousemove(function(ev) {
                var scrollLeft = $element.scrollLeft();
                mouseY = ev.pageY - $(this).offset().top;
                if (ev.pageX < gutterWidth) {
                    if (!controlIsVisible) { 
                        data.lineNumbers.setControlPosition();
                        $control.stop(true, true).fadeIn('fast');
                        controlIsVisible = true;
                    }
                } else {
                    if (controlIsVisible) { 
                        $control.stop(true, true).fadeOut('fast'); 
                        controlIsVisible = false;
                    } 
                }
            });
                       
            data.lineNumbers.setControlPosition();
            $element.find('pre.code').css('padding-left', $lineNumbers.outerWidth() + 'px');
            $lineNumbers.css({
              position: 'absolute',
              top: 0,
              left: 0
            });
            $element.scroll(function() {
                data.lineNumbers.setControlPosition();
                $control.css('left', controlCalculateLeftCss());
                $lineNumbers.css('left', $element.scrollLeft() + 'px');
            });
            schedule.push(function() { $element.luminous('showLineNumbers', true); });
            $element.find('.line-numbers').parent().css({width: 0, maxWidth: 0});
            
        }
        
        // highlight all the initial lines
        highlightLinesData = $element.find('.code').data('highlightlines') || "";
        highlightLines = highlightLinesData.split(",");
        $.each(highlightLines, function(i, element) {
             var lineNo = parseInt(element, 10);
             if (!isNaN(lineNo)) {
                 highlightLineByNumber($element, lineNo);
            }
        });

        data.code = {};
        data.code.highlighted = $element.find('.code').html();
        
        data.code.plain = '';
        $element.find(LINE_SELECTOR).each(function(i, e) {
            var line = $(e).text();
            line = line
                    .replace(/&/g, '&amp')
                    .replace(/>/g, '&gt;')
                    .replace(/</g, '&lt;');
        
            data.code.plain += '<span>' + line + '</span>';
        });
        data.code.active = 'highlighted';
        
        $element.data('luminous', data);
        
        $.each(schedule, function(i, f) {
            f();
        });
        
    }
    
    
    
    /****************************************************************
     * JQUERY PLUGIN *
     ***************************************************************/


    $.fn.luminous = function(optionsOrCommand /* variadic */) {
    
        var args = Array.prototype.slice.call(arguments);
        
        return $(this).each(function() {
            var $luminous = $(this);
            
            // no instructions - bind everything 
            if (!optionsOrCommand) {
                bindLuminousExtras($luminous);
                return;
            }
            
            // $('.luminous').luminous('highlightLine', [2, 3]);
            if (optionsOrCommand === 'highlightLine') {
                var lineNumbers = args[1];
                if (!$.isArray(lineNumbers)) 
                    lineNumbers = [lineNumbers];
                
                $.each(lineNumbers, function(index, el) {
                    highlightLineByNumber($luminous, el);
                });
                
                return;
            }
            else if (optionsOrCommand === 'show') {
                // args[1] should be 'highlighted' or 'plain'
                toggleHighlightAndPlain($luminous, args[1]);
            }
            else if (optionsOrCommand === 'showLineNumbers') {
                toggleLineNumbers($luminous, args[1]);
            }
            
        });
    };

    $(document).ready(function() {
        $('.luminous').luminous();
    });
  
}(jQuery));