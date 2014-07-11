PROJ := 'QueryPath'
SRCDIR := src
TESTS := test/Tests
VERSION := 'DEV'
DATE := `date "+%Y%m%d"`

VFILES = src/HPCloud

docs :
	@cat ./config.doxy | sed 's/-UNSTABLE%/$(VERSION)/' | doxygen -

test :
	phpunit --verbose --color --exclude-group=deprecated $(TESTS);

test-group :
	phpunit --verbose --color --group=$(GROUP) $(TESTS);

fulltest:
	phpunit --color $(TESTS)

lint : $(SRCDIR)
	find $(SRCDIR) -iname *.php -exec php -l {} ';'

dist: tar

tar: 
	@echo $(PROJ)-$(VERSION)-$(DATE).tgz
	# @tar -zcvf $(PROJ)-$(VERSION)-$(DATE).tgz $(SRCDIR)

.PHONY: docs test dist tar lint
