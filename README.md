# Microweber
---
Now runs on Laravel 5!


## Install from CLI
You can install Microweber directly from the command line interface. This may be useful in shell scripts that automate the site creation process. Here's an example of what the command looks like:

`php artisan microweber:install admin@site.com admin pass 127.0.0.1 site_db root secret -p site_`

This would initialize the Microweber database on localhost in database "site_db" using user "root" with password "secret" for the connections. In case the database user doesn't have a password you can skip setting that argument (and also be ashamed of your attitude to security). All tables will be prefixed by "site_". After the schema initialization an admin user will be created with credentials "admin"/"pass" and email "admin@site.com".
All arguments until the database password are required and need to be present in that exact order.

#### Usage:
`microweber:install [-p|--prefix[="..."]] [-t|--template[="..."]] [-d|--default-content[="..."]] email username password db_host db_name db_user [db_pass]`

#### Arguments:
Argument  | Description
      --- | ---
email     | Admin account email
username  | Admin account username
password  | Admin account password
db_host   | Database host address
db_name   | Database schema name
db_user   | Database username
db_pass   | Database password (optional)

#### Options:
               Option  | Description
                   --- | ---
--prefix (-p)          | Database tables prefix
--template (-t)        | Set default template name
--default-content (-d) | Install default content

#### Laravel Options:
      Option  | Description
          --- | ---
--help (-h)   | Display this help message.
--quiet (-q)  | Do not output any message.
--env         | The environment the command should run under.
