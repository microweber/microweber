/**
 * Functions to resize images after loading for bbcode
 */

const maxsize = 100;

function isDefined(property) {
  return (typeof property != 'undefined');
}

function resizeImage(img) {
	if (img.width>maxsize) {
		img.height = img.width*maxsize/img.height;
		img.width = maxsize;
	}
	if (img.height>maxsize) {
		img.width = img.height*maxsize/img.width;
		img.height = maxsize;
	}
}

function resizeImagePrep(img) {
	// if already done, resize
	if (img.complete) {
		resizeImage(img);
	} else {
		// if not wait for loading
		img.onload = function() {
			resizeImage(this);
		}
	}
}

function resizeImagesInit() {
	var tables = document.getElementsByTagName("td");
	for (var i=0; i<tables.length; ++i) {
		// check if correct class
		if (tables[i].getAttribute("class") != "task-description") continue;
		// check image size
		var desc = tables[i];
		var images = document.getElementsByTagName("img");
		for (var j=0; j<images.length; ++j) {
			resizeImagePrep(images[j]);
		}
	}
}

// call image resize when document finishes loading
if (isDefined(window.addEventListener)) {
   window.addEventListener('load', resizeImagesInit, false);
} else if (isDefined(window.attachEvent)) {
   window.attachEvent('onload', resizeImagesInit);
}
