Filters
=======

It is often desirable to create reusable queries that can be used to
extract particular subsets of data without repeating large sections of
code. Paris allows this by providing a method called ``filter`` which
can be chained in queries alongside the existing Idiorm query API. The
filter method takes the name of a **public static** method on the
current Model subclass as an argument. The supplied method will be
called at the point in the chain where ``filter`` is called, and will be
passed the ``ORM`` object as the first parameter. It should return the
ORM object after calling one or more query methods on it. The method
chain can then be continued if necessary.

It is easiest to illustrate this with an example. Imagine an application
in which users can be assigned a role, which controls their access to
certain pieces of functionality. In this situation, you may often wish
to retrieve a list of users with the role ‘admin’. To do this, add a
static method called (for example) ``admins`` to your Model class:

.. code-block:: php

    <?php
    class User extends Model {
        public static function admins($orm) {
            return $orm->where('role', 'admin');
        }
    }

You can then use this filter in your queries:

.. code-block:: php

    <?php
    $admin_users = Model::factory('User')->filter('admins')->find_many();

You can also chain it with other methods as normal:

.. code-block:: php

    <?php
    $young_admins = Model::factory('User')
                        ->filter('admins')
                        ->where_lt('age', 18)
                        ->find_many();

Filters with arguments
~~~~~~~~~~~~~~~~~~~~~~

You can also pass arguments to custom filters. Any additional arguments
passed to the ``filter`` method (after the name of the filter to apply)
will be passed through to your custom filter as additional arguments
(after the ORM instance).

For example, let’s say you wish to generalise your role filter (see
above) to allow you to retrieve users with any role. You can pass the
role name to the filter as an argument:

.. code-block:: php

    <?php
    class User extends Model {
        public static function has_role($orm, $role) {
            return $orm->where('role', $role);
        }
    }

    $admin_users = Model::factory('User')->filter('has_role', 'admin')->find_many();
    $guest_users = Model::factory('User')->filter('has_role', 'guest')->find_many();

These examples may seem simple (``filter('has_role', 'admin')`` could
just as easily be achieved using ``where('role', 'admin')``), but
remember that filters can contain arbitrarily complex code - adding
``raw_where`` clauses or even complete ``raw_query`` calls to perform
joins, etc. Filters provide a powerful mechanism to hide complexity in
your model’s query API.