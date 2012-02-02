<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/sql/sql.php
 *   Author: Nigel McNie
 *   E-mail: nigel@geshi.org
 * </pre>
 * 
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 * 
 * This program is part of GeSHi.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
 *
 * @package    geshi
 * @subpackage lang
 * @author     Nigel McNie
 * @copyright  (C) 2006 Nigel McNie
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version    1.1.2alpha3
 */

//
// SQL:
//  Statements separated by ;
//  Statements contain tokens
//  Tokens can be:
//    - key word
//    - identifier (name)
//    - quoted identifier
//    - literal (constant)
//    - special character symbol
//
//  Keywords and identifiers have the same lexical structure!
//
// TODO:
//   Merge all keywords back into one array. The code parser will instead
//   be written to be much smarter about how it tells between an identifier
//   and a keyword.
//
function geshi_sql_sql (&$context)
{
    $context->addChild('quoted_identifier', 'string');
    $context->addChild('string', 'string');
    $context->addChild('bitstring');
    $context->addChild('hexstring');
    $context->addChild('single_comment');
    $context->addChild('multi_comment');

    $context->addKeywordGroup(array(
        'ABORT', 'ABS', 'ABSOLUTE', 'ACCESS', 'ADA', 'ADD', 'ADMIN',
        'AFTER', 'AGGREGATE', 'ALIAS', 'ALL', 'ALLOCATE', 'ALTER', 'ANALYSE',
        'ANALYZE', 'AND', 'ANY', 'ARE', 'AS', 'ASC', 'ASENSITIVE',
        'ASSERTION', 'ASSIGNMENT', 'ASYMMETRIC', 'AT', 'ATOMIC',
        'AUTHORIZATION', 'AVG', 'BACKWARD', 'BEFORE', 'BEGIN', 'BETWEEN',
        'BITVAR', 'BIT_LENGTH',
        'BOTH', 'BREADTH', 'BY', 'C', 'CACHE', 'CALL', 'CALLED', 'CARDINALITY',
        'CASCADE', 'CASCADED', 'CASE', 'CAST', 'CATALOG', 'CATALOG_NAME',
        'CHAIN', 'CHARACTERISTICS', 'CHARACTER_LENGTH',
        'CHARACTER_SET_CATALOG', 'CHARACTER_SET_NAME', 'CHARACTER_SET_SCHEMA',
        'CHAR_LENGTH', 'CHECK', 'CHECKED', 'CHECKPOINT', 'CLASS',
        'CLASS_ORIGIN', 'CLOB', 'CLOSE', 'CLUSTER', 'COALSECE', 'COBOL',
        'COLLATE', 'COLLATION', 'COLLATION_CATALOG', 'COLLATION_NAME',
        'COLLATION_SCHEMA', 'COLUMN', 'COLUMN_NAME', 'COMMAND_FUNCTION',
        'COMMAND_FUNCTION_CODE', 'COMMENT', 'COMMIT', 'COMMITTED',
        'COMPLETION', 'CONDITION_NUMBER', 'CONNECT', 'CONNECTION', 
        'CONNECTION_NAME', 'CONSTRAINT', 'CONSTRAINTS', 'CONSTRAINT_CATALOG',
        'CONSTRAINT_NAME', 'CONSTRAINT_SCHEMA', 'CONSTRUCTOR', 'CONTAINS',
        'CONTINUE', 'CONVERSION', 'CONVERT', 'COPY', 'CORRESPONTING', 'COUNT',
        'CREATE', 'CREATEDB', 'CREATEUSER', 'CROSS', 'CUBE', 'CURRENT',
        'CURRENT_DATE', 'CURRENT_PATH', 'CURRENT_ROLE', 'CURRENT_TIME',
        'CURRENT_TIMESTAMP', 'CURRENT_USER', 'CURSOR', 'CURSOR_NAME', 'CYCLE',
        'DATA', 'DATABASE', 'DATETIME_INTERVAL_CODE',
        'DATETIME_INTERVAL_PRECISION', 'DAY', 'DEALLOCATE',
        'DECLARE', 'DEFAULT', 'DEFAULTS', 'DEFERRABLE', 'DEFERRED', 'DEFINED',
        'DEFINER', 'DELETE', 'DELIMITER', 'DELIMITERS', 'DEREF',
        'DESC', 'DESCRIBE', 'DESCRIPTOR', 'DESTROY', 'DESTRUCTOR',
        'DETERMINISTIC', 'DIAGNOSTICS', 'DICTIONARY', 'DISCONNECT', 'DISPATCH',
        'DISTINCT', 'DO', 'DOMAIN', 'DROP', 'DYNAMIC',
        'DYNAMIC_FUNCTION', 'DYNAMIC_FUNCTION_CODE', 'EACH', 'ELSE',
        'ENCODING', 'ENCRYPTED', 'END', 'END-EXEC', 'EQUALS', 'ESCAPE',
        'EVERY', 'EXCEPT', 'ESCEPTION', 'EXCLUDING', 'EXCLUSIVE', 'EXEC',
        'EXECUTE', 'EXISTING', 'EXISTS', 'EXPLAIN', 'EXTERNAL', 'EXTRACT',
        'FALSE', 'FETCH', 'FINAL', 'FIRST', 'FOR', 'FORCE', 'FOREIGN',
        'FORTRAN', 'FORWARD', 'FOUND', 'FREE', 'FREEZE', 'FROM', 'FULL',
        'FUNCTION', 'G', 'GENERAL', 'GENERATED', 'GET', 'GLOBAL', 'GO', 'GOTO',
        'GRANT', 'GRANTED', 'GROUP', 'GROUPING', 'HANDLER', 'HAVING',
        'HIERARCHY', 'HOLD', 'HOST', 'IDENTITY', 'IGNORE', 'ILIKE',
        'IMMEDIATE', 'IMMUTABLE', 'IMPLEMENTATION', 'IMPLICIT', 'IN',
        'INCLUDING', 'INCREMENT', 'INDEX', 'INDITCATOR', 'INFIX', 'INHERITS',
        'INITIALIZE', 'INITIALLY', 'INNER', 'INOUT', 'INPUT', 'INSENSITIVE',
        'INSERT', 'INSTANTIABLE', 'INSTEAD',
        'INTERSECT', 'INTO', 'INVOKER', 'IS', 'ISNULL',
        'ISOLATION', 'ITERATE', 'JOIN', 'K', 'KEY', 'KEY_MEMBER', 'KEY_TYPE',
        'LANCOMPILER', 'LANGUAGE', 'LARGE', 'LAST', 'LATERAL', 'LEADING',
        'LEFT', 'LENGTH', 'LESS', 'LEVEL', 'LIKE', 'LILMIT', 'LISTEN', 'LOAD',
        'LOCAL', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCATION', 'LOCATOR', 'LOCK',
        'LOWER', 'M', 'MAP', 'MATCH', 'MAX', 'MAXVALUE', 'MESSAGE_LENGTH',
        'MESSAGE_OCTET_LENGTH', 'MESSAGE_TEXT', 'METHOD', 'MIN', 'MINUTE',
        'MINVALUE', 'MOD', 'MODE', 'MODIFIES', 'MODIFY', 'MONTH',
        'MORE', 'MOVE', 'MUMPS', 'NAMES', 'NATIONAL', 'NATURAL',
        'NCHAR', 'NCLOB', 'NEW', 'NEXT', 'NO', 'NOCREATEDB', 'NOCREATEUSER',
        'NONE', 'NOT', 'NOTHING', 'NOTIFY', 'NOTNULL', 'NULL', 'NULLABLE',
        'NULLIF', 'OBJECT', 'OCTET_LENGTH', 'OF', 'OFF',
        'OFFSET', 'OIDS', 'OLD', 'ON', 'ONLY', 'OPEN', 'OPERATION', 'OPERATOR',
        'OPTION', 'OPTIONS', 'OR', 'ORDER', 'ORDINALITY', 'OUT', 'OUTER',
        'OUTPUT', 'OVERLAPS', 'OVERLAY', 'OVERRIDING', 'OWNER', 'PAD',
        'PARAMETER', 'PARAMETERS', 'PARAMETER_MODE', 'PARAMATER_NAME',
        'PARAMATER_ORDINAL_POSITION', 'PARAMETER_SPECIFIC_CATALOG',
        'PARAMETER_SPECIFIC_NAME', 'PARAMATER_SPECIFIC_SCHEMA', 'PARTIAL',
        'PASCAL', 'PENDANT', 'PLACING', 'PLI', 'POSITION',
        'POSTFIX', 'PRECISION', 'PREFIX', 'PREORDER', 'PREPARE', 'PRESERVE',
        'PRIMARY', 'PRIOR', 'PRIVILEGES', 'PROCEDURAL', 'PROCEDURE', 'PUBLIC',
        'READ', 'READS', 'RECHECK', 'RECURSIVE', 'REF', 'REFERENCES',
        'REFERENCING', 'REINDEX', 'RELATIVE', 'RENAME', 'REPEATABLE',
        'REPLACE', 'RESET', 'RESTART', 'RESTRICT', 'RESULT', 'RETURN',
        'RETURNED_LENGTH', 'RETURNED_OCTET_LENGTH', 'RETURNED_SQLSTATE',
        'RETURNS', 'REVOKE', 'RIGHT', 'ROLE', 'ROLLBACK', 'ROLLUP', 'ROUTINE',
        'ROUTINE_CATALOG', 'ROUTINE_NAME', 'ROUTINE_SCHEMA', 'ROW', 'ROWS',
        'ROW_COUNT', 'RULE', 'SAVE_POINT', 'SCALE', 'SCHEMA', 'SCHEMA_NAME',
        'SCOPE', 'SCROLL', 'SEARCH', 'SECOND', 'SECURITY', 'SELECT',
        'SELF', 'SENSITIVE', 'SERIALIZABLE', 'SERVER_NAME',
        'SESSION', 'SESSION_USER', 'SET', 'SETOF', 'SETS', 'SHARE', 'SHOW',
        'SIMILAR', 'SIMPLE', 'SIZE', 'SOME', 'SOURCE', 'SPACE',
        'SPECIFIC', 'SPECIFICTYPE', 'SPECIFIC_NAME', 'SQL', 'SQLCODE',
        'SQLERROR', 'SQLEXCEPTION', 'SQLSTATE', 'SQLWARNINIG', 'STABLE',
        'START', 'STATE', 'STATEMENT', 'STATIC', 'STATISTICS', 'STDIN',
        'STDOUT', 'STORAGE', 'STRICT', 'STRUCTURE', 'STYPE', 'SUBCLASS_ORIGIN',
        'SUBLIST', 'SUBSTRING', 'SUM', 'SYMMETRIC', 'SYSID', 'SYSTEM',
        'SYSTEM_USER', 'TABLE', 'TABLE_NAME',' TEMP', 'TEMPLATE', 'TEMPORARY',
        'TERMINATE', 'THAN', 'THEN', 'TIMESTAMP', 'TIMEZONE_HOUR',
        'TIMEZONE_MINUTE', 'TO', 'TOAST', 'TRAILING', 'TRANSATION',
        'TRANSACTIONS_COMMITTED', 'TRANSACTIONS_ROLLED_BACK',
        'TRANSATION_ACTIVE', 'TRANSFORM', 'TRANSFORMS', 'TRANSLATE',
        'TRANSLATION', 'TREAT', 'TRIGGER', 'TRIGGER_CATALOG', 'TRIGGER_NAME',
        'TRIGGER_SCHEMA', 'TRIM', 'TRUE', 'TRUNCATE', 'TRUSTED', 'TYPE',
        'UNCOMMITTED', 'UNDER', 'UNENCRYPTED', 'UNION', 'UNIQUE', 'UNKNOWN',
        'UNLISTEN', 'UNNAMED', 'UNNEST', 'UNTIL', 'UPDATE', 'UPPER', 'USAGE',
        'USER', 'USER_DEFINED_TYPE_CATALOG', 'USER_DEFINED_TYPE_NAME',
        'USER_DEFINED_TYPE_SCHEMA', 'USING', 'VACUUM', 'VALID', 'VALIDATOR',
        'VALUES', 'VARIABLE', 'VERBOSE',
        'VERSION', 'VIEW', 'VOLATILE', 'WHEN', 'WHENEVER', 'WHERE', 'WITH',
        'WITHOUT', 'WORK', 'WRITE', 'YEAR', 'ZONE' 
    ), 'keyword/reserved');

    // Need to take nonreserved keywords out of the above array and put here...
    // DEPTH, PATH and SEQUENCE are reserved in SQL99 but not in postgres
    // (so presumably not elsewhere)
    // ACTION, MODULE and SECTION are reserved in SQL92 and 99 but not in
    // postgres
    // TIME is reserved in SQL92 and 99 and especially restricted in postgres
    // @todo [blocking 1.1.2] codeparser for SQL should perhaps be smarter
    // where keywords/fieldnames are concerned...
    // e.g. detect CREATE TABLE rule, then make first stuff field names unless
    // it's ones like CONSTRAINT, PRIMARY etc...
    // @todo to add here:
    //  version, search, scale, data, year, rule, role, reads, 
    $context->addKeywordGroup(array(
        'ACTION', 'DEPTH', 'INSTANCE', 'MODULE', 'NAME', 'PASSWORD', 'PATH',
        'SECTION', 'SEQUENCE', 'TIME', 'VALUE'
    ), 'keyword/nonreserved');
    // This group contains datatypes, which are really keywords but we split
    // them out because people like them highlighted differently. For some
    // reason the postgres documentation I got these lists from did not list
    // "SERIAL" as a keyword...
    $context->addKeywordGroup(array(
        'ARRAY', 'BIGINT', 'BINARY', 'BIT', 'BLOB', 'BOOLEAN', 'CHAR',
        'CHARACTER', 'DATE', 'DEC', 'DECIMAL', 'FLOAT', 'INT', 'INTEGER',
        'INTERVAL', 'NUMBER', 'NUMERIC', 'REAL', 'SERIAL', 'SMALLINT',
        'VARCHAR', 'VARYING',
        // These types added but weren't listed in the postgres manual
        'INT8', 'SERIAL8', 'TEXT'
    ), 'type');
    
    $context->addSymbolGroup(array(
        ';', ':', '(', ')', '[', ']', ',', '.'
    ), 'symbol');
    
    $context->addSymbolGroup(array(
        '+', '-', '*', '/', '<', '>', '=', '~', '!', '@', '#', '%', '^', '&',
        '|', '`', '?'
    ), 'operator');
    
    $context->addRegexGroup('#(\$\d+)#', '$', array(
        1 => array('positional_parameter', false)
    ));
    
    $context->useStandardIntegers();
    $context->useStandardDoubles();
    
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_sql_sql_quoted_identifier (&$context)
{
    $context->addDelimiters('"', '"');
    $context->addEscapeGroup('"');
}

function geshi_sql_sql_string (&$context)
{
    // This context starts with a ' and ends with one too
    $context->addDelimiters(array("'", '"'), array("'", '"'));

    // If a ' occurs it can escape. There's nothing else listed as escape
    // characters so it only escapes itself.
    $context->addEscapeGroup("'");

    // The backslash escape is not SQL standard but is used in many databases
    // regardless (e.g. mysql, postgresql)
    // This rule means that the backslash escapes the array given (inc. the regex)
    // As such, the definitions given here are perfectly in line with the new feature
    // The only other feature is that the escape char could actually be an array of them
    $context->addEscapeGroup('\\', array('b', 'f', 'n', 'r', 't', "'",
        'REGEX#[0-7]{3}#')); 
}

function geshi_sql_sql_bitstring (&$context)
{
    // @todo [blocking 1.1.2] use parser to detect that only 0 and 1 are in
    // the string
    $context->addDelimiters("B'", "'");
}

function geshi_sql_sql_hexstring (&$context)
{
    // @todo [blocking 1.1.2] use parser to detect that only 0 - F are in
    // the string
    $context->addDelimiters("X'", "'");
}


function geshi_sql_sql_single_comment (&$context)
{
    $context->addDelimiters('--', "\n");
}

function geshi_sql_sql_multi_comment (&$context)
{
    // @todo [blocking 1.1.5] sql multiline comments can be nested
    $context->addDelimiters('/*', '*/');
}
?>
