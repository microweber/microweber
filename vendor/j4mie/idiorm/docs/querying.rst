Querying
========

Idiorm provides a `*fluent
interface* <http://en.wikipedia.org/wiki/Fluent_interface>`_ to enable
simple queries to be built without writing a single character of SQL. If
you've used `jQuery <http://jquery.com>`_ at all, you'll be familiar
with the concept of a fluent interface. It just means that you can
*chain* method calls together, one after another. This can make your
code more readable, as the method calls strung together in order can
start to look a bit like a sentence.

All Idiorm queries start with a call to the ``for_table`` static method
on the ORM class. This tells the ORM which table to use when making the
query.

*Note that this method **does not** escape its query parameter and so
the table name should **not** be passed directly from user input.*

Method calls which add filters and constraints to your query are then
strung together. Finally, the chain is finished by calling either
``find_one()`` or ``find_many()``, which executes the query and returns
the result.

Let's start with a simple example. Say we have a table called ``person``
which contains the columns ``id`` (the primary key of the record -
Idiorm assumes the primary key column is called ``id`` but this is
configurable, see below), ``name``, ``age`` and ``gender``.

A note on PSR-1 and camelCase
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

All the methods detailed in the documentation can also be called in a PSR-1 way:
underscores (_) become camelCase. Here follows an example of one query chain
being converted to a PSR-1 compliant style.

.. code-block:: php

    <?php
    // documented and default style
    $person = ORM::for_table('person')->where('name', 'Fred Bloggs')->find_one();

    // PSR-1 compliant style
    $person = ORM::forTable('person')->where('name', 'Fred Bloggs')->findOne();

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

Single records
^^^^^^^^^^^^^^

Any method chain that ends in ``find_one()`` will return either a
*single* instance of the ORM class representing the database row you
requested, or ``false`` if no matching record was found.

To find a single record where the ``name`` column has the value "Fred
Bloggs":

.. code-block:: php

    <?php
    $person = ORM::for_table('person')->where('name', 'Fred Bloggs')->find_one();

This roughly translates into the following SQL:
``SELECT * FROM person WHERE name = "Fred Bloggs"``

To find a single record by ID, you can pass the ID directly to the
``find_one`` method:

.. code-block:: php

    <?php
    $person = ORM::for_table('person')->find_one(5);

Multiple records
^^^^^^^^^^^^^^^^

.. note::

   It is recommended that you use results sets over arrays - see `As a result set`
   below.

Any method chain that ends in ``find_many()`` will return an *array* of
ORM class instances, one for each row matched by your query. If no rows
were found, an empty array will be returned.

To find all records in the table:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->find_many();

To find all records where the ``gender`` is ``female``:

.. code-block:: php

    <?php
    $females = ORM::for_table('person')->where('gender', 'female')->find_many();

As a result set
'''''''''''''''

.. note::

   There is a configuration setting ``return_result_sets`` that will cause
   ``find_many()`` to return result sets by default. It is recommended that you
   turn this setting on:

   ::

       ORM::configure('return_result_sets', true);

You can also find many records as a result set instead of an array of Idiorm
instances. This gives you the advantage that you can run batch operations on a
set of results.

So for example instead of running this:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->find_many();
    foreach ($people as $person) {
        $person->age = 50;
        $person->save();
    }

You can simply do this instead:

.. code-block:: php

    <?php
    ORM::for_table('person')->find_result_set()
    ->set('age', 50)
    ->save();

To do this substitute any call to ``find_many()`` with
``find_result_set()``.

A result set will also behave like an array so you can `count()` it and `foreach`
over it just like an array.

.. code-block:: php

    <?php
    foreach(ORM::for_table('person')->find_result_set() as $record) {
        echo $record->name;
    }

.. code-block:: php

    <?php
    echo count(ORM::for_table('person')->find_result_set());

.. note::
   
   For deleting many records it is recommended that you use `delete_many()` as it
   is more efficient than calling `delete()` on a result set.

As an associative array
'''''''''''''''''''''''

You can also find many records as an associative array instead of Idiorm
instances. To do this substitute any call to ``find_many()`` with
``find_array()``.

.. code-block:: php

    <?php
    $females = ORM::for_table('person')->where('gender', 'female')->find_array();

