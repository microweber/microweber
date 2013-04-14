**Directory structure**    
    /application - the MW system directory where the system files are located.
    /application/functions - the main functions of MW
    /userfiles/modules - the directory where the modules are installed
    /userfiles/templates - the directory for the templates
    
**Getting data**

All the data you get with those functions is returned as array

The get() function is the core function that you shall use to query the databases.


You can use it by passing parameters via array or string. The "from" parameter is the name of the dabase table you want to query, and the other parameters are used to filter and sort the results.

 ```php
 //example
get("from=content&id=1");
```