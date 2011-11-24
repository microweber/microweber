<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/php/common.php
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
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 * 
 */

/**#@+
 * @access private
 */

// @todo [blocking 1.1.9] highlight $_GET/$_POST etc as a different context
// (maybe do this via code parser)
/** Get the GeSHiPHPDoubleStringContext class */
require_once GESHI_LANGUAGES_ROOT . 'php' . GESHI_DIR_SEP
    . 'class.geshiphpdoublestringcontext.php';

function geshi_php_common (&$context)
{
    // Delimiters for PHP
    $context->addDelimiters(array('<?php', '<?'), '?>');
    $context->addDelimiters('<%', '%>');

    // Children for PHP
    $context->addChild('single_string', 'string');
    $context->addChild('double_string', 'phpdoublestring');
    $context->addChild('heredoc', 'phpdoublestring');
    $context->addChild('single_comment');
    $context->addChild('multi_comment');
    $context->addChild('phpdoc_comment');
    
    // Keywords in all PHP version that have php.net manual entries
    $context->addKeywordGroup(array(
        'array', 'die', 'echo', 'empty', 'eval', 'exit', 'include',
        'include_once', 'isset', 'list', 'print', 'require', 'require_once',
        'return', 'unset'
    ), 'keyword', false, 'http://www.php.net/{FNAME}');
    
    // Keywords in all PHP versions with no manual entry
    $context->addKeywordGroup(array(
        'and', 'as', 'break', 'case', 'class', 'continue', 'declare',
        'default', 'do', 'else', 'elseif', 'enddeclare', 'endfor',
        'endforeach', 'endif', 'endswitch', 'endwhile', 'extends', 'for',
        'foreach', 'function', 'global', 'if', 'or', 'parent', 'static',
        'switch', 'new', 'use', 'var', 'while', 'xor'
    ), 'keyword');
    
    // Keywords in all PHP versions that are case sensitive
    $context->addKeywordGroup(array(
        '__CLASS__', '__FILE__', '__FUNCTION__', '__LINE__'
    ), 'keyword', true);
    
    // Primitive types in all PHP versions
    $context->addKeywordGroup(array(
        'bool', 'boolean', 'float',  'int', 'integer', 'string', 'object'
    ), 'type', true, 'http://php.net/{FNAME}');
    
    // Double type has no manual entry, it's an alias of float
    $context->addKeywordGroup(array(
        'double'
    ), 'type', true, 'http://php.net/float');
    
    // Constants in all PHP versions
    // @todo [blocking 1.1.2] Don't want the extension constants in here,
    // because it's certain that I've missed various constants. It should
    // be an option to enable constants for specific extensions
    $context->addKeywordGroup(array(
        'E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE', 'E_CORE_ERROR',
        'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING',
        'E_USER_ERROR', 'E_USER_WARNING', 'E_USER_NOTICE', 'E_ALL', 'TRUE',
        'FALSE', 'ZEND_THREAD_SAFE', 'NULL', 'PHP_VERSION', 'PHP_OS',
        'PHP_SAPI', 'DEFAULT_INCLUDE_PATH', 'PEAR_INSTALL_DIR',
        'PEAR_EXTENSION_DIR', 'PHP_EXTENSION_DIR', 'PHP_PREFIX', 'PHP_BINDIR',
        'PHP_LIBDIR', 'PHP_DATADIR', 'PHP_SYSCONFDIR', 'PHP_LOCALSTATEDIR',
        'PHP_CONFIG_FILE_PATH', 'PHP_CONFIG_FILE_SCAN_DIR', 'PHP_SHLIB_SUFFIX',
        'PHP_EOL', 'PHP_INT_MAX', 'PHP_INT_SIZE', 'PHP_OUTPUT_HANDLER_START',
        'PHP_OUTPUT_HANDLER_CONT', 'PHP_OUTPUT_HANDLER_END', 'UPLOAD_ERR_OK',
        'UPLOAD_ERR_INI_SIZE', 'UPLOAD_ERR_FORM_SIZE', 'UPLOAD_ERR_PARTIAL',
        'UPLOAD_ERR_NO_FILE', 'UPLOAD_ERR_NO_TMP_DIR', 'UPLOAD_ERR_CANT_WRITE',
        'YPERR_BADARGS', 'YPERR_BADDB', 'YPERR_BUSY', 'YPERR_DOMAIN',
        'YPERR_KEY', 'YPERR_MAP', 'YPERR_NODOM', 'YPERR_NOMORE', 'YPERR_PMAP',
        'YPERR_RESRC', 'YPERR_RPC', 'YPERR_YPBIND', 'YPERR_YPERR',
        'YPERR_YPSERV', 'YPERR_VERS', 'XML_ERROR_NONE', 'XML_ERROR_NO_MEMORY',
        'XML_ERROR_SYNTAX', 'XML_ERROR_NO_ELEMENTS', 'XML_ERROR_INVALID_TOKEN',
        'XML_ERROR_UNCLOSED_TOKEN', 'XML_ERROR_PARTIAL_CHAR',
        'XML_ERROR_TAG_MISMATCH', 'XML_ERROR_DUPLICATE_ATTRIBUTE',
        'XML_ERROR_JUNK_AFTER_DOC_ELEMENT', 'XML_ERROR_PARAM_ENTITY_REF',
        'XML_ERROR_UNDEFINED_ENTITY', 'XML_ERROR_RECURSIVE_ENTITY_REF',
        'XML_ERROR_ASYNC_ENTITY', 'XML_ERROR_BAD_CHAR_REF',
        'XML_ERROR_BINARY_ENTITY_REF',
        'XML_ERROR_ATTRIBUTE_EXTERNAL_ENTITY_REF',
        'XML_ERROR_MISPLACED_XML_PI', 'XML_ERROR_UNKNOWN_ENCODING',
        'XML_ERROR_INCORRECT_ENCODING', 'XML_ERROR_UNCLOSED_CDATA_SECTION',
        'XML_ERROR_EXTERNAL_ENTITY_HANDLING', 'XML_OPTION_CASE_FOLDING',
        'XML_OPTION_TARGET_ENCODING', 'XML_OPTION_SKIP_TAGSTART',
        'XML_OPTION_SKIP_WHITE', 'T_INCLUDE', 'T_INCLUDE_ONCE', 'T_EVAL',
        'T_REQUIRE', 'T_REQUIRE_ONCE', 'T_LOGICAL_OR', 'T_LOGICAL_XOR',
        'T_LOGICAL_AND', 'T_PRINT', 'T_PLUS_EQUAL', 'T_MINUS_EQUAL',
        'T_MUL_EQUAL', 'T_DIV_EQUAL', 'T_CONCAT_EQUAL', 'T_MOD_EQUAL',
        'T_AND_EQUAL', 'T_OR_EQUAL', 'T_XOR_EQUAL', 'T_SL_EQUAL', 'T_SR_EQUAL',
        'T_BOOLEAN_OR', 'T_BOOLEAN_AND', 'T_IS_EQUAL', 'T_IS_NOT_EQUAL',
        'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL', 'T_IS_SMALLER_OR_EQUAL',
        'T_IS_GREATER_OR_EQUAL', 'T_SL', 'T_SR', 'T_INC', 'T_DEC',
        'T_INT_CAST', 'T_DOUBLE_CAST', 'T_STRING_CAST', 'T_ARRAY_CAST',
        'T_OBJECT_CAST', 'T_BOOL_CAST', 'T_UNSET_CAST', 'T_NEW', 'T_EXIT',
        'T_IF', 'T_ELSEIF', 'T_ELSE', 'T_ENDIF', 'T_LNUMBER', 'T_DNUMBER',
        'T_STRING', 'T_STRING_VARNAME', 'T_VARIABLE', 'T_NUM_STRING',
        'T_INLINE_HTML', 'T_CHARACTER', 'T_BAD_CHARACTER',
        'T_ENCAPSED_AND_WHITESPACE', 'T_CONSTANT_ENCAPSED_STRING', 'T_ECHO',
        'T_DO', 'T_WHILE', 'T_ENDWHILE', 'T_FOR', 'T_ENDFOR', 'T_FOREACH',
        'T_ENDFOREACH', 'T_DECLARE', 'T_ENDDECLARE', 'T_AS', 'T_SWITCH',
        'T_ENDSWITCH', 'T_CASE', 'T_DEFAULT', 'T_BREAK', 'T_CONTINUE',
        'T_OLD_FUNCTION', 'T_FUNCTION', 'T_CONST', 'T_RETURN', 'T_USE',
        'T_GLOBAL', 'T_STATIC', 'T_VAR', 'T_UNSET', 'T_ISSET', 'T_EMPTY',
        'T_CLASS', 'T_EXTENDS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_ARROW',
        'T_LIST', 'T_ARRAY', 'T_CLASS_C', 'T_FUNC_C', 'T_LINE', 'T_FILE',
        'T_COMMENT', 'T_ML_COMMENT', 'T_OPEN_TAG', 'T_OPEN_TAG_WITH_ECHO',
        'T_CLOSE_TAG', 'T_WHITESPACE', 'T_START_HEREDOC', 'T_END_HEREDOC',
        'T_DOLLAR_OPEN_CURLY_BRACES', 'T_CURLY_OPEN', 'T_PAAMAYIM_NEKUDOTAYIM',
        'T_DOUBLE_COLON', 'MSG_IPC_NOWAIT', 'MSG_NOERROR', 'MSG_EXCEPT',
        'CONNECTION_ABORTED', 'CONNECTION_NORMAL', 'CONNECTION_TIMEOUT',
        'INI_USER', 'INI_PERDIR', 'INI_SYSTEM', 'INI_ALL', 'M_E', 'M_LOG2E',
        'M_LOG10E', 'M_LN2', 'M_LN10', 'M_PI', 'M_PI_2', 'M_PI_4', 'M_1_PI',
        'M_2_PI', 'M_2_SQRTPI', 'M_SQRT2', 'M_SQRT1_2', 'INF', 'NAN',
        'INFO_GENERAL', 'INFO_CREDITS', 'INFO_CONFIGURATION', 'INFO_MODULES',
        'INFO_ENVIRONMENT', 'INFO_VARIABLES', 'INFO_LICENSE', 'INFO_ALL',
        'CREDITS_GROUP', 'CREDITS_GENERAL', 'CREDITS_SAPI', 'CREDITS_MODULES',
        'CREDITS_DOCS', 'CREDITS_FULLPAGE', 'CREDITS_QA', 'CREDITS_ALL',
        'HTML_SPECIALCHARS', 'HTML_ENTITIES', 'ENT_COMPAT', 'ENT_QUOTES',
        'ENT_NOQUOTES', 'STR_PAD_LEFT', 'STR_PAD_RIGHT', 'STR_PAD_BOTH',
        'PATHINFO_DIRNAME', 'PATHINFO_BASENAME', 'PATHINFO_EXTENSION',
        'CHAR_MAX', 'LC_CTYPE', 'LC_NUMERIC', 'LC_TIME', 'LC_COLLATE',
        'LC_MONETARY', 'LC_ALL', 'LC_MESSAGES', 'SEEK_SET', 'SEEK_CUR',
        'SEEK_END', 'LOCK_SH', 'LOCK_EX', 'LOCK_UN', 'LOCK_NB',
        'STREAM_NOTIFY_CONNECT', 'STREAM_NOTIFY_AUTH_REQUIRED',
        'STREAM_NOTIFY_AUTH_RESULT', 'STREAM_NOTIFY_MIME_TYPE_IS',
        'STREAM_NOTIFY_FILE_SIZE_IS', 'STREAM_NOTIFY_REDIRECTED',
        'STREAM_NOTIFY_PROGRESS', 'STREAM_NOTIFY_FAILURE',
        'STREAM_NOTIFY_SEVERITY_INFO', 'STREAM_NOTIFY_SEVERITY_WARN',
        'STREAM_NOTIFY_SEVERITY_ERR', 'FNM_NOESCAPE', 'FNM_PATHNAME',
        'FNM_PERIOD', 'FNM_CASEFOLD', 'ABDAY_1', 'ABDAY_2', 'ABDAY_3',
        'ABDAY_4', 'ABDAY_5', 'ABDAY_6', 'ABDAY_7', 'DAY_1', 'DAY_2', 'DAY_3',
        'DAY_4', 'DAY_5', 'DAY_6', 'DAY_7', 'ABMON_1', 'ABMON_2', 'ABMON_3',
        'ABMON_4', 'ABMON_5', 'ABMON_6', 'ABMON_7', 'ABMON_8', 'ABMON_9',
        'ABMON_10', 'ABMON_11', 'ABMON_12', 'MON_1', 'MON_2', 'MON_3', 'MON_4',
        'MON_5', 'MON_6', 'MON_7', 'MON_8', 'MON_9', 'MON_10', 'MON_11',
        'MON_12', 'AM_STR', 'PM_STR', 'D_T_FMT', 'D_FMT', 'T_FMT',
        'T_FMT_AMPM', 'ERA', 'ERA_D_T_FMT', 'ERA_D_FMT', 'ERA_T_FMT',
        'ALT_DIGITS', 'CRNCYSTR', 'RADIXCHAR', 'THOUSEP', 'YESEXPR', 'NOEXPR',
        'CODESET', 'CRYPT_SALT_LENGTH', 'CRYPT_STD_DES', 'CRYPT_EXT_DES',
        'CRYPT_MD5', 'CRYPT_BLOWFISH', 'DIRECTORY_SEPARATOR', 'PATH_SEPARATOR',
        'GLOB_BRACE', 'GLOB_MARK', 'GLOB_NOSORT', 'GLOB_NOCHECK',
        'GLOB_NOESCAPE', 'GLOB_ONLYDIR', 'LOG_EMERG', 'LOG_ALERT', 'LOG_CRIT',
        'LOG_ERR', 'LOG_WARNING', 'LOG_NOTICE', 'LOG_INFO', 'LOG_DEBUG',
        'LOG_KERN', 'LOG_USER', 'LOG_MAIL', 'LOG_DAEMON', 'LOG_AUTH',
        'LOG_SYSLOG', 'LOG_LPR', 'LOG_NEWS', 'LOG_UUCP', 'LOG_CRON',
        'LOG_AUTHPRIV', 'LOG_LOCAL0', 'LOG_LOCAL1', 'LOG_LOCAL2', 'LOG_LOCAL3',
        'LOG_LOCAL4', 'LOG_LOCAL5', 'LOG_LOCAL6', 'LOG_LOCAL7', 'LOG_PID',
        'LOG_CONS', 'LOG_ODELAY', 'LOG_NDELAY', 'LOG_NOWAIT', 'LOG_PERROR',
        'EXTR_OVERWRITE', 'EXTR_SKIP', 'EXTR_PREFIX_SAME', 'EXTR_PREFIX_ALL',
        'EXTR_PREFIX_INVALID', 'EXTR_PREFIX_IF_EXISTS', 'EXTR_IF_EXISTS',
        'EXTR_REFS', 'SORT_ASC', 'SORT_DESC', 'SORT_REGULAR', 'SORT_NUMERIC',
        'SORT_STRING', 'SORT_LOCALE_STRING', 'CASE_LOWER', 'CASE_UPPER',
        'COUNT_NORMAL', 'COUNT_RECURSIVE', 'ASSERT_ACTIVE', 'ASSERT_CALLBACK',
        'ASSERT_BAIL', 'ASSERT_WARNING', 'ASSERT_QUIET_EVAL',
        'STREAM_USE_PATH', 'STREAM_IGNORE_URL', 'STREAM_ENFORCE_SAFE_MODE',
        'STREAM_REPORT_ERRORS', 'STREAM_MUST_SEEK', 'IMAGETYPE_GIF',
        'IMAGETYPE_JPEG', 'IMAGETYPE_PNG', 'IMAGETYPE_SWF', 'IMAGETYPE_PSD',
        'IMAGETYPE_BMP', 'IMAGETYPE_TIFF_II', 'IMAGETYPE_TIFF_MM',
        'IMAGETYPE_JPC', 'IMAGETYPE_JP2', 'IMAGETYPE_JPX', 'IMAGETYPE_JB2',
        'IMAGETYPE_SWC', 'IMAGETYPE_IFF', 'IMAGETYPE_WBMP',
        'IMAGETYPE_JPEG2000', 'IMAGETYPE_XBM', 'AF_UNIX', 'AF_INET',
        'SOCK_STREAM', 'SOCK_DGRAM', 'SOCK_RAW', 'SOCK_SEQPACKET', 'SOCK_RDM',
        'MSG_OOB', 'MSG_WAITALL', 'MSG_PEEK', 'MSG_DONTROUTE', 'SO_DEBUG',
        'SO_REUSEADDR', 'SO_KEEPALIVE', 'SO_DONTROUTE', 'SO_LINGER',
        'SO_BROADCAST', 'SO_OOBINLINE', 'SO_SNDBUF', 'SO_RCVBUF',
        'SO_SNDLOWAT', 'SO_RCVLOWAT', 'SO_SNDTIMEO', 'SO_RCVTIMEO', 'SO_TYPE',
        'SO_ERROR', 'SOL_SOCKET', 'SOMAXCONN', 'PHP_NORMAL_READ',
        'PHP_BINARY_READ', 'SOCKET_EPERM', 'SOCKET_ENOENT', 'SOCKET_EINTR',
        'SOCKET_EIO', 'SOCKET_ENXIO', 'SOCKET_E2BIG', 'SOCKET_EBADF',
        'SOCKET_EAGAIN', 'SOCKET_ENOMEM', 'SOCKET_EACCES', 'SOCKET_EFAULT',
        'SOCKET_ENOTBLK', 'SOCKET_EBUSY', 'SOCKET_EEXIST', 'SOCKET_EXDEV',
        'SOCKET_ENODEV', 'SOCKET_ENOTDIR', 'SOCKET_EISDIR', 'SOCKET_EINVAL',
        'SOCKET_ENFILE', 'SOCKET_EMFILE', 'SOCKET_ENOTTY', 'SOCKET_ENOSPC',
        'SOCKET_ESPIPE', 'SOCKET_EROFS', 'SOCKET_EMLINK', 'SOCKET_EPIPE',
        'SOCKET_ENAMETOOLONG', 'SOCKET_ENOLCK', 'SOCKET_ENOSYS',
        'SOCKET_ENOTEMPTY', 'SOCKET_ELOOP', 'SOCKET_EWOULDBLOCK',
        'SOCKET_ENOMSG', 'SOCKET_EIDRM', 'SOCKET_ECHRNG', 'SOCKET_EL2NSYNC',
        'SOCKET_EL3HLT', 'SOCKET_EL3RST', 'SOCKET_ELNRNG', 'SOCKET_EUNATCH',
        'SOCKET_ENOCSI', 'SOCKET_EL2HLT', 'SOCKET_EBADE', 'SOCKET_EBADR',
        'SOCKET_EXFULL', 'SOCKET_ENOANO', 'SOCKET_EBADRQC', 'SOCKET_EBADSLT',
        'SOCKET_ENOSTR', 'SOCKET_ENODATA', 'SOCKET_ETIME', 'SOCKET_ENOSR',
        'SOCKET_ENONET', 'SOCKET_EREMOTE', 'SOCKET_ENOLINK', 'SOCKET_EADV',
        'SOCKET_ESRMNT', 'SOCKET_ECOMM', 'SOCKET_EPROTO', 'SOCKET_EMULTIHOP',
        'SOCKET_EBADMSG', 'SOCKET_ENOTUNIQ', 'SOCKET_EBADFD', 'SOCKET_EREMCHG',
        'SOCKET_ERESTART', 'SOCKET_ESTRPIPE', 'SOCKET_EUSERS',
        'SOCKET_ENOTSOCK', 'SOCKET_EDESTADDRREQ', 'SOCKET_EMSGSIZE',
        'SOCKET_EPROTOTYPE', 'SOCKET_ENOPROTOOPT', 'SOCKET_EPROTONOSUPPORT',
        'SOCKET_ESOCKTNOSUPPORT', 'SOCKET_EOPNOTSUPP', 'SOCKET_EPFNOSUPPORT',
        'SOCKET_EAFNOSUPPORT', 'SOCKET_EADDRINUSE', 'SOCKET_EADDRNOTAVAIL',
        'SOCKET_ENETDOWN', 'SOCKET_ENETUNREACH', 'SOCKET_ENETRESET',
        'SOCKET_ECONNABORTED', 'SOCKET_ECONNRESET', 'SOCKET_ENOBUFS',
        'SOCKET_EISCONN', 'SOCKET_ENOTCONN', 'SOCKET_ESHUTDOWN',
        'SOCKET_ETOOMANYREFS', 'SOCKET_ETIMEDOUT', 'SOCKET_ECONNREFUSED',
        'SOCKET_EHOSTDOWN', 'SOCKET_EHOSTUNREACH', 'SOCKET_EALREADY',
        'SOCKET_EINPROGRESS', 'SOCKET_EISNAM', 'SOCKET_EREMOTEIO',
        'SOCKET_EDQUOT', 'SOCKET_ENOMEDIUM', 'SOCKET_EMEDIUMTYPE', 'SOL_TCP',
        'SOL_UDP', 'MB_OVERLOAD_MAIL', 'MB_OVERLOAD_STRING',
        'MB_OVERLOAD_REGEX', 'MB_CASE_UPPER', 'MB_CASE_LOWER', 'MB_CASE_TITLE',
        'ICONV_IMPL', 'ICONV_VERSION', 'FTP_ASCII', 'FTP_TEXT', 'FTP_BINARY',
        'FTP_IMAGE', 'FTP_AUTORESUME', 'FTP_TIMEOUT_SEC', 'FTP_AUTOSEEK',
        'FTP_FAILED', 'FTP_FINISHED', 'FTP_MOREDATA', 'EXIF_USE_MBSTRING',
        'DBX_MYSQL', 'DBX_ODBC', 'DBX_PGSQL', 'DBX_MSSQL', 'DBX_FBSQL',
        'DBX_OCI8', 'DBX_SYBASECT', 'DBX_PERSISTENT', 'DBX_RESULT_INFO',
        'DBX_RESULT_INDEX', 'DBX_RESULT_ASSOC', 'DBX_COLNAMES_UNCHANGED',
        'DBX_COLNAMES_UPPERCASE', 'DBX_COLNAMES_LOWERCASE', 'DBX_CMP_NATIVE',
        'DBX_CMP_TEXT', 'DBX_CMP_NUMBER', 'DBX_CMP_ASC', 'DBX_CMP_DESC',
        'CAL_GREGORIAN', 'CAL_JULIAN', 'CAL_JEWISH', 'CAL_FRENCH',
        'CAL_NUM_CALS', 'CAL_DOW_DAYNO', 'CAL_DOW_SHORT', 'CAL_DOW_LONG',
        'CAL_MONTH_GREGORIAN_SHORT', 'CAL_MONTH_GREGORIAN_LONG',
        'CAL_MONTH_JULIAN_SHORT', 'CAL_MONTH_JULIAN_LONG', 'CAL_MONTH_JEWISH',
        'CAL_MONTH_FRENCH', 'CAL_EASTER_DEFAULT', 'CAL_EASTER_ROMAN',
        'CAL_EASTER_ALWAYS_GREGORIAN', 'CAL_EASTER_ALWAYS_JULIAN',
        'CAL_JEWISH_ADD_ALAFIM_GERESH', 'CAL_JEWISH_ADD_ALAFIM',
        'CAL_JEWISH_ADD_GERESHAYIM', 'FORCE_GZIP', 'FORCE_DEFLATE',
        'PREG_PATTERN_ORDER', 'PREG_SET_ORDER', 'PREG_OFFSET_CAPTURE',
        'PREG_SPLIT_NO_EMPTY', 'PREG_SPLIT_DELIM_CAPTURE',
        'PREG_SPLIT_OFFSET_CAPTURE', 'PREG_GREP_INVERT',
        'X509_PURPOSE_SSL_CLIENT', 'X509_PURPOSE_SSL_SERVER',
        'X509_PURPOSE_NS_SSL_SERVER', 'X509_PURPOSE_SMIME_SIGN',
        'X509_PURPOSE_SMIME_ENCRYPT', 'X509_PURPOSE_CRL_SIGN',
        'X509_PURPOSE_ANY', 'PKCS7_DETACHED', 'PKCS7_TEXT', 'PKCS7_NOINTERN',
        'PKCS7_NOVERIFY', 'PKCS7_NOCHAIN', 'PKCS7_NOCERTS', 'PKCS7_NOATTR',
        'PKCS7_BINARY', 'PKCS7_NOSIGS', 'OPENSSL_PKCS1_PADDING',
        'OPENSSL_SSLV23_PADDING', 'OPENSSL_NO_PADDING',
        'OPENSSL_PKCS1_OAEP_PADDING', 'OPENSSL_KEYTYPE_RSA',
        'OPENSSL_KEYTYPE_DSA', 'OPENSSL_KEYTYPE_DH', 'IMG_GIF', 'IMG_JPG',
        'IMG_JPEG', 'IMG_PNG', 'IMG_WBMP', 'IMG_XPM', 'IMG_COLOR_TILED',
        'IMG_COLOR_STYLED', 'IMG_COLOR_BRUSHED', 'IMG_COLOR_STYLEDBRUSHED',
        'IMG_COLOR_TRANSPARENT', 'IMG_ARC_ROUNDED', 'IMG_ARC_PIE',
        'IMG_ARC_CHORD', 'IMG_ARC_NOFILL', 'IMG_ARC_EDGED', 'IMG_GD2_RAW',
        'IMG_GD2_COMPRESSED', 'GD_BUNDLED', 'PGSQL_CONNECT_FORCE_NEW',
        'PGSQL_ASSOC', 'PGSQL_NUM', 'PGSQL_BOTH', 'PGSQL_CONNECTION_BAD',
        'PGSQL_CONNECTION_OK', 'PGSQL_SEEK_SET', 'PGSQL_SEEK_CUR',
        'PGSQL_SEEK_END', 'PGSQL_STATUS_LONG', 'PGSQL_STATUS_STRING',
        'PGSQL_EMPTY_QUERY', 'PGSQL_COMMAND_OK', 'PGSQL_TUPLES_OK',
        'PGSQL_COPY_OUT', 'PGSQL_COPY_IN', 'PGSQL_BAD_RESPONSE',
        'PGSQL_NONFATAL_ERROR', 'PGSQL_FATAL_ERROR',
        'PGSQL_CONV_IGNORE_DEFAULT', 'PGSQL_CONV_FORCE_NULL',
        'PGSQL_CONV_IGNORE_NOT_NULL', 'PGSQL_DML_NO_CONV', 'PGSQL_DML_EXEC',
        'PGSQL_DML_ASYNC', 'PGSQL_DML_STRING', 'MYSQL_ASSOC', 'MYSQL_NUM',
        'MYSQL_BOTH', 'MYSQL_CLIENT_COMPRESS', 'MYSQL_CLIENT_SSL',
        'MYSQL_CLIENT_INTERACTIVE', 'MYSQL_CLIENT_IGNORE_SPACE', 'APD_AF_UNIX',
        'APD_AF_INET', 'APD_FUNCTION_TRACE', 'APD_ARGS_TRACE',
        'APD_ASSIGNMENT_TRACE', 'APD_STATEMENT_TRACE', 'APD_MEMORY_TRACE',
        'APD_TIMING_TRACE', 'APD_SUMMARY_TRACE', 'APD_ERROR_TRACE',
        'APD_PROF_TRACE', 
    ), 'constant'); 
    
    $context->setCharactersDisallowedBeforeKeywords('$', '_');
    $context->setCharactersDisallowedAfterKeywords("'", '_');

    // PHP symbols
    $context->addSymbolGroup(array(
        '(', ')', ',', ';', ':', '[', ']',
        '+', '-', '*', '/', '&', '|', '!', '<', '>', '~',
        '{', '}', '=', '@', '?', '.'
    ), 'symbol');
    
    // PHP Variables
    // @todo [blocking 1.1.5] maybe later let the test string be a regex or something
    $context->addRegexGroup('#(\$\$?)([a-zA-Z_][a-zA-Z0-9_]*)#', '$',
        // This is the special bit ;)
        // For each bracket pair in the regex above, you can specify a name and optionally,
        // whether the matched part should be checked for being code first
        array(
            // index 1 is for the first bracket pair
            // so for PHP it's everything within the (...)
            // of course we could have specified the regex
            // as #$([a-zA-Z_][a-zA-Z0-9_]*)# (note the change
            // in place for the first open bracket), then the
            // name and style for this part would not include
            // the beginning $
            // Note:NEW AFTER 1.1.0a5: if second index of this array is true,
            // then you are saying: "Try to highlight as code first, if
            // it isn't code then use the styles in the second index".
            // This is really aimed at the OO support. For example, this
            // is some javascript code:
            //    foo.bar.toLowerCase();
            // The "bar" will be highlighted as an oo method, while the
            // "toLowerCase" will be highlighted as a keyword even though
            // it matches the oo method.
            1 => array('varstart', false),
            2 => array('var', false)
        )
    );
    
    // Number support
    $context->useStandardIntegers();
    $context->useStandardDoubles();
    
    // PHP Objects
    $context->addObjectSplitter('->', 'oodynamic', 'symbol');
    $context->addObjectSplitter('::', 'oostatic', 'symbol');

    $context->setComplexFlag(GESHI_COMPLEX_TOKENISE);
}