This is useful if you need to serialise the the query output into a
format like JSON and you do not need the ability to update the returned
records.

Counting results
^^^^^^^^^^^^^^^^

To return a count of the number of rows that would be returned by a
query, call the ``count()`` method.

.. code-block:: php

    <?php
    $number_of_people = ORM::for_table('person')->count();

Filtering results
^^^^^^^^^^^^^^^^^

Idiorm provides a family of methods to extract only records which
satisfy some condition or conditions. These methods may be called
multiple times to build up your query, and Idiorm's fluent interface
allows method calls to be *chained* to create readable and
simple-to-understand queries.

*Caveats*
'''''''''

Only a subset of the available conditions supported by SQL are available
when using Idiorm. Additionally, all the ``WHERE`` clauses will be
``AND``\ ed together when the query is run. Support for ``OR``\ ing
``WHERE`` clauses is not currently present.

These limits are deliberate: these are by far the most commonly used
criteria, and by avoiding support for very complex queries, the Idiorm
codebase can remain small and simple.

Some support for more complex conditions and queries is provided by the
``where_raw`` and ``raw_query`` methods (see below). If you find
yourself regularly requiring more functionality than Idiorm can provide,
it may be time to consider using a more full-featured ORM.

Equality: ``where``, ``where_equal``, ``where_not_equal``
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''

By default, calling ``where`` with two parameters (the column name and
the value) will combine them using an equals operator (``=``). For
example, calling ``where('name', 'Fred')`` will result in the clause
``WHERE name = "Fred"``.

If your coding style favours clarity over brevity, you may prefer to use
the ``where_equal`` method: this is identical to ``where``.

The ``where_not_equal`` method adds a ``WHERE column != "value"`` clause
to your query.

Shortcut: ``where_id_is``
'''''''''''''''''''''''''

This is a simple helper method to query the table by primary key.
Respects the ID column specified in the config.

Less than / greater than: ``where_lt``, ``where_gt``, ``where_lte``, ``where_gte``
''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

There are four methods available for inequalities:

-  Less than:
   ``$people = ORM::for_table('person')->where_lt('age', 10)->find_many();``
-  Greater than:
   ``$people = ORM::for_table('person')->where_gt('age', 5)->find_many();``
-  Less than or equal:
   ``$people = ORM::for_table('person')->where_lte('age', 10)->find_many();``
-  Greater than or equal:
   ``$people = ORM::for_table('person')->where_gte('age', 5)->find_many();``

String comparision: ``where_like`` and ``where_not_like``
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''

To add a ``WHERE ... LIKE`` clause, use:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->where_like('name', '%fred%')->find_many();

Similarly, to add a ``WHERE ... NOT LIKE`` clause, use:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->where_not_like('name', '%bob%')->find_many();

Set membership: ``where_in`` and ``where_not_in``
'''''''''''''''''''''''''''''''''''''''''''''''''

To add a ``WHERE ... IN ()`` or ``WHERE ... NOT IN ()`` clause, use the
``where_in`` and ``where_not_in`` methods respectively.

Both methods accept two arguments. The first is the column name to
compare against. The second is an *array* of possible values.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->where_in('name', array('Fred', 'Joe', 'John'))->find_many();

Working with ``NULL`` values: ``where_null`` and ``where_not_null``
'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

To add a ``WHERE column IS NULL`` or ``WHERE column IS NOT NULL``
clause, use the ``where_null`` and ``where_not_null`` methods
respectively. Both methods accept a single parameter: the column name to
test.

Raw WHERE clauses
'''''''''''''''''

If you require a more complex query, you can use the ``where_raw``
method to specify the SQL fragment for the WHERE clause exactly. This
method takes two arguments: the string to add to the query, and an
(optional) array of parameters which will be bound to the string. If
parameters are supplied, the string should contain question mark
characters (``?``) to represent the values to be bound, and the
parameter array should contain the values to be substituted into the
string in the correct order.

This method may be used in a method chain alongside other ``where_*``
methods as well as methods such as ``offset``, ``limit`` and
``order_by_*``. The contents of the string you supply will be connected
with preceding and following WHERE clauses with AND.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')
                ->where('name', 'Fred')
                ->where_raw('(`age` = ? OR `age` = ?)', array(20, 25))
                ->order_by_asc('name')
                ->find_many();

    // Creates SQL:
    SELECT * FROM `person` WHERE `name` = "Fred" AND (`age` = 20 OR `age` = 25) ORDER BY `name` ASC;

