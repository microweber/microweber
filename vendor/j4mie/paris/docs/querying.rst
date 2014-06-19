Querying
========

Querying allows you to select data from your database and populate
instances of your model classes. Queries start with a call to a static
*factory method* on the base ``Model`` class that takes a single
argument: the name of the model class you wish to use for your query.
This factory method is then used as the start of a *method chain* which
gives you full access to `Idiorm`_\ ’s fluent query API. **See Idiorm’s
documentation for details of this API.**

For example:

.. code-block:: php

    <?php
    $users = Model::factory('User')
        ->where('name', 'Fred')
        ->where_gte('age', 20)
        ->find_many();

You can also use the same shortcut provided by Idiorm when looking up a
record by its primary key ID:

.. code-block:: php

    <?php
    $user = Model::factory('User')->find_one($id);

If you are using PHP 5.3+ you can also do the following: 

.. code-block:: php

    <?php
    $users = User::where('name', 'Fred')
        ->where_gte('age', 20)
        ->find_many();
        
This does the same as the example above but is shorter and more readable.


The only differences between using Idiorm and using Paris for querying
are as follows:

1. You do not need to call the ``for_table`` method to specify the
   database table to use. Paris will supply this automatically based on
   the class name (or the ``$_table`` static property, if present).

2. The ``find_one`` and ``find_many`` methods will return instances of
   *your model subclass*, instead of the base ``ORM`` class. Like
   Idiorm, ``find_one`` will return a single instance or ``false`` if no
   rows matched your query, while ``find_many`` will return an array of
   instances, which may be empty if no rows matched.

3. Custom filtering, see next section.

You may also retrieve a count of the number of rows returned by your
query. This method behaves exactly like Idiorm’s ``count`` method:

.. code-block:: php

    <?php
    $count = Model::factory('User')->where_lt('age', 20)->count();

A note on PSR-1 and camelCase
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

All the methods detailed in the documentation can also be called in a PSR-1 way:
underscores (_) become camelCase. Here follows an example of one query chain
being converted to a PSR-1 compliant style.

.. code-block:: php

    <?php
    // documented and default style
    $count = Model::factory('User')->where_lt('age', 20)->find_one();

    // PSR-1 compliant style
    $count = Model::factory('User')->whereLt('age', 20)->findOne();

As you can see any method can be changed from the documented underscore (_) format
to that of a camelCase method name.

.. note::

    In the background the PSR-1 compliant style uses the `__call()` and 
    `__callStatic()` magic methods to map the camelCase method name you supply
    to the original underscore method name. It then uses `call_user_func_array()`
    to apply the arguments to the method. If this minimal overhead is too great
    then you can simply revert to using the underscore methods to avoid it. In
    general this will not be a bottle neck in any application however and should
    be considered a micro-optimisation.

    As `__callStatic()` was added in PHP 5.3.0 you will need at least that version
    of PHP to use this feature in any meaningful way.

Getting data from objects, updating and inserting data
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The model instances returned by your queries now behave exactly as if
they were instances of Idiorm’s raw ``ORM`` class.

You can access data:

.. code-block:: php

    <?php
    $user = Model::factory('User')->find_one($id);
    echo $user->name;

Update data and save the instance:

.. code-block:: php

    <?php
    $user = Model::factory('User')->find_one($id);
    $user->name = 'Paris';
    $user->save();

To create a new (empty) instance, use the ``create`` method:

.. code-block:: php

    <?php
    $user = Model::factory('User')->create();
    $user->name = 'Paris';
    $user->save();

To check whether a property has been changed since the object was
created (or last saved), call the ``is_dirty`` method:

.. code-block:: php

    <?php
    $name_has_changed = $person->is_dirty('name'); // Returns true or false

You can also use database expressions when setting values on your model:

.. code-block:: php

    <?php
    $user = Model::factory('User')->find_one($id);
    $user->name = 'Paris';
    $user->set_expr('last_logged_in', 'NOW()');
    $user->save();

Of course, because these objects are instances of your base model
classes, you can also call methods that you have defined on them:

.. code-block:: php

    <?php
    class User extends Model {
        public function full_name() {
            return $this->first_name . ' ' . $this->last_name;
        }
    }

    $user = Model::factory('User')->find_one($id);
    echo $user->full_name();

To delete the database row associated with an instance of your model,
call its ``delete`` method:

.. code-block:: php

    <?php
    $user = Model::factory('User')->find_one($id);
    $user->delete();

You can also get the all the data wrapped by a model subclass instance
using the ``as_array`` method. This will return an associative array
mapping column names (keys) to their values.

The ``as_array`` method takes column names as optional arguments. If one
or more of these arguments is supplied, only matching column names will
be returned.

.. code-block:: php

    <?php
    class Person extends Model {
    }

    $person = Model::factory('Person')->create();

    $person->first_name = 'Fred';
    $person->surname = 'Bloggs';
    $person->age = 50;

    // Returns array('first_name' => 'Fred', 'surname' => 'Bloggs', 'age' => 50)
    $data = $person->as_array();

    // Returns array('first_name' => 'Fred', 'age' => 50)
    $data = $person->as_array('first_name', 'age');

.. _Idiorm: http://github.com/j4mie/idiorm/
