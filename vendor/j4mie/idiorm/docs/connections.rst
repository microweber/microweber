Mulitple Connections
====================
Idiorm now works with multiple conections. Most of the static functions
work with an optional connection name as an extra parameter. For the
``ORM::configure`` method, this means that when passing connection
strings for a new connection, the second parameter, which is typically
omitted, should be ``null``. In all cases, if a connection name is not
provided, it defaults to ``ORM::DEFAULT_CONNECTION``.

When chaining, once ``for_table()`` has been used in the chain, remaining
calls in the chain use the correct connection.

.. code-block:: php

    <?php
    // Default connection
    ORM::configure('sqlite:./example.db');

    // A named connection, where 'remote' is an arbitrary key name
    ORM::configure('mysql:host=localhost;dbname=my_database', null, 'remote');
    ORM::configure('username', 'database_user', 'remote');
    ORM::configure('password', 'top_secret', 'remote');
    
    // Using default connection
    $person = ORM::for_table('person')->find_one(5);
    
    // Using default connection, explicitly
    $person = ORM::for_table('person', ORM::DEFAULT_CONNECTION)->find_one(5);
    
    // Using named connection
    $person = ORM::for_table('different_person', 'remote')->find_one(5);
    
    

Supported Methods
^^^^^^^^^^^^^^^^^
In each of these cases, the ``$connection_name`` parameter is optional, and is
an arbitrary key identifying the named connection.

* ``ORM::configure($key, $value, $connection_name)``
* ``ORM::for_table($table_name, $connection_name)``
* ``ORM::set_db($pdo, $connection_name)``
* ``ORM::get_db($connection_name)``
* ``ORM::raw_execute($query, $parameters, $connection_name)``
* ``ORM::get_last_query($connection_name)``
* ``ORM::get_query_log($connection_name)``

Of these methods, only ``ORM::get_last_query($connection_name)`` does *not*
fallback to the default connection when no connection name is passed.
Instead, passing no connection name (or ``null``) returns the most recent
query on *any* connection.

.. code-block:: php

    <?php
    // Using default connection, explicitly
    $person = ORM::for_table('person')->find_one(5);
    
    // Using named connection
    $person = ORM::for_table('different_person', 'remote')->find_one(5);

    // Last query on *any* connection
    ORM::get_last_query(); // returns query on 'different_person' using 'remote'
    
    // returns query on 'person' using default by passing in the connection name
    ORM::get_last_query(ORM::DEFAULT_CONNECTION);

Notes
~~~~~
* **There is no support for joins across connections**
* Multiple connections do not share configuration settings. This means if
  one connection has logging set to ``true`` and the other does not, only
  queries from the logged connection will be available via
  ``ORM::get_last_query()`` and ``ORM::get_query_log()``.
* A new method has been added, ``ORM::get_connection_names()``, which returns
  an array of connection names.
* Caching *should* work with multiple connections (remember to turn caching
  on for each connection), but the unit tests are not robust. Please report
  any errors.