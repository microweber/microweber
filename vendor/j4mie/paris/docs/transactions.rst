Transactions
============

Paris (or Idiorm) doesn’t supply any extra methods to deal with
transactions, but it’s very easy to use PDO’s built-in methods:

.. code-block:: php

    <?php
    // Start a transaction
    ORM::get_db()->beginTransaction();

    // Commit a transaction
    ORM::get_db()->commit();

    // Roll back a transaction
    ORM::get_db()->rollBack();

For more details, see `the PDO documentation on Transactions`_.

.. _the PDO documentation on Transactions: http://www.php.net/manual/en/pdo.transactions.php