SWFUpload jQuery Plugin v1.0.0
-------------------------------
Copyright (c) 2009 Adam Royle. Licensed under the MIT license.
 

Overview
--------

A jQuery plugin that makes working with SWFUpload even easier.

Features include:
	- jQuery-style instantiation
	- jQuery-style event handling
	- Ability to separate SWFUpload object from UI code
	

Usage
-----

// create the swfupload instance (settings must be an object)
$('.swfupload-control').swfupload(settings);

// add some additional handlers (for any plugins if used)
$.swfupload.additionalHandlers('some_extra_handler', 'another_extra_handler');

// same as above but as an array
$.swfupload.additionalHandlers(['some_extra_handler', 'another_extra_handler']);

// or just return an array of the additional handler names
$.swfupload.additionalHandlers();

// or return an array of the default handler names
$.swfupload.defaultHandlers();

// bind the swfupload event handlers like an other jquery event
$('.swfupload-control')
	.bind('swfuploadLoaded', function(event){
		console.debug('swfuploadLoaded!!', event);
	})
	.bind('fileQueued', function(event, file){
		$(this).swfupload('startUpload');
		console.debug('fileQueued!!', event);
	});


// call methods on the swfupload instances by passing a string as the first parameter
// this method is chainable and therefore does not return any values
$('.swfupload-control').swfupload('startUpload', fileID);
$('.swfupload-control').swfupload('cancelUpload', fileID, triggerErrorEvent);

// or if you prefer, you can just get the instance directly
// if you need any return values for methods
$.swfupload.getInstance('.swfupload-control');



Event Names (and, their, params)
--------------------------------
swfuploadLoaded (event)
fileQueued (event, file)
fileQueueError (event, file, errorCode, message)
fileDialogStart (event)
fileDialogComplete (event, numFilesSelected, numFilesQueued)
uploadStart (event, file)
uploadProgress (event, file, bytesLoaded)
uploadSuccess (event, file, serverData)
uploadComplete (event, file)
uploadError (event, file, errorCode, message)
// event from swfupload.queue.js
queueComplete (event, uploadCount)