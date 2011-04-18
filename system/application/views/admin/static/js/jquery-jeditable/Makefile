VERSION = 1.7.1
SHELL = /bin/sh
DOWNLOAD = /var/www/www.appelsiini.net/htdocs/download
JSPACKER = /home/tuupola/bin/jspacker
JSMIN    = /home/tuupola/bin/jsmin

#all: jeditable packed minified latest wysiwyg
all: jeditable minified latest wysiwyg

jeditable: jquery.jeditable.js
	cp jquery.jeditable.js $(DOWNLOAD)/jquery.jeditable-$(VERSION).js

packed: jquery.jeditable.js
	$(JSPACKER) < jquery.jeditable.js > jquery.jeditable.pack.js 
	cp jquery.jeditable.pack.js $(DOWNLOAD)/jquery.jeditable-$(VERSION).pack.js

minified: jquery.jeditable.js
	$(JSMIN) < jquery.jeditable.js > jquery.jeditable.mini.js 
	cp jquery.jeditable.mini.js $(DOWNLOAD)/jquery.jeditable-$(VERSION).mini.js

latest: jquery.jeditable.js jquery.jeditable.pack.js 
	cp jquery.jeditable.js $(DOWNLOAD)/jquery.jeditable.js
	cp jquery.jeditable.ajaxupload.js $(DOWNLOAD)/jquery.jeditable.ajaxupload.js
	cp jquery.jeditable.autogrow.js $(DOWNLOAD)/jquery.jeditable.autogrow.js
#	cp jquery.jeditable.datepicker.js $(DOWNLOAD)/jquery.jeditable.datepicker.js
	cp jquery.jeditable.masked.js $(DOWNLOAD)/jquery.jeditable.masked.js
#	cp jquery.jeditable.tageditor.js $(DOWNLOAD)/jquery.jeditable.tageditor.js
	cp jquery.jeditable.time.js $(DOWNLOAD)/jquery.jeditable.time.js
	cp jquery.jeditable.timepicker.js $(DOWNLOAD)/jquery.jeditable.timepicker.js
	cp jquery.jeditable.charcounter.js $(DOWNLOAD)/jquery.jeditable.charcounter.js
#	cp jquery.jeditable.pack.js $(DOWNLOAD)/jquery.jeditable.pack.js
	cp jquery.jeditable.mini.js $(DOWNLOAD)/jquery.jeditable.mini.js


.PHONY: wysiwyg
wysiwyg:
	 cp wysiwyg/jquery.jeditable.wysiwyg.js  $(DOWNLOAD)/jquery.jeditable.wysiwyg.js

tests: jquery.jeditable.js
	rm examples/lib/jquery.jeditable.js
	cp jquery.jeditable.js examples/lib/

tarball: examples/index.html
	rm examples/lib/jquery.jeditable.js
	cp jquery.jeditable.js examples/lib/
	/usr/local/bin/tar -X ignore.txt -czvf jEditable_examples-$(VERSION).tar.gz examples/*
	cp jEditable_examples-$(VERSION).tar.gz $(DOWNLOAD)
