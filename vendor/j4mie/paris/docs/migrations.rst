Migrations
==========

Paris does not have native support for migrations, but some work has been
done to integrate `PHPMig`_. If you want to have migrations in your project
then this is recommended route as Paris will never have migrations directly
implemented in the core. Please refer to the Paris and Idiorm Philosophy for
reasons why.

To integrate Paris with PHPMig you will need to follow their `installation
instructions`_ and then configure it to use the Paris PDO instance:

.. code-block:: php

   <?php
   $container['db'] = $container->share(function(){
       return ORM::get_db();
   });
   $container['phpmig.adapter'] = $container->share(function() use ($container) {
       return new Adapter\PDO\Sql($container['db'], 'migrations');
   });

.. _PHPMig: https://github.com/davedevelopment/phpmig
.. _installation instructions: https://github.com/davedevelopment/phpmig#getting-started