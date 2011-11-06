jQuery.fn.handleKeyboardChange = function(nDelay)
{
    // Utility function to test if a keyboard event should be ignored
    function shouldIgnore(event) 
    { 
        var mapIgnoredKeys = {
                 9:true, // Tab
                16:true, 17:true, 18:true, // Shift, Alt, Ctrl
                37:true, 38:true, 39:true, 40:true, // Arrows 
                91:true, 92:true, 93:true // Windows keys
        };
        return mapIgnoredKeys[event.which];
    }

    // Utility function to fire OUR change event if the value was actually changed
    function fireChange($element)
    {
        if( $element.val() != jQuery.data($element[0], "valueLast") )
        {
                jQuery.data($element[0], "valueLast", $element.val())
                $element.trigger("change");
        }
    }

    // The currently running timeout,
    // will be accessed with closures
    var timeout = 0;

    // Utility function to cancel a previously set timeout
    function clearPreviousTimeout()
    {
        if( timeout )
        { 
                clearTimeout(timeout);
        }
    }

    return this
    .keydown(function(event)
    {
        if( shouldIgnore(event) ) return;
        // User pressed a key, stop the timeout for now
        clearPreviousTimeout();
        return null; 
    })
    .keyup(function(event)
    {
        if( shouldIgnore(event) ) return;
        // Start a timeout to fire our event after some time of inactivity
        // Eventually cancel a previously running timeout
        clearPreviousTimeout();
        var $self = $(this);
        timeout = setTimeout(function(){ fireChange($self) }, nDelay);
    })
    .change(function()
    {
        // Fire a change
        // Use our function instead of just firing the event
        // Because we want to check if value really changed since
        // our previous event.
        // This is for when the browser fires the change event
        // though we already fired the event because of the timeout
        fireChange($(this));
    })
    ;
}