function geshi_php_single_string (&$context)
{
    $context->addDelimiters("'", "'");
    $context->addEscapeGroup('\\', array("'"));
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_php_double_string (&$context)
{
    $context->addDelimiters('"', '"');
    $context->addEscapeGroup('\\', array('n', 'r', 't', 'REGEX#[0-7]{1,3}#',
        'REGEX#x[0-9a-f]{1,2}#i', '"','$'));
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_php_heredoc (&$context)
{
    $context->addDelimiters("REGEX#<<<\s*([a-z][a-z0-9]*)\n#i", "REGEX#\n!!!1;?\n#i");
    $context->addEscapeGroup('\\', array('n', 'r', 't', 'REGEX#[0-7]{1,3}#',
        'REGEX#x[0-9a-f]{1,2}#i', '"', '$'));
    //$this->_contextStyleType = GESHI_STYLE_STRINGS;
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_php_single_comment (&$context)
{
    $context->addDelimiters(array('//', '#'), array("\n", '?>'));
    $context->parseDelimiters(GESHI_CHILD_PARSE_LEFT);
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_php_multi_comment (&$context)
{
    $context->addDelimiters('/*', '*/');
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_php_phpdoc_comment (&$context)
{
    $context->addDelimiters('/**', '*/');
    $context->addChild('tag');
    $context->addChild('link');
    $context->addChild('htmltag');
    $context->setComplexFlag(GESHI_COMPLEX_TOKENISE);
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_php_phpdoc_comment_tag (&$context)
{
    $context->addDelimiters('REGEX#(?<=[\s*])@#', 'REGEX#[^a-z]#');
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_php_phpdoc_comment_link (&$context)
{
    $context->addDelimiters('{@', '}');
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_php_phpdoc_comment_htmltag (&$context)
{
    $context->addDelimiters('REGEX#<[/a-z_0-6]+#i', '>');
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

/**#@-*/

?>
