Multiple Connections
====================

Paris now works with multiple database conections (and necessarily relies on an updated version of Idiorm that also supports multiple connections). Database connections are identified by a string name, and default to ``OrmWrapper::DEFAULT_CONNECTION`` (which is really ``ORM::DEFAULT_CONNECTION``).

See `Idiorm’s documentation`_ for information about configuring multiple connections.

The connection to use can be specified in two separate ways. To indicate a default connection key for a subclass of ``Model``, create a public static property in your model class called ``$_connection_name``.

.. code-block:: php

    <?php
    // A named connection, where 'alternate' is an arbitray key name
    ORM::configure('sqlite:./example2.db', null, 'alternate');

    class SomeClass extends Model
    {
        public static $_connection_name = 'alternate';
    }

The connection to use can also be specified as an optional additional parameter to ``OrmWrapper::for_table()``, or to ``Model::factory()``. This will override the default setting (if any) found in the ``$_connection_name`` static property.

.. code-block:: php

    <?php
    $person = Model::factory('Author', 'alternate')->find_one(1);  // Uses connection named 'alternate'

The connection can be changed after a model is populated, should that be necessary:

.. code-block:: php

    <?php
    $person = Model::factory('Author')->find_one(1);     // Uses default connection
    $person->orm = Model::factory('Author', 'alternate');  // Switches to connection named 'alternate'
    $person->name = 'Foo';
    $person->save();                                     // *Should* now save through the updated connection

Queries across multiple connections are not supported. However, as the Paris methods ``has_one``, ``has_many`` and ``belongs_to`` don't require joins, these *should* work as expected, even when the objects on opposite sides of the relation belong to diffrent connections. The ``has_many_through`` relationship requires joins, and so will not reliably work across different connections.

.. _Idiorm’s documentation: http://github.com/j4mie/idiorm/
