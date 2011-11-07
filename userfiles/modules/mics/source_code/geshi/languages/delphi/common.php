<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/delphi/common.php
 *   Author: Benny Baumann
 *   E-mail: BenBE@benbe.omorphia.de
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
 * @author     Benny Baumann <BenBE@benbe.omorphia.de>, Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2005 - 2006 Benny Baumann, Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/**#@+
 * @access private
 */

function geshi_delphi_common(&$context)
{
    $context->addChild('single_comment');
    $context->addChild('multi_comment');
    $context->addChild('single_string', 'string');

}

function geshi_delphi_chars(&$context)
{
    // Characters
    $context->addRegexGroup('/(#[0-9]+)/', '#', array(
        1 => array('char', false)
    ));
    $context->addRegexGroup('/(#\$[0-9a-fA-F]+)/', '#', array(
        1 => array('charhex', false)
    ));
}

function geshi_delphi_integers(&$context)
{
    // Integers
    $context->addRegexGroup('/(\$[0-9a-fA-F]+)/', '$', array(
        1 => array('hex', false)
    ));
    $context->useStandardIntegers();
}

function geshi_delphi_keytype(&$context)
{
    // Keytypes
    $context->addKeywordGroup(array(
        'AnsiChar', 'AnsiString', 'Bool', 'Boolean', 'Byte', 'ByteBool', 'Cardinal', 'Char',
        'Comp', 'Currency', 'DWORD', 'Double', 'Extended', 'Int64', 'Integer', 'IUnknown',
        'LongBool', 'LongInt', 'LongWord', 'PAnsiChar', 'PAnsiString', 'PBool', 'PBoolean', 'PByte',
        'PByteArray', 'PCardinal', 'PChar', 'PComp', 'PCurrency', 'PDWORD', 'PDate', 'PDateTime',
        'PDouble', 'PExtended', 'PInt64', 'PInteger', 'PLongInt', 'PLongWord', 'Pointer', 'PPointer',
        'PShortInt', 'PShortString', 'PSingle', 'PSmallInt', 'PString', 'PHandle', 'PVariant', 'PWord',
        'PWordArray', 'PWordBool', 'PWideChar', 'PWideString', 'Real', 'Real48', 'ShortInt', 'ShortString',
        'Single', 'SmallInt', 'String', 'TClass', 'TDate', 'TDateTime', 'TextFile', 'THandle',
        'TObject', 'TTime', 'Variant', 'WideChar', 'WideString', 'Word', 'WordBool'
    ), 'keytype');
}

function geshi_delphi_keyident_noself(&$context)
{
    // Keyidents
    $context->addKeywordGroup(array(
        'false', 'nil', 'true'
    ), 'keyident');
}

function geshi_delphi_keyident_self(&$context)
{
    // Keyidents
    $context->addKeywordGroup(array(
        'false', 'nil', 'result', 'self', 'true'
    ), 'keyident');
}

