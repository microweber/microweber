Philosophy
==========

The `Pareto Principle`_ states that *roughly 80% of the effects come
from 20% of the causes.* In software development terms, this could be
translated into something along the lines of *80% of the results come
from 20% of the complexity*. In other words, you can get pretty far by
being pretty stupid.

**Idiorm is deliberately simple**. Where other ORMs consist of dozens of
classes with complex inheritance hierarchies, Idiorm has only one class,
``ORM``, which functions as both a fluent ``SELECT`` query API and a
simple CRUD model class. If my hunch is correct, this should be quite
enough for many real-world applications. Let’s face it: most of us
aren’t building Facebook. We’re working on small-to-medium-sized
projects, where the emphasis is on simplicity and rapid development
rather than infinite flexibility and features.

You might think of **Idiorm** as a *micro-ORM*. It could, perhaps, be
“the tie to go along with `Slim`_\ ’s tux” (to borrow a turn of phrase
from `DocumentCloud`_). Or it could be an effective bit of spring
cleaning for one of those horrendous SQL-littered legacy PHP apps you
have to support.

**Idiorm** might also provide a good base upon which to build
higher-level, more complex database abstractions. For example, `Paris`_
is an implementation of the `Active Record pattern`_ built on top of
Idiorm.

.. _Pareto Principle: http://en.wikipedia.org/wiki/Pareto_principle
.. _Slim: http://github.com/codeguy/slim/
.. _DocumentCloud: http://github.com/documentcloud/underscore
.. _Paris: http://github.com/j4mie/paris
.. _Active Record pattern: http://martinfowler.com/eaaCatalog/activeRecord.html