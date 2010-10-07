<?php
/**
 * A collection of exceptions used throughout the library
 *
 * @author Rob Young <bubblenut@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */ 

class MioException extends Exception {}

class MioBlockingException extends MioException {}

class MioClosedException extends MioException {}

class MioOpsException extends MioException {}