Note that this method only supports "question mark placeholder" syntax,
and NOT "named placeholder" syntax. This is because PDO does not allow
queries that contain a mixture of placeholder types. Also, you should
ensure that the number of question mark placeholders in the string
exactly matches the number of elements in the array.

If you require yet more flexibility, you can manually specify the entire
query. See *Raw queries* below.

Limits and offsets
''''''''''''''''''

*Note that these methods **do not** escape their query parameters and so
these should **not** be passed directly from user input.*

The ``limit`` and ``offset`` methods map pretty closely to their SQL
equivalents.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->where('gender', 'female')->limit(5)->offset(10)->find_many();

Ordering
''''''''

*Note that these methods **do not** escape their query parameters and so
these should **not** be passed directly from user input.*

Two methods are provided to add ``ORDER BY`` clauses to your query.
These are ``order_by_desc`` and ``order_by_asc``, each of which takes a
column name to sort by. The column names will be quoted.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->order_by_asc('gender')->order_by_desc('name')->find_many();

If you want to order by something other than a column name, then use the
``order_by_expr`` method to add an unquoted SQL expression as an
``ORDER BY`` clause.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->order_by_expr('SOUNDEX(`name`)')->find_many();

Grouping
^^^^^^^^

*Note that this method **does not** escape it query parameter and so
this should **not** by passed directly from user input.*

To add a ``GROUP BY`` clause to your query, call the ``group_by``
method, passing in the column name. You can call this method multiple
times to add further columns.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->where('gender', 'female')->group_by('name')->find_many();

It is also possible to ``GROUP BY`` a database expression:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->where('gender', 'female')->group_by_expr("FROM_UNIXTIME(`time`, '%Y-%m')")->find_many();

Having
^^^^^^

When using aggregate functions in combination with a ``GROUP BY`` you can use
``HAVING`` to filter based on those values.

``HAVING`` works in exactly the same way as all of the ``where*`` functions in Idiorm.
Substitute ``where_`` for ``having_`` to make use of these functions.

For example:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->group_by('name')->having_not_like('name', '%bob%')->find_many();

Result columns
^^^^^^^^^^^^^^

By default, all columns in the ``SELECT`` statement are returned from
your query. That is, calling:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT * FROM `person`;

The ``select`` method gives you control over which columns are returned.
Call ``select`` multiple times to specify columns to return or use
```select_many`` <#shortcuts-for-specifying-many-columns>`_ to specify
many columns at once.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->select('name')->select('age')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT `name`, `age` FROM `person`;

Optionally, you may also supply a second argument to ``select`` to
specify an alias for the column:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->select('name', 'person_name')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT `name` AS `person_name` FROM `person`;

Column names passed to ``select`` are quoted automatically, even if they
contain ``table.column``-style identifiers:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->select('person.name', 'person_name')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT `person`.`name` AS `person_name` FROM `person`;

If you wish to override this behaviour (for example, to supply a
database expression) you should instead use the ``select_expr`` method.
Again, this takes the alias as an optional second argument. You can
specify multiple expressions by calling ``select_expr`` multiple times
or use ```select_many_expr`` <#shortcuts-for-specifying-many-columns>`_
to specify many expressions at once.

.. code-block:: php

    <?php
    // NOTE: For illustrative purposes only. To perform a count query, use the count() method.
    $people_count = ORM::for_table('person')->select_expr('COUNT(*)', 'count')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT COUNT(*) AS `count` FROM `person`;

Shortcuts for specifying many columns
'''''''''''''''''''''''''''''''''''''

``select_many`` and ``select_many_expr`` are very similar, but they
allow you to specify more than one column at once. For example:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->select_many('name', 'age')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT `name`, `age` FROM `person`;

To specify aliases you need to pass in an array (aliases are set as the
key in an associative array):

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->select_many(array('first_name' => 'name'), 'age', 'height')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT `name` AS `first_name`, `age`, `height` FROM `person`;

You can pass the the following styles into ``select_many`` and
``select_many_expr`` by mixing and matching arrays and parameters:

