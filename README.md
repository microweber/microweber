# Microweber
---
Now runs on Laravel 5!


## Install from CLI
### Usage:
microweber:install [-p|--prefix[="..."]] [-t|--template[="..."]] [-d|--default-content[="..."]] email username password db_host db_name db_user [db_pass]

### Arguments:
* email                  Admin account email
* username               Admin account username
* password               Admin account password
* db_host                Database host address
* db_name                Database schema name
* db_user                Database username
* db_pass                Database password

### Options:
* --prefix (-p)          Database tables prefix
* --template (-t)        Set default template name
* --default-content (-d) Install default content
### Laravel Options:
* --help (-h)            Display this help message.
* --quiet (-q)           Do not output any message.
* --verbose (-v|vv|vvv)  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug.
* --no-interaction (-n)  Do not ask any interactive question.
* --env                  The environment the command should run under.
