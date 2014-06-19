Associations
============

Paris provides a simple API for one-to-one, one-to-many and many-to-many
relationships (associations) between models. It takes a different
approach to many other ORMs, which use associative arrays to add
configuration metadata about relationships to model classes. These
arrays can often be deeply nested and complex, and are therefore quite
error-prone.

Instead, Paris treats the act of querying across a relationship as a
*behaviour*, and supplies a family of helper methods to help generate
such queries. These helper methods should be called from within
*methods* on your model classes which are named to describe the
relationship. These methods return ORM instances (rather than actual
Model instances) and so, if necessary, the relationship query can be
modified and added to before it is run.

Summary
^^^^^^^

The following list summarises the associations provided by Paris, and
explains which helper method supports each type of association:

One-to-one
''''''''''

Use ``has_one`` in the base, and ``belongs_to`` in the associated model.

One-to-many
'''''''''''

Use ``has_many`` in the base, and ``belongs_to`` in the associated
model.

Many-to-many
''''''''''''

Use ``has_many_through`` in both the base and associated models.

Below, each association helper method is discussed in detail.

Has-one
^^^^^^^

One-to-one relationships are implemented using the ``has_one`` method.
For example, say we have a ``User`` model. Each user has a single
``Profile``, and so the ``user`` table should be associated with the
``profile`` table. To be able to find the profile for a particular user,
we should add a method called ``profile`` to the ``User`` class (note
that the method name here is arbitrary, but should describe the
relationship). This method calls the protected ``has_one`` method
provided by Paris, passing in the class name of the related object. The
``profile`` method should return an ORM instance ready for (optional)
further filtering.

.. code-block:: php

    <?php
    class Profile extends Model {
    }

    class User extends Model {
        public function profile() {
            return $this->has_one('Profile');
        }
    }

The API for this method works as follows:

.. code-block:: php

    <?php
    // Select a particular user from the database
    $user = Model::factory('User')->find_one($user_id);

    // Find the profile associated with the user
    $profile = $user->profile()->find_one();

By default, Paris assumes that the foreign key column on the related
table has the same name as the current (base) table, with ``_id``
appended. In the example above, Paris will look for a foreign key column
called ``user_id`` on the table used by the ``Profile`` class. To
override this behaviour, add a second argument to your ``has_one`` call,
passing the name of the column to use.

In addition, Paris assumes that the foreign key column in the current (base)
 table is the primary key column of the base table. In the example above, 
Paris will use the column called ``user_id`` (assuming ``user_id`` is the 
primary key for the user table) in the base table (in this case the user table) 
as the foreign key column in the base table. To override this behaviour, 
add a third argument to your ``has_one call``, passing the name of the column 
you intend to use as the foreign key column in the base table.

Has many
^^^^^^^^

One-to-many relationships are implemented using the ``has_many`` method.
For example, say we have a ``User`` model. Each user has several
``Post`` objects. The ``user`` table should be associated with the
``post`` table. To be able to find the posts for a particular user, we
should add a method called ``posts`` to the ``User`` class (note that
the method name here is arbitrary, but should describe the
relationship). This method calls the protected ``has_many`` method
provided by Paris, passing in the class name of the related objects.
**Pass the model class name literally, not a pluralised version**. The
``posts`` method should return an ORM instance ready for (optional)
further filtering.

.. code-block:: php

    <?php
    class Post extends Model {
    }

    class User extends Model {
        public function posts() {
            return $this->has_many('Post'); // Note we use the model name literally - not a pluralised version
        }
    }

The API for this method works as follows:

.. code-block:: php

    <?php
    // Select a particular user from the database
    $user = Model::factory('User')->find_one($user_id);

    // Find the posts associated with the user
    $posts = $user->posts()->find_many();

By default, Paris assumes that the foreign key column on the related
table has the same name as the current (base) table, with ``_id``
appended. In the example above, Paris will look for a foreign key column
called ``user_id`` on the table used by the ``Post`` class. To override
this behaviour, add a second argument to your ``has_many`` call, passing
the name of the column to use.

