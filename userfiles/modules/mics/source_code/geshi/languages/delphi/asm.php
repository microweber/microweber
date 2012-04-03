<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/delphi/asm.php
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

/**#@+
 * @access private
 */

require_once GESHI_LANGUAGES_ROOT . 'delphi' . GESHI_DIR_SEP . 'delphi.php';

function geshi_delphi_asm (&$context)
{
    $context->setComplexFlag(GESHI_COMPLEX_TOKENISE);

    $context->addChild('delphi/delphi/preprocessor', 'code');
    $context->addChild('delphi/delphi/single_comment');
    $context->addChild('delphi/delphi/multi_comment');

    //Assembler Directives
    $context->addKeywordGroup(array(
        'db','dd','dw'
    ), 'keyword');

    // Keyops
    $context->addKeywordGroup(array(
        'high', 'low', 'mod'
        //@done: Make ASM detect 'and', 'not', 'or', 'shl', 'shr', 'xor' if not used as instructions
        //The mentioned operators are now handled by the CodeParser and appear here for reference only.
    ), 'keyop');

    // Control
    $context->addKeywordGroup(array(
        'byte', 'dmtindex', 'dword', 'large', 'offset', 'ptr', 'qword', 'small',
        'tbyte', 'type', 'vmtoffset', 'word'
    ), 'control');

    // Registers
    $context->addKeywordGroup(array(
        'ah', 'al', 'bh', 'bl', 'ch', 'cl', 'dh', 'dl',
        'ax', 'bx', 'cx', 'dx', 'sp', 'bp', 'di', 'si',

        'eax', 'ebx', 'ecx', 'edx', 'esp', 'ebp', 'edi', 'esi',

        'mm0', 'mm1', 'mm2', 'mm3', 'mm4', 'mm5', 'mm6', 'mm7',
        'xmm0', 'xmm1', 'xmm2', 'xmm3', 'xmm4', 'xmm5', 'xmm6', 'xmm7',

        'st0', 'st1', 'st2', 'st3', 'st4', 'st5', 'st6', 'st7',

        'cs', 'ds', 'es', 'fs', 'gs', 'ss',
        'cr0', 'cr1', 'cr2', 'cr3', 'cr4',
        'dr0', 'dr1', 'dr2', 'dr3', 'dr4', 'dr5', 'dr6', 'dr7'
    ), 'register');
    $context->addRegexGroup('/(?=\b)(st\([0-7]\))/im', '', array(
        1 => array('register', false)
    ));

    // CPU i386 instructions
    $context->addKeywordGroup(array(
         // @todo order the i386 instruction set
         // @todo divide the i386 instruction set into i386\i486\i586\i686 instructions
        'AAA', 'AAD', 'AAM', 'AAS', 'ADC', 'ADD', 'AND', 'ARPL', 'BOUND', 'BSF',
        'BSR', 'BSWAP', 'BT', 'BTC', 'BTR', 'BTS', 'CALL', 'CBW', 'CDQ', 'CLC',
        'CLD', 'CLI', 'CLTS', 'CMC', 'cmova', 'cmovae', 'cmovb', 'cmovbe',
        'cmovc', 'cmovcxz', 'cmove', 'cmovg', 'cmovge', 'cmovl', 'cmovle',
        'cmovna', 'cmovnae', 'cmovnb', 'cmovnbe', 'cmovnc', 'cmovne', 'cmovng',
        'cmovnge', 'cmovnl', 'cmovnle', 'cmovno', 'cmovnp', 'cmovns', 'cmovnz',
        'cmovo', 'cmovp', 'cmovpe', 'cmovpo', 'cmovs', 'cmovz', 'CMP', 'CMPSB',
        'CMPSD', 'CMPSW', 'CMPXCHG', 'CMPXCHG8B', 'CMPXCHG486', 'CPUID', 'CWD',
        'CWDE', 'DAA', 'DAS', 'DEC', 'DIV', 'EMMS', 'ENTER', 'HLT', 'IBTS',
        'ICEBP', 'IDIV', 'IMUL', 'IN', 'INC', 'INSB', 'INSD', 'INSW', 'INT',
        'INT01', 'INT03', 'INT1', 'INT3', 'INTO', 'INVD', 'INVLPG', 'IRET',
        'IRETD', 'IRETW', 'ja', 'jae', 'jb', 'jbe', 'jc', 'jcxz', 'JCXZ', 'je',
        'JECXZ', 'jg', 'jge', 'jl', 'jle', 'JMP', 'jna', 'jnae', 'jnb', 'jnbe',
        'jnc', 'jne', 'jng', 'jnge', 'jnl', 'jnle', 'jno', 'jnp', 'jns', 'jnz',
        'jo', 'jp', 'jpe', 'jpo', 'js', 'jz', 'LAHF', 'LAR', 'LCALL', 'LDS',
        'LEA', 'LEAVE', 'LES', 'LFS', 'LGDT', 'LGS', 'LIDT', 'LJMP', 'LLDT',
        'LMSW', 'LOADALL', 'LOADALL286', 'LOCK', 'LODSB', 'LODSD', 'LODSW',
        'LOOP', 'LOOPE', 'LOOPNE', 'LOOPNZ', 'LOOPZ', 'LSL', 'LSS', 'LTR', 'MOV',
        'MOVD', 'MOVQ', 'MOVSB', 'MOVSD', 'MOVSW', 'MOVSX', 'MOVZX', 'MUL', 'NEG',
        'NOP', 'NOT', 'OR', 'OUT', 'OUTSB', 'OUTSD', 'OUTSW', 'POP', 'POPA',
        'POPAD', 'POPAW', 'POPF', 'POPFD', 'POPFW', 'PUSH', 'PUSHA', 'PUSHAD',
        'PUSHAW', 'PUSHF', 'PUSHFD', 'PUSHFW', 'RCL', 'RCR', 'RDMSR', 'RDPMC',
        'RDSHR', 'RDTSC', 'REP', 'REPE', 'REPNE', 'REPNZ', 'REPZ', 'RET', 'RETF',
        'RETN', 'ROL', 'ROR', 'RSDC', 'RSLDT', 'RSM', 'SAHF', 'SAL', 'SALC',
        'SAR', 'SBB', 'SCASB', 'SCASD', 'SCASW', 'seta', 'setae', 'setb', 'setbe',
        'setc', 'setcxz', 'sete', 'setg', 'setge', 'setl', 'setle', 'setna',
        'setnae', 'setnb', 'setnbe', 'setnc', 'setne', 'setng', 'setnge', 'setnl',
        'setnle', 'setno', 'setnp', 'setns', 'setnz', 'seto', 'setp', 'setpe',
        'setpo', 'sets', 'setz', 'SGDT', 'SHL', 'SHLD', 'SHR', 'SHRD', 'SIDT',
        'SLDT', 'SMI', 'SMINT', 'SMINTOLD', 'SMSW', 'STC', 'STD', 'STI', 'STOSB',
        'STOSD', 'STOSW', 'STR', 'SUB', 'SVDC', 'SVLDT', 'SVTS', 'SYSCALL',
        'SYSENTER', 'SYSEXIT', 'SYSRET', 'TEST', 'UD1', 'UD2', 'UMOV', 'VERR',
        'VERW', 'WAIT', 'WBINVD', 'WRMSR', 'WRSHR', 'XADD', 'XBTS', 'XCHG',
        'XLAT', 'XLATB', 'XOR'
    ), 'instr/i386');

    // FPU i387 instructions
    $context->addKeywordGroup(array(
         // @todo order the i387 instruction set
        'F2XM1', 'FABS', 'FADD', 'FADDP', 'FBLD', 'FBSTP', 'FCHS', 'FCLEX',
        'FCMOVB', 'FCMOVBE', 'FCMOVE', 'FCMOVNB', 'FCMOVNBE', 'FCMOVNE',
        'FCMOVNU', 'FCMOVU', 'FCOM', 'FCOMI', 'FCOMIP', 'FCOMP', 'FCOMPP', 'FCOS',
        'FDECSTP', 'FDISI', 'FDIV', 'FDIVP', 'FDIVR', 'FDIVRP', 'FEMMS', 'FENI',
        'FFREE', 'FIADD', 'FICOM', 'FICOMP', 'FIDIV', 'FIDIVR', 'FILD', 'FIMUL',
        'FINCSTP', 'FINIT', 'FIST', 'FISTP', 'FISUB', 'FISUBR', 'FLD', 'FLD1',
        'FLDCW', 'FLDENV', 'FLDL2E', 'FLDL2T', 'FLDLG2', 'FLDLN2', 'FLDPI',
        'FLDZ', 'FMUL', 'FMULP', 'FNCLEX', 'FNDISI', 'FNENI', 'FNINIT', 'FNOP',
        'FNSAVE', 'FNSTCW', 'FNSTENV', 'FNSTSW', 'FPATAN', 'FPREM', 'FPREM1',
        'FPTAN', 'FRNDINT', 'FRSTOR', 'FSAVE', 'FSCALE', 'FSETPM', 'FSIN',
        'FSINCOS', 'FSQRT', 'FST', 'FSTCW', 'FSTENV', 'FSTP', 'FSTSW', 'FSUB',
        'FSUBP', 'FSUBR', 'FSUBRP', 'FTST', 'FUCOM', 'FUCOMI', 'FUCOMIP',
        'FUCOMP', 'FUCOMPP', 'FWAIT', 'FXAM', 'FXCH', 'FXTRACT', 'FYL2X',
        'FYL2XP1'
    ), 'instr/i387');

    // MMX instruction set
    $context->addKeywordGroup(array(
        // @todo order the mmx instruction set
        // @todo divide into MMX and XMM instruction sets
        'FFREEP', 'FXRSTOR', 'FXSAVE', 'MASKMOVQ', 'MOVNTQ', 'PACKSSDW',
        'PACKSSWB', 'PACKUSWB', 'PADDB', 'PADDD', 'PADDSB', 'PADDSIW', 'PADDSW',
        'PADDUSB', 'PADDUSW', 'PADDW', 'PAND', 'PANDN', 'PAVEB', 'PAVGB', 'PAVGW',
        'PCMPEQB', 'PCMPEQD', 'PCMPEQW', 'PCMPGTB', 'PCMPGTD', 'PCMPGTW',
        'PDISTIB', 'PEXTRW', 'PFCMPEQ', 'PFCMPGE', 'PFCMPGT', 'PINSRW',
        'PMACHRIW', 'PMADDWD', 'PMAGW', 'PMAXSW', 'PMAXUB', 'PMINSW', 'PMINUB',
        'PMOVMSKB', 'PMULHUW', 'PMVGEZB', 'PMVLZB', 'PMVNZB', 'PMVZB', 'POR',
        'PREFETCHNTA', 'PREFETCHT0', 'PREFETCHT1', 'PREFETCHT2', 'PSADBW',
        'PSHUFW', 'PSLLD', 'PSLLQ', 'PSLLW', 'PSRAD', 'PSRAW', 'PSRLD', 'PSRLQ',
        'PSRLW', 'PSUBB', 'PSUBD', 'PSUBSB', 'PSUBSIW', 'PSUBSW', 'PSUBUSB',
        'PSUBUSW', 'PSUBW', 'PUNPCKHBW', 'PUNPCKHDQ', 'PUNPCKHWD', 'PUNPCKLBW',
        'PUNPCKLDQ', 'PUNPCKLWD', 'PXOR', 'SFENCE'
    ), 'instr/mmx');

    // SSE instruction set
    $context->addKeywordGroup(array(
        // @todo order the SSE instruction set
        // @todo divide between SSE\SSE2\SSE3 instruction sets
        'ADDPS', 'ADDSS', 'ANDNPS', 'ANDPS', 'CMPEQPS', 'CMPEQSS', 'CMPLEPS',
        'CMPLESS', 'CMPLTPS', 'CMPLTSS', 'CMPNEQPS', 'CMPNEQSS', 'CMPNLEPS',
        'CMPNLESS', 'CMPNLTPS', 'CMPNLTSS', 'CMPORDPS', 'CMPORDSS', 'CMPPS',
        'CMPSS', 'CMPUNORDPS', 'CMPUNORDSS', 'COMISS', 'CVTPI2PS', 'CVTPS2PI',
        'CVTSI2SS', 'CVTSS2SI', 'CVTTPS2PI', 'CVTTSS2SI', 'DIVPS', 'DIVSS',
        'LDMXCSR', 'MAXPS', 'MAXSS', 'MINPS', 'MINSS', 'MOVAPS', 'MOVHLPS',
        'MOVHPS', 'MOVLHPS', 'MOVLPS', 'MOVMSKPS', 'MOVNTPS', 'MOVSS', 'MOVUPS',
        'MULPS', 'MULSS', 'ORPS', 'RCPPS', 'RCPSS', 'RSQRTPS', 'RSQRTSS',
        'SHUFPS', 'SQRTPS', 'SQRTSS', 'STMXCSR', 'SUBPS', 'SUBSS', 'UCOMISS',
        'UNPCKHPS', 'UNPCKLPS', 'XORPS'
    ), 'instr/sse');

    // 3DNow instruction set
    $context->addKeywordGroup(array(
        // @todo order the 3Dnow! instruction set
        'PAVGUSB', 'PF2ID', 'PFACC', 'PFADD', 'PFMAX', 'PFMIN', 'PFMUL', 'PFRCP',
        'PFRCPIT1', 'PFRCPIT2', 'PFRSQIT1', 'PFRSQRT', 'PFSUB', 'PFSUBR', 'PI2FD',
        'PMULHRIW', 'PMULHRWA', 'PMULHRWC', 'PMULHW', 'PMULLW', 'PREFETCH',
        'PREFETCHW'
    ), 'instr/3Dnow');

    // 3DNowExt instruction set
    $context->addKeywordGroup(array(
        // @todo order the 3Dnow! Ext instruction set
        'PF2IW', 'PFNACC', 'PFPNACC', 'PI2FW', 'PSWAPD'
    ), 'instr/3Dnow2');

    // @todo Split into the same subgroups like for delphi/delphi
    $context->addSymbolGroup(array(
        ',', ';', '[', ']', '(', ')', '.', '&', '+', '-', '/', '*'
    ), 'symbol');

    $context->addObjectSplitter('.', 'oodynamic', 'symbol');

    $context->addRegexGroup('#([@a-zA-Z_][@a-zA-Z0-9_]+:)#', ':', array(
        1 => array('label', false)
    ));
    $context->addRegexGroup('#(@@[@a-zA-Z0-9_]+)#', '@@', array(
        1 => array('label', false)
    ));
    $context->addRegexGroup('/(\$[0-9a-fA-F_]+)/', '$', array(
        1 => array('hex', false)
    ));

    $context->useStandardIntegers();
    $context->useStandardDoubles(array('require_leading_number' => true));

    geshi_delphi_keyident_self($context);

    geshi_delphi_stdprocs($context);
}

/**#@-*/

?>
