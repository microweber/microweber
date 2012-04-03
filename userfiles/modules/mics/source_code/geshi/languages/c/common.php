<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/c/common.php
 *   Author: Netocrat
 *   E-mail: N/A
 * </pre>
 *
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 *
 *  This file is part of GeSHi.
 *
 *  GeSHi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  GeSHi is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with GeSHi; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * You can view a copy of the GNU GPL in the COPYING file that comes
 * with GeSHi, in the docs/ directory.
 *
 * @package    geshi
 * @subpackage lang
 * @author     http://clc-wiki.net/wiki/User:Netocrat
 * @link       http://clc-wiki.net/wiki/Development:GeSHi_C Bug reports
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2006 Netocrat
 * @version    1.1.2alpha3
 *
 */

/**#@+
 * @access private
 */

/**
 * This file keeps all data for C highlighting in one spot to avoid it being
 * split across languages/c/c.php and languages/c/class.geshiccodeparser.php.
 */

/** Get the GeSHiSingleCharContext class */
require_once GESHI_CLASSES_ROOT . 'class.geshisinglecharcontext.php';

function geshi_c_get_base_url()
{
    return 'http://clc-wiki.net/wiki/';
}

/**
 * Aside from those containing unusual symbols such as hash or underscore,
 * this url can be used for all tokens - the wiki will redirect to any more
 * specific location as required.
 */
function geshi_c_get_default_url($token = '{FNAME}')
{
    return geshi_c_get_base_url().$token;
}

/** Control-flow keywords (complete list) */
function geshi_c_get_ctlflow_keywords()
{
    return array(
        'break', 'case', 'continue', 'default', 'do', 'else', 'for', 'goto',
        'if', 'return', 'switch', 'while'
    );
}
function geshi_c_get_ctlflow_keywords_url($token = '{FNAME}')
{
    return geshi_c_get_default_url($token);
}

/** Declaration and type-related keywords (complete list) */
function geshi_c_get_declarator_keywords()
{
    return array('enum', 'struct', 'typedef', 'union');
}
function geshi_c_get_declarator_keywords_url()
{
    return geshi_c_get_default_url();
}

/** Types and qualifiers (need to verify as complete list but probably not) */
function geshi_c_get_types_and_qualifiers()
{
    return array(
        'auto', '_Bool', 'char', 'clock_t', '_Complex', 'const', 'div_t',
        'double', 'extern', 'FILE', 'float', 'fpos_t', '_Imaginary', 'inline',
        'int', 'jmp_buf', 'ldiv_t', 'long', 'ptrdiff_t', 'register',
        'restrict', 'short', 'signal', 'signed', 'size_t', 'static', 'string',
        'time_t', 'tm'/*a struct*/, 'unsigned', 'va_list', 'void', 'volatile',
        'wchar_t'
    );
}
function geshi_c_get_types_and_qualifiers_url()
{
    return geshi_c_get_default_url();
}

/** Standard library functions (need to verify whether comprehensive) */
function geshi_c_get_standard_functions()
{
    return array(
        'abort', 'abs', 'acos', 'asctime', 'asin', 'assert', 'atan',
        'atan2', 'atexit', 'atof', 'atoi', 'atol', 'bsearch', 'calloc',
        'ceil', 'clearerr', 'clock', 'cos', 'cosh', 'ctime', 'difftime',
        'div', 'exit', 'exp', 'fabs', 'fclose', 'feof', 'ferror',
        'fflush', 'fgetc', 'fgetpos', 'fgets', 'floor', 'fmod', 'fopen',
        'fprintf', 'fputc', 'fputs', 'fread', 'free', 'freopen', 'frexp',
        'fscanf', 'fseek', 'fsetpos', 'ftell', 'fwrite', 'getc',
        'getchar', 'getenv', 'gets', 'gmtime', 'isalnum', 'isalpha',
        'iscntrl', 'isdigit', 'isgraph', 'islower', 'isprint', 'ispunct',
        'isspace', 'isupper', 'isxdigit', 'labs', 'ldexp', 'ldiv',
        'localtime', 'log', 'log10', 'longjmp', 'main'/*user-supplied but
        fits best in this grouping*/, 'malloc', 'memchr',
        'memcmp', 'memcpy', 'memmove', 'memset', 'mktime', 'modf',
        'offsetof', 'perror', 'pow', 'printf', 'putc', 'putchar', 'puts',
        'qsort', 'raise', 'rand', 'realloc', 'remove', 'rename', 'rewind',
        'scanf', 'setbuf', 'setjmp', 'setvbuf', 'sin', 'sinh',
        'sizeof'/*actually an operator rather than a function (except for
         C99 VLAs) but fits best here*/, 'snprintf', 'sprintf', 'sqrt',
        'srand', 'sscanf', 'strcat', 'strchr', 'strcmp', 'strcoll',
        'strcpy', 'strcspn', 'strerror', 'strftime', 'strlen', 'strncat',
        'strncmp', 'strncpy', 'strpbrk', 'strrchr', 'strspn', 'strstr',
        'strtod', 'strtok', 'strtol', 'strtoul', 'strxfrm', 'system',
        'tan', 'tanh', 'time', 'tmpfile', 'tmpnam', 'tolower', 'toupper',
        'ungetc', 'va_arg', 'va_end', 'va_start', 'vfprintf', 'vfscanf',
        'vprintf', 'vscanf', 'vsnprintf', 'vsprintf', 'vsscanf'
    );
}
function geshi_c_get_standard_functions_url()
{
    return geshi_c_get_default_url();
}

/**
 * Standard macros and objects (not comprehensive - many missing; NB in
 * particular the macros in 7.8.1 and 7.18 in N1124 e.g. INT8_C)
 */
