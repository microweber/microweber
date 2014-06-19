A word on validation
====================

It’s generally considered a good idea to centralise your data validation
in a single place, and a good place to do this is inside your model
classes. This is preferable to handling validation alongside form
handling code, for example. Placing validation code inside models means
that if you extend your application in the future to update your model
via an alternative route (say a REST API rather than a form) you can
re-use the same validation code.

Despite this, Paris doesn’t provide any built-in support for validation.
This is because validation is potentially quite complex, and often very
application-specific. Paris is deliberately quite ignorant about your
actual data - it simply executes queries, and gives you the
responsibility of making sure the data inside your models is valid and
correct. Adding a full validation framework to Paris would probably
require more code than Paris itself!

However, there are several simple ways that you could add validation to
your models without any help from Paris. You could override the
``save()`` method, check the data is valid, and return ``false`` on
failure, or call ``parent::save()`` on success. You could create your
own subclass of the ``Model`` base class and add your own generic
validation methods. Or you could write your own external validation
framework which you pass model instances to for checking. Choose
whichever approach is most suitable for your own requirements.