function geshi_delphi_single_comment (&$context)
{
    $context->addDelimiters('//', "\n");
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_delphi_multi_comment (&$context)
{
    $context->addDelimiters('REGEX#\{(?!\$)#im', '}');
    $context->addDelimiters('REGEX#\(\*(?!\$)#im', '*)');
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
}

function geshi_delphi_single_string (&$context)
{
    $context->addDelimiters("'", array("'", "\n"));
    $context->addEscapeGroup("'");
}

function geshi_delphi_preprocessor (&$context)
{
    $context->addDelimiters('REGEX#\{(?=\$)#im', '}');
    $context->addDelimiters('REGEX#\(\*(?=\$)#im', '*)');
//    $context->addDelimiters('{$', '}');
//    $context->addDelimiters('(*$', '*)');

    $context->useStandardIntegers();
    // @note need third parameter here, changes function called
    // from geshi_delphi_delphi_preprocessor_single_string
    // and geshi_delphi_preprocessor_single_string
    // to geshi_delphi_single_string
    $context->addChild('single_string', 'string', 'single_string');

    $context->addKeywordGroup(array(
        '$A-', '$A+', '$A1', '$A2', '$A4',
        '$A8',                  //Delphi 7 up, named here for completenes
        '$B-', '$B+',
        '$C-', '$C+',
        '$D-', '$D+',
        '$E',
        '$G-', '$G+',
        '$H-', '$H+',
        '$I', '$I-', '$I+',
        '$J-', '$J+',
        '$L', '$L-', '$L+',
        '$M',                   //Plattform dependend, see below
        '$O-', '$O+',
        '$P-', '$P+',
        '$Q-', '$Q+',
        '$R', '$R-', '$R+',
        '$T-', '$T+',
        '$U-', '$U+',
        '$V-', '$V+',
        '$W-', '$W+',
        '$X-', '$X+',
        '$Y-', '$Y+', '$YD',
        '$Z1', '$Z2', '$Z4',

        '$ALIGN',               //alias $A
        '$APPTYPE',
        '$ASSERTIONS',          //alias $C
        '$BOOLEVAL',            //alias $B
        '$DEBUGINFO',           //alias $D
        '$DEFINE',
        '$DEFINITIONINFO',      //alias $YD
        '$DENYPACKAGEUNIT',
        '$DESCRIPTION',
        '$DESIGNONLY',
        '$ELSE',
        '$ELSEIF',              //Delphi 7 up, named here for completenes
        '$ENDIF',
        '$EXTENDEDSYNTAX',      //alias $X
        '$EXTENSION',           //alias $E
        '$EXTERNALSYM',
        '$HINTS',
        '$HPPEMIT',
        '$IF',                  //Delphi 7 up, named here for completenes
        '$IFDEF',
        '$IFNDEF',
        '$IFNOPT',
        '$IFOPT',
        '$IMAGEBASE',
        '$IMPLICITBUILD',
        '$IMPORTEDDATA',        //alias $G
        '$INCLUDE',             //alias $I
        '$IOCHECKS',            //alias $I
        '$LIBSUFFIX',
        '$LIBVERSION',
        '$LINK',                //alias $L
        '$LOCALSYMBOLS',        //alias $L
        '$LONGSTRINGS',         //alias $H
        '$MAXSTACKSIZE',        //alias $M (Windows only)
        '$MESSAGE',
        '$MINENUMSIZE',         //alias $Z1, $Z2 und $Z4
        '$MINSTACKSIZE',        //alias $M (Windows only)
        '$NODEFINE',
        '$NOINCLUDE',
        '$OBJEXPORTALL',
        '$OPENSTRINGS',         //alias $P
        '$OPTIMIZATION',        //alias $O
        '$OVERFLOWCHECKS',      //alias $Q
        '$RANGECHECKS',         //alias $R
        '$REALCOMPATIBILITY',
        '$REFERENCEINFO',       //alias $Y
        '$RESOURCE',            //alias $R
        '$RESOURCERESERVE',     //alias $M (Linux only)
        '$RUNONLY',
        '$SAFEDIVID',           //alias $U
        '$SOPREFIX',
        '$SOSUFFIX',
        '$SOVERSION',
        '$STACKCHECKS',
        '$STACKFRAMES',         //alias $W
        '$TYPEDADDRESS',        //alias $T
        '$TYPEINFO',            //alias $M
        '$UNDEF',
        '$VARSTRINGCHECKS',     //alias $V
        '$WARN',
        '$WARNINGS',
        '$WEAKPACKAGEUNIT',
        '$WRITEABLECONST',      //alias $J
    ), 'switch');
}

function geshi_delphi_stdprocs(&$context)
{
    // Standard functions of Unit System
    $context->addKeywordGroup(array(
        'Abs', 'AcquireExceptionObject', 'Addr', 'AnsiToUtf8', 'Append', 'ArcTan',
        'Assert', 'AssignFile', 'Assigned', 'BeginThread', 'BlockRead',
        'BlockWrite', 'Break', 'ChDir', 'Chr', 'Close', 'CloseFile',
        'CompToCurrency', 'CompToDouble', 'Concat', 'Continue', 'Copy', 'Cos',
        'Dec', 'Delete', 'Dispose', 'DoubleToComp', 'EndThread', 'EnumModules',
        'EnumResourceModules', 'Eof', 'Eoln', 'Erase', 'ExceptAddr',
        'ExceptObject', 'Exclude', 'Exit', 'Exp', 'FilePos', 'FileSize',
        'FillChar', 'Finalize', 'FindClassHInstance', 'FindHInstance',
        'FindResourceHInstance', 'Flush', 'Frac', 'FreeMem', 'Get8087CW',
        'GetDir', 'GetLastError', 'GetMem', 'GetMemoryManager',
        'GetModuleFileName', 'GetVariantManager', 'Halt', 'Hi', 'High',
        'IOResult', 'Inc', 'Include', 'Initialize', 'Insert', 'Int',
        'IsMemoryManagerSet', 'IsVariantManagerSet', 'Length', 'Ln', 'Lo', 'Low',
        'MkDir', 'Move', 'New', 'Odd', 'OleStrToStrVar', 'OleStrToString', 'Ord',
        'PUCS4Chars', 'ParamCount', 'ParamStr', 'Pi', 'Pos', 'Pred', 'Ptr',
        'Random', 'Randomize', 'Read', 'ReadLn', 'ReallocMem',
        'ReleaseExceptionObject', 'Rename', 'Reset', 'Rewrite', 'RmDir', 'Round',
        'RunError', 'Seek', 'SeekEof', 'SeekEoln', 'Set8087CW', 'SetLength',
        'SetLineBreakStyle', 'SetMemoryManager', 'SetString', 'SetTextBuf',
        'SetVariantManager', 'Sin', 'SizeOf', 'Slice', 'Sqr', 'Sqrt', 'Str',
        'StringOfChar', 'StringToOleStr', 'StringToWideChar', 'Succ', 'Swap',
        'Trunc', 'Truncate', 'TypeInfo', 'UCS4StringToWideString', 'UTF8Decode',
        'UTF8Encode', 'UnicodeToUtf8', 'UniqueString', 'UpCase', 'Utf8ToAnsi',
        'Utf8ToUnicode', 'Val', 'VarArrayRedim', 'VarClear',
        'WideCharLenToStrVar', 'WideCharLenToString', 'WideCharToStrVar',
        'WideCharToString', 'WideStringToUCS4String', 'Write', 'WriteLn'
    ), 'stdprocs/system');

    // Standard functions of Unit SysUtils
    $context->addKeywordGroup(array(
        'Abort', 'AddExitProc', 'AddTerminateProc', 'AdjustLineBreaks', 'AllocMem',
        'AnsiCompareFileName', 'AnsiCompareStr', 'AnsiCompareText',
        'AnsiDequotedStr', 'AnsiExtractQuotedStr', 'AnsiLastChar',
        'AnsiLowerCase', 'AnsiLowerCaseFileName', 'AnsiPos', 'AnsiQuotedStr',
        'AnsiSameStr', 'AnsiSameText', 'AnsiStrComp', 'AnsiStrIComp',
        'AnsiStrLComp', 'AnsiStrLIComp', 'AnsiStrLastChar', 'AnsiStrLower',
        'AnsiStrPos', 'AnsiStrRScan', 'AnsiStrScan', 'AnsiStrUpper',
        'AnsiUpperCase', 'AnsiUpperCaseFileName', 'AppendStr', 'AssignStr',
        'Beep', 'BoolToStr', 'ByteToCharIndex', 'ByteToCharLen', 'ByteType',
        'CallTerminateProcs', 'ChangeFileExt', 'CharLength', 'CharToByteIndex',
        'CharToByteLen', 'CompareMem', 'CompareStr', 'CompareText', 'CreateDir',
        'CreateGUID', 'CurrToStr', 'CurrToStrF', 'CurrentYear', 'Date',
        'DateTimeToFileDate', 'DateTimeToStr', 'DateTimeToString',
        'DateTimeToSystemTime', 'DateTimeToTimeStamp', 'DateToStr', 'DayOfWeek',
        'DecodeDate', 'DecodeDateFully', 'DecodeTime', 'DeleteFile',
        'DirectoryExists', 'DiskFree', 'DiskSize', 'DisposeStr', 'EncodeDate',
        'EncodeTime', 'ExceptionErrorMessage', 'ExcludeTrailingBackslash',
        'ExcludeTrailingPathDelimiter', 'ExpandFileName', 'ExpandFileNameCase',
        'ExpandUNCFileName', 'ExtractFileDir', 'ExtractFileDrive',
        'ExtractFileExt', 'ExtractFileName', 'ExtractFilePath',
        'ExtractRelativePath', 'ExtractShortPathName', 'FileAge', 'FileClose',
        'FileCreate', 'FileDateToDateTime', 'FileExists', 'FileGetAttr',
        'FileGetDate', 'FileIsReadOnly', 'FileOpen', 'FileRead', 'FileSearch',
        'FileSeek', 'FileSetAttr', 'FileSetDate', 'FileSetReadOnly', 'FileWrite',
        'FinalizePackage', 'FindClose', 'FindCmdLineSwitch', 'FindFirst',
        'FindNext', 'FloatToCurr', 'FloatToDateTime', 'FloatToDecimal',
        'FloatToStr', 'FloatToStrF', 'FloatToText', 'FloatToTextFmt',
        'FmtLoadStr', 'FmtStr', 'ForceDirectories', 'Format', 'FormatBuf',
        'FormatCurr', 'FormatDateTime', 'FormatFloat', 'FreeAndNil',
        'GUIDToString', 'GetCurrentDir', 'GetEnvironmentVariable',
        'GetFileVersion', 'GetFormatSettings', 'GetLocaleFormatSettings',
        'GetModuleName', 'GetPackageDescription', 'GetPackageInfo', 'GetTime',
        'IncAMonth', 'IncMonth', 'IncludeTrailingBackslash',
        'IncludeTrailingPathDelimiter', 'InitializePackage', 'IntToHex',
        'IntToStr', 'InterlockedDecrement', 'InterlockedExchange',
        'InterlockedExchangeAdd', 'InterlockedIncrement', 'IsDelimiter',
        'IsEqualGUID', 'IsLeapYear', 'IsPathDelimiter', 'IsValidIdent',
        'Languages', 'LastDelimiter', 'LoadPackage', 'LoadStr', 'LowerCase',
        'MSecsToTimeStamp', 'NewStr', 'NextCharIndex', 'Now', 'OutOfMemoryError',
        'QuotedStr', 'RaiseLastOSError', 'RaiseLastWin32Error', 'RemoveDir',
        'RenameFile', 'ReplaceDate', 'ReplaceTime', 'SafeLoadLibrary',
        'SameFileName', 'SameText', 'SetCurrentDir', 'ShowException', 'Sleep',
        'StrAlloc', 'StrBufSize', 'StrByteType', 'StrCat', 'StrCharLength',
        'StrComp', 'StrCopy', 'StrDispose', 'StrECopy', 'StrEnd', 'StrFmt',
        'StrIComp', 'StrLCat', 'StrLComp', 'StrLCopy', 'StrLFmt', 'StrLIComp',
        'StrLen', 'StrLower', 'StrMove', 'StrNew', 'StrNextChar', 'StrPCopy',
        'StrPLCopy', 'StrPas', 'StrPos', 'StrRScan', 'StrScan', 'StrToBool',
        'StrToBoolDef', 'StrToCurr', 'StrToCurrDef', 'StrToDate', 'StrToDateDef',
        'StrToDateTime', 'StrToDateTimeDef', 'StrToFloat', 'StrToFloatDef',
        'StrToInt', 'StrToInt64', 'StrToInt64Def', 'StrToIntDef', 'StrToTime',
        'StrToTimeDef', 'StrUpper', 'StringReplace', 'StringToGUID', 'Supports',
        'SysErrorMessage', 'SystemTimeToDateTime', 'TextToFloat', 'Time',
        'TimeStampToDateTime', 'TimeStampToMSecs', 'TimeToStr', 'Trim',
        'TrimLeft', 'TrimRight', 'TryEncodeDate', 'TryEncodeTime',
        'TryFloatToCurr', 'TryFloatToDateTime', 'TryStrToBool', 'TryStrToCurr',
        'TryStrToDate', 'TryStrToDateTime', 'TryStrToFloat', 'TryStrToInt',
        'TryStrToInt64', 'TryStrToTime', 'UnloadPackage', 'UpperCase',
        'WideCompareStr', 'WideCompareText', 'WideFmtStr', 'WideFormat',
        'WideFormatBuf', 'WideLowerCase', 'WideSameStr', 'WideSameText',
        'WideUpperCase', 'Win32Check', 'WrapText'
    ), 'stdprocs/sysutil');

    // Standard functions of Unit Classes
    $context->addKeywordGroup(array(
        'ActivateClassGroup', 'AllocateHwnd', 'BinToHex', 'CheckSynchronize',
        'CollectionsEqual', 'CountGenerations', 'DeallocateHwnd', 'EqualRect',
        'ExtractStrings', 'FindClass', 'FindGlobalComponent', 'GetClass',
        'GroupDescendantsWith', 'HexToBin', 'IdentToInt',
        'InitInheritedComponent', 'IntToIdent', 'InvalidPoint',
        'IsUniqueGlobalComponentName', 'LineStart', 'ObjectBinaryToText',
        'ObjectResourceToText', 'ObjectTextToBinary', 'ObjectTextToResource',
        'PointsEqual', 'ReadComponentRes', 'ReadComponentResEx',
        'ReadComponentResFile', 'Rect', 'RegisterClass', 'RegisterClassAlias',
        'RegisterClasses', 'RegisterComponents', 'RegisterIntegerConsts',
        'RegisterNoIcon', 'RegisterNonActiveX', 'SmallPoint', 'StartClassGroup',
        'TestStreamFormat', 'UnregisterClass', 'UnregisterClasses',
        'UnregisterIntegerConsts', 'UnregisterModuleClasses',
        'WriteComponentResFile'
    ), 'stdprocs/class');

    // Standard functions of Unit Math
    $context->addKeywordGroup(array(
        'ArcCos', 'ArcCosh', 'ArcCot', 'ArcCotH', 'ArcCsc', 'ArcCscH', 'ArcSec',
        'ArcSecH', 'ArcSin', 'ArcSinh', 'ArcTan2', 'ArcTanh', 'Ceil',
        'CompareValue', 'Cosecant', 'Cosh', 'Cot', 'CotH', 'Cotan', 'Csc', 'CscH',
        'CycleToDeg', 'CycleToGrad', 'CycleToRad', 'DegToCycle', 'DegToGrad',
        'DegToRad', 'DivMod', 'DoubleDecliningBalance', 'EnsureRange', 'Floor',
        'Frexp', 'FutureValue', 'GetExceptionMask', 'GetPrecisionMode',
        'GetRoundMode', 'GradToCycle', 'GradToDeg', 'GradToRad', 'Hypot',
        'InRange', 'IntPower', 'InterestPayment', 'InterestRate',
        'InternalRateOfReturn', 'IsInfinite', 'IsNan', 'IsZero', 'Ldexp', 'LnXP1',
        'Log10', 'Log2', 'LogN', 'Max', 'MaxIntValue', 'MaxValue', 'Mean',
        'MeanAndStdDev', 'Min', 'MinIntValue', 'MinValue', 'MomentSkewKurtosis',
        'NetPresentValue', 'Norm', 'NumberOfPeriods', 'Payment', 'PeriodPayment',
        'Poly', 'PopnStdDev', 'PopnVariance', 'Power', 'PresentValue',
        'RadToCycle', 'RadToDeg', 'RadToGrad', 'RandG', 'RandomRange', 'RoundTo',
        'SLNDepreciation', 'SYDDepreciation', 'SameValue', 'Sec', 'SecH',
        'Secant', 'SetExceptionMask', 'SetPrecisionMode', 'SetRoundMode', 'Sign',
        'SimpleRoundTo', 'SinCos', 'Sinh', 'StdDev', 'Sum', 'SumInt',
        'SumOfSquares', 'SumsAndSquares', 'Tan', 'Tanh', 'TotalVariance',
        'Variance'
    ), 'stdprocs/math');
}

/**#@-*/

?>