In addition, Paris assumes that the foreign key column in the current (base) 
table is the primary key column of the base table. In the example above, Paris 
will use the column called ``user_id`` (assuming ``user_id`` is the primary key 
for the user table) in the base table (in this case the user table) as the 
foreign key column in the base table. To override this behaviour, add a third 
argument to your ``has_many call``, passing the name of the column you intend 
to use as the foreign key column in the base table.

Belongs to
^^^^^^^^^^

The ‘other side’ of ``has_one`` and ``has_many`` is ``belongs_to``. This
method call takes identical parameters as these methods, but assumes the
foreign key is on the *current* (base) table, not the related table.

.. code-block:: php

    <?php
    class Profile extends Model {
        public function user() {
            return $this->belongs_to('User');
        }
    }

    class User extends Model {
    }

The API for this method works as follows:

.. code-block:: php

    <?php
    // Select a particular profile from the database
    $profile = Model::factory('Profile')->find_one($profile_id);

    // Find the user associated with the profile
    $user = $profile->user()->find_one();

Again, Paris makes an assumption that the foreign key on the current
(base) table has the same name as the related table with ``_id``
appended. In the example above, Paris will look for a column named
``user_id``. To override this behaviour, pass a second argument to the
``belongs_to`` method, specifying the name of the column on the current
(base) table to use.

Paris also makes an assumption that the foreign key in the associated (related) 
table is the primary key column of the related table. In the example above, 
Paris will look for a column named ``user_id`` in the user table (the related 
table in this example). To override this behaviour, pass a third argument to 
the belongs_to method, specifying the name of the column in the related table 
to use as the foreign key column in the related table.

Has many through
^^^^^^^^^^^^^^^^

Many-to-many relationships are implemented using the
``has_many_through`` method. This method has only one required argument:
the name of the related model. Supplying further arguments allows us to
override default behaviour of the method.

For example, say we have a ``Book`` model. Each ``Book`` may have
several ``Author`` objects, and each ``Author`` may have written several
``Books``. To be able to find the authors for a particular book, we
should first create an intermediary model. The name for this model
should be constructed by concatenating the names of the two related
classes, in alphabetical order. In this case, our classes are called
``Author`` and ``Book``, so the intermediate model should be called
``AuthorBook``.

We should then add a method called ``authors`` to the ``Book`` class
(note that the method name here is arbitrary, but should describe the
relationship). This method calls the protected ``has_many_through``
method provided by Paris, passing in the class name of the related
objects. **Pass the model class name literally, not a pluralised
version**. The ``authors`` method should return an ORM instance ready
for (optional) further filtering.

.. code-block:: php

    <?php
    class Author extends Model {
        public function books() {
            return $this->has_many_through('Book');
        }
    }

    class Book extends Model {
        public function authors() {
            return $this->has_many_through('Author');
        }
    }

    class AuthorBook extends Model {
    }

The API for this method works as follows:

.. code-block:: php

    <?php
    // Select a particular book from the database
    $book = Model::factory('Book')->find_one($book_id);

    // Find the authors associated with the book
    $authors = $book->authors()->find_many();

    // Get the first author
    $first_author = $authors[0];

    // Find all the books written by this author
    $first_author_books = $first_author->books()->find_many();

Overriding defaults
'''''''''''''''''''

The ``has_many_through`` method takes up to four arguments, which allow
us to progressively override default assumptions made by the method.

**First argument: associated model name** - this is mandatory and should
be the name of the model we wish to select across the association.

**Second argument: intermediate model name** - this is optional and
defaults to the names of the two associated models, sorted
alphabetically and concatenated.

**Third argument: custom key to base table on intermediate table** -
this is optional, and defaults to the name of the base table with
``_id`` appended.

**Fourth argument: custom key to associated table on intermediate
table** - this is optional, and defaults to the name of the associated
table with ``_id`` appended.

**Fifth argument: foreign key column in the base table** - 
this is optional, and defaults to the name of the primary key column in 
the base table.

**Sixth argument: foreign key column in the associated table** - 
this is optional, and defaults to the name of the primary key column 
in the associated table.