// Bind to Aloha Ready Event
Aloha.ready( function() {
	var $ = jQuery = Aloha.jQuery;
	$('.edit').aloha();

var button = jQuery('#bold');
 
button.attr( 'disabled',
    ( Aloha.queryCommandSupported( 'bold' ) &&
    Aloha.queryCommandEnabled( 'bold' ) )
);
 
button.click( function() {
    Aloha.execCommand( 'bold', false, '' );
    updateBoldColor();
});
 
Aloha.bind('aloha-selection-changed aloha-command-executed', function() {
	updateBoldColor();
});

function updateBoldColor() {
	if ( Aloha.queryCommandIndeterm( 'bold' ) ) {
		button.css( 'background-color', 'yellow' );
		return;
	}
    button.css( 'background-color', 
            Aloha.queryCommandState( 'bold' ) ? 'lightgreen' : 'orange'
    );
}
// update the color on startup
updateBoldColor();

var 
range = Aloha.createRange(),
begin = jQuery( 'p' ),
end = jQuery( 'i' );

//setStart and setEnd take dom node and the offset as parameters
range.setStart( begin.get(0), 0);
range.setEnd( end.get(0), 1);

//add the range to the selection
Aloha.getSelection().removeAllRanges();
Aloha.getSelection().addRange( range );

Aloha.execCommand( 'bold', false, '' );
});

