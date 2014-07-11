<?php
// Stub for @PHAR_FILENAME@
// Basic Phar loader. Includes QueryPath.php by default.
try {
    Phar::mapPhar('-PHAR_FILENAME%'); // Uses Phar alias.
    include 'phar://-PHAR_FILENAME%/QueryPath.php';
} catch (PharException $e) {
    echo $e->getMessage();
    die('Cannot initialize Phar');
}
__HALT_COMPILER();
?>