function geshi_c_get_standard_macros_and_objects()
{
    return array(
        'BUFSIZ', 'CHAR_BIT', 'CHAR_MAX', 'CHAR_MIN', 'CLOCKS_PER_SEC',
        '__DATE__', 'DBL_DIG', 'DBL_EPSILON', 'DBL_MANT_DIG', 'DBL_MAX',
        'DBL_MAX_EXP', 'DBL_MIN', 'DBL_MIN_EXP', 'EDOM', 'EOF', 'ERANGE',
        'errno', 'EXIT_FAILURE', 'EXIT_SUCCESS', 'false', '__FILE__',
        'FILENAME_MAX', 'FLT_DIG', 'FLT_EPSILON', 'FLT_MANT_DIG',
        'FLT_MAX', 'FLT_MAX_EXP', 'FLT_MIN', 'FLT_MIN_EXP', 'FLT_RADIX',
        'FLT_ROUNDS', 'FOPEN_MAX', 'HUGE_VAL', 'INT_MAX', 'INT_MIN',
        'LDBL_DIG', 'LDBL_EPSILON', 'LDBL_MANT_DIG', 'LDBL_MAX',
        'LDBL_MAX_EXP', 'LDBL_MIN', 'LDBL_MIN_EXP', '__LINE__', 'LONG_MAX',
        'LONG_MIN', 'L_tmpnam', 'NULL', 'RAND_MAX', 'SCHAR_MAX',
        'SCHAR_MIN', 'SEEK_CUR', 'SEEK_END', 'SEEK_SET', 'SHRT_MAX',
        'SHRT_MIN', 'SIGABRT', 'SIG_DFL', 'SIG_ERR', 'SIGFPE', 'SIG_IGN',
        'SIGILL', 'SIGINT', 'SIGSEGV', 'SIGTERM', '__STDC__',
        '__STDC_HOSTED__', '__STDC_IEC_559__', '__STDC_IEC_559_COMPLEX__',
        '__STDC_VERSION__', '__STDC_ISO_10646__', 'stderr', 'stdin',
        'stdout', '__TIME__', 'TMP_MAX', 'true', 'UCHAR_MAX', 'UINT_MAX',
        'ULONG_MAX', 'USHRT_MAX'
    );
}
function geshi_c_get_standard_macros_and_objects_url()
{
    return geshi_c_get_default_url();
}

/** Standard C headers (comprehensive list from N1124) */
function geshi_c_get_standard_headers()
{
    return array(
        'assert.h', 'complex.h', 'ctype.h', 'errno.h', 'fenv.h', 'float.h',
        'inttypes.h', 'iso646.h', 'locale.h', 'limits.h', 'math.h',
        'setjmp.h', 'signal.h', 'stdarg.h', 'stdbool.h', 'stddef.h',
        'stdint.h', 'stdio.h', 'stdlib.h', 'string.h', 'tgmath.h',
        'time.h', 'wchar.h', 'wctype.h'
    );
}
function geshi_c_get_standard_headers_url($token = '{FNAME}')
{
    return geshi_c_get_default_url($token);
}

/**
 * Start-of-line preprocessor directives preceded by a hash (comprehensive list)
 */
function geshi_c_get_start_of_line_PP_directives_hashsym()
{
    return array(
        'define', 'endif', 'elif', 'else', 'error', 'if', 'ifdef', 'ifndef',
        'include', 'line', 'pragma', 'undef'
    );
}
function geshi_c_get_start_of_line_PP_directives_hashsym_url($token = '{FNAME}')
{
    return "http://clc-wiki.net/wiki/hash_$token";
}

/**
 * Start-of-line preprocessor directives not preceded by a hash (comprehensive
 * list)
 */
function geshi_c_get_start_of_line_PP_directives_nohashsym()
{
    return array('_Pragma');
}
function geshi_c_get_start_of_line_PP_directives_nohashsym_url($token='{FNAME}')
{
    return "http://clc-wiki.net/wiki/underscore$token";
}

/**
 * The if and elif start-of-line preprocessor directives.
 */
function geshi_c_get_if_elif_PP_directives()
{
    return array('if', 'elif');
}

/**
 * Preprocessor directives for which control-flow-keyword highlighting should
 * be enabled.
 */
function geshi_c_get_PP_directives_needing_ctrl_flow_keywd_hlt()
{
    return array('define', 'if', 'elif', 'ifdef', 'ifndef', 'undef');
}

/**
 * Non-start-of-line preprocessor directives - only one - that occur in #if and
 * #elsif directives
 */
function geshi_c_get_if_elif_PP_subdirectives()
{
    return array('defined');
}
function geshi_c_get_if_elif_PP_subdirectives_url($token = '{FNAME}')
{
    return geshi_c_get_default_url($token);
}

/**
 * The url for non-standard preprocessor directives, used only in
 * class.geshiccodeparser.php
 */
function geshi_c_get_non_std_preproc_directives_url()
{
    return 'http://www.clc-wiki.net/wiki/Non-standard preprocessor directives';
}

/** Standard C symbols (need to verify whether this list is complete) */
function geshi_c_get_standard_symbols()
{
    return array(
     ',', '.', '?', ':', '>', '<', '~', '!', '=', '%', '^', '+', '-', '/', '*',
     '&', '(', ')', '{', '}', '[', ']', ';', '\\'/*line continuation character*/
    );
}

/** The symbols (list of one) that are limited to the preprocessor. */
function geshi_c_get_preprocessor_symbols()
{
    return array('#');
}

/** C tokens that access structure members */
function geshi_c_get_structure_access_symbols()
{
    return array('.', '->');
}

/**#@-*/

?>