.. code-block:: php

    <?php
    select_many(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5')
    select_many('column', 'column2', 'column3')
    select_many(array('column', 'column2', 'column3'), 'column4', 'column5')

All the select methods can also be chained with each other so you could
do the following to get a neat select query including an expression:

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->select_many('name', 'age', 'height')->select_expr('NOW()', 'timestamp')->find_many();

Will result in the query:

.. code-block:: php

    <?php
    SELECT `name`, `age`, `height`, NOW() AS `timestamp` FROM `person`;

DISTINCT
^^^^^^^^

To add a ``DISTINCT`` keyword before the list of result columns in your
query, add a call to ``distinct()`` to your query chain.

.. code-block:: php

    <?php
    $distinct_names = ORM::for_table('person')->distinct()->select('name')->find_many();

This will result in the query:

.. code-block:: php

    <?php
    SELECT DISTINCT `name` FROM `person`;

Joins
^^^^^

Idiorm has a family of methods for adding different types of ``JOIN``\ s
to the queries it constructs:

Methods: ``join``, ``inner_join``, ``left_outer_join``,
``right_outer_join``, ``full_outer_join``.

Each of these methods takes the same set of arguments. The following
description will use the basic ``join`` method as an example, but the
same applies to each method.

The first two arguments are mandatory. The first is the name of the
table to join, and the second supplies the conditions for the join. The
recommended way to specify the conditions is as an *array* containing
three components: the first column, the operator, and the second column.
The table and column names will be automatically quoted. For example:

.. code-block:: php

    <?php
    $results = ORM::for_table('person')->join('person_profile', array('person.id', '=', 'person_profile.person_id'))->find_many();

It is also possible to specify the condition as a string, which will be
inserted as-is into the query. However, in this case the column names
will **not** be escaped, and so this method should be used with caution.

.. code-block:: php

    <?php
    // Not recommended because the join condition will not be escaped.
    $results = ORM::for_table('person')->join('person_profile', 'person.id = person_profile.person_id')->find_many();

The ``join`` methods also take an optional third parameter, which is an
``alias`` for the table in the query. This is useful if you wish to join
the table to *itself* to create a hierarchical structure. In this case,
it is best combined with the ``table_alias`` method, which will add an
alias to the *main* table associated with the ORM, and the ``select``
method to control which columns get returned.

.. code-block:: php

    <?php
    $results = ORM::for_table('person')
        ->table_alias('p1')
        ->select('p1.*')
        ->select('p2.name', 'parent_name')
        ->join('person', array('p1.parent', '=', 'p2.id'), 'p2')
        ->find_many();

Aggregate functions
^^^^^^^^^^^^^^^^^^^

There is support for ``MIN``, ``AVG``, ``MAX`` and ``SUM`` in addition
to ``COUNT`` (documented earlier).

To return a minimum value of column, call the ``min()`` method.

.. code-block:: php

    <?php
    $min = ORM::for_table('person')->min('height');

The other functions (``AVG``, ``MAX`` and ``SUM``) work in exactly the
same manner. Supply a column name to perform the aggregate function on
and it will return an integer.

Raw queries
^^^^^^^^^^^

If you need to perform more complex queries, you can completely specify
the query to execute by using the ``raw_query`` method. This method
takes a string and optionally an array of parameters. The string can
contain placeholders, either in question mark or named placeholder
syntax, which will be used to bind the parameters to the query.

.. code-block:: php

    <?php
    $people = ORM::for_table('person')->raw_query('SELECT p.* FROM person p JOIN role r ON p.role_id = r.id WHERE r.name = :role', array('role' => 'janitor'))->find_many();

The ORM class instance(s) returned will contain data for all the columns
returned by the query. Note that you still must call ``for_table`` to
bind the instances to a particular table, even though there is nothing
to stop you from specifying a completely different table in the query.
This is because if you wish to later called ``save``, the ORM will need
to know which table to update.

Note that using ``raw_query`` is advanced and possibly dangerous, and
Idiorm does not make any attempt to protect you from making errors when
using this method. If you find yourself calling ``raw_query`` often, you
may have misunderstood the purpose of using an ORM, or your application
may be too complex for Idiorm. Consider using a more full-featured
database abstraction system.
