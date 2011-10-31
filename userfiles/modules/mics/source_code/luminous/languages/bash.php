<?php

/*
 * XXX: I don't really know bash all that well. I don't know how the
 * interpolation rules work exactly, i.e. if
 * x=" $(  #   )"
 * is a left unterminated by the comment or if the comment terminates at the )
 * Kate things the latter and I'll go with it.
 * 
 */

class LuminousBashScanner extends LuminousScanner {

  public $interpolated = false;


  public static function string_filter($token) {
    $token = LuminousUtils::escape_token($token);
    $token[1] = preg_replace("/\\$(?:\w+|\\{[^}\n]+\\})/",
      '<VARIABLE>$0</VARIABLE>', $token[1]);
    return $token;
  }

  public function init() {
    $this->add_identifier_mapping('KEYWORD',  array('case', 'do', 'done',
    'elif', 'else', 'esac', 'fi', 'for', 'function', 'if', 'in', 'select',
    'then', 'time', 'until', 'while',
    'foreach', 'end' // zsh I think
    ));
    // I could ls /usr/bin, but i think this will do for now
    $this->add_identifier_mapping('FUNCTION', array('adduser', 'addgroup',
'alias', 'apropos', 'apt-get', 'aptitude', 'aspell', 'awk', 'basename', 'bash',
'bc', 'bg', 'break', 'builtin', 'bzip2', 'cal', 'case', 'cat', 'cd', 'cfdisk',
'chgrp', 'chmod', 'chown', 'chroot', 'chkconfig', 'cksum', 'clear', 'cmp',
'comm', 'command', 'continue', 'cp', 'cron', 'crontab', 'csplit', 'cut', 'date',
'dc', 'dd', 'ddrescue', 'declare', 'df', 'diff', 'diff3', 'dig', 'dir',
'dircolors', 'dirname', 'dirs', 'dmesg', 'du', 'echo', 'egrep', 'eject',
'enable', 'env', 'ethtool', 'eval', 'exec', 'exit', 'expect', 'expand',
'export', 'expr', 'false', 'fdformat', 'fdisk', 'fg', 'fgrep', 'file', 'find',
'fmt', 'fold', 'for', 'format', 'free', 'fsck', 'ftp', 'function', 'fuser',
'gawk', 'getopts', 'git', 'grep', 'groups', 'gzip', 'hash', 'head', 'help',
'history', 'hg', 'hostname', 'iconv', 'id', 'if', 'ifconfig', 'ifdown', 'ifup',
'import', 'install', 'jobs', 'join', 'kill', 'killall', 'less', 'let', 'ln',
'local', 'locate', 'logname', 'logout', 'look', 'lpc', 'lpr', 'lprint',
'lprintd', 'lprintq', 'lprm', 'ls', 'lsof', 'make', 'man', 'mkdir', 'mkfifo',
'mkisofs', 'mknod', 'more', 'mount', 'mtools', 'mtr', 'mv', 'mmv', 'nano',
'netstat', 'nice', 'nl', 'nohup', 'notify-send', 'nslookup', 'open', 'op',
'passwd', 'paste', 'pathchk', 'ping', 'pkill', 'popd', 'pr', 'printcap',
'printenv', 'printf', 'ps', 'pushd', 'pwd', 'quota', 'quotacheck', 'quotactl',
'ram', 'rcp', 'read', 'readarray', 'readonly', 'reboot', 'rename', 'renice',
'remsync', 'return', 'rev', 'rm', 'rmdir', 'rsync', 'screen', 'scp', 'sdiff',
'sed', 'select', 'seq', 'set', 'sftp', 'shift', 'shopt', 'shutdown', 'sleep',
'slocate', 'sort', 'source', 'split', 'ssh', 'strace', 'su', 'sudo', 'sum',
'suspend', 'svn', 'symlink', 'sync', 'tail', 'tar', 'tee', 'test', 'time',
'times', 'touch', 'top', 'traceroute', 'trap', 'tr', 'true', 'tsort', 'tty',
'type', 'ulimit', 'umask', 'umount', 'unalias', 'uname', 'unexpand', 'uniq',
'units', 'unset', 'unshar', 'until', 'useradd', 'usermod', 'users', 'uuencode',
'uudecode', 'v', 'vdir', 'vi', 'vim', 'vmstat', 'watch', 'wc', 'whereis',
'which', 'while', 'who', 'whoami', 'Wget', 'write', 'xargs', 'xdg-open',
'yes',));
    $this->remove_stream_filter('oo-syntax');
    $this->remove_filter('comment-to-doc');
    $this->add_filter('str-filter', 'STRING', array($this, 'string_filter'));
  }


  
  function main() {
    
    $stack = array();
    while(!$this->eos()) {

      $c = $this->peek();
  
      if ($this->scan('/\\$([{(])/')) {
        $this->record($this->match(), 'KEYWORD');
        $stack[] = array($this->match_group(1), true);
      }
      elseif($c === '[') {
        $this->record($this->get(), 'KEYWORD');
        $stack[] = array($c, true);
      }
      elseif ($c === '{' || $c === '(') {
        $this->record($this->get(), null);
        $stack[] = array($c, false);
      }
      elseif($c === '}' || $c === ')' || $c ===']') {
        $match = array('{'=>'}', '('=>')', '[' => ']');
        $type = null;
        if (isset($stack[0])) {
          $pop = array_pop($stack);
          if ($pop[1]) $type = 'KEYWORD';
          if ($match[$pop[0]] !== $c) {
             // err
            $stack[] = $pop;
            $type = null;
          }
        }
        $this->record($this->get(), $type);
        if (empty($stack) && $this->interpolated) {
          break;
        }
       
      }
      elseif($c === '`') {
        $this->record($this->get(), 'KEYWORD');
      }
      elseif ($this->scan('/
        \$( [_a-zA-Z]\w* | [\d\#*@\-!_\\?\\$])
        /xm')
        ) {
        $this->record($this->match(), 'VARIABLE');
      }
      elseif($this->scan('/^(\s*)([_a-zA-Z]\w*(?=[=]))/m')) {
        $m = $this->match_groups();
        if ($m[1] !== '') $this->record($m[1], null);
        $this->record($m[2], 'VARIABLE');

      }
      elseif (($this->interpolated && count($stack) === 1 &&
        $this->scan('/\#.*?(?=[)]|$)/m'))
        || $this->scan('/\#.*/')) {
        $this->record($this->match(), 'COMMENT');
      }
      elseif(($m = $this->scan("/\\$?'(?> [^'\\\\]+ | \\\\.)* '/sx"))) {
        $tok = ($m[0] === '$')? 'VARIABLE' : 'STRING';
        $this->record($m, $tok);
      }
      elseif($this->scan('/-*[a-zA-Z_][\-\w]*/')) {
        $this->record($this->match(), 'IDENT');
      }
      // quoted heredoc is the same as a single string, no interpolation,
      // A straight regex is causing backtracking problems on my box so 
      // we're going to do it the hard way
      // note that the <<- means the delimiter can be indented.
      elseif($this->scan('/(<<-?)(\s*)(["\'])(\w+)((?:\\3)?)/msx')) {
        $m = $this->match_groups();
        $this->record($m[1] . $m[2], null);
        $this->record($m[3] . $m[4] . $m[5], 'DELIMITER');
        $delim_regex = "/^(" . (($m[1] === '<<-')? '\s*' : '') 
          . ')(' . preg_quote($m[4], '/') . ')\\b/m';
        $heredoc = $this->scan_until($delim_regex);
        if ($heredoc === null) {
          $heredoc = $this->rest();
          $this->terminate();
        }
        $this->record($heredoc, 'HEREDOC');
        if ($this->scan($delim_regex) !== null) {
          $g = $this->match_groups();
          if ($g[1] !== '') $this->record($g[1], null);
          $this->record($g[2], 'DELIMITER');
        }
      }
      // heredocs and double quoted strings are pretty much the same
      elseif($this->scan('/(<<-?\s*)(\w+)/') ||
        $this->scan('/\\$?"/'))
      {
        $pos = $this->match_pos();

        $m = $this->match_groups();
        $type = 'STRING';
        $delim = '';
        if ($m[0][0] === '<') {
          $type = 'HEREDOC';
          $this->record($m[1], null);
          $this->record($m[2], 'KEYWORD');
          $delim = $m[2];
          if ($m[0][2] === '-') $delim = "[ \t]*" . $delim;
          $pos = $this->pos();
        } 
        elseif($m[0][0] === '$') $type = 'VARIABLE';
        $in_str = true;

        $searches = array(($type === 'HEREDOC')? "/^$delim\\b/m" :
          '/(?<!\\\\)((?:\\\\\\\\)*)(")/', 
          '/(?<!\\\\)((?:\\\\\\\\)*)(\\$\\()/');

        while(1) {
          list($index, $matches) = $this->get_next($searches);

          if ($index === -1) {
            $this->record(substr($this->string(), $pos), $type);
            $this->terminate();
            break;
          }
          $hit = isset($matches[2])? $matches[2] : $matches[0];
          $index_ = $index + strlen($matches[0]);
          if($hit === '"') {
            $this->record(substr($this->string(), $pos, $index_ - $pos), $type);
            $this->pos($index_);
            break;
          }
          // URGH WORST CHECK EVER.
          elseif($type === 'HEREDOC' && !isset($matches[2])) {
            $this->record(substr($this->string(), $pos, $index-$pos), $type);
            $this->record($hit, 'KEYWORD');
            $this->pos($index_);
            break;
          }
          else {
            $index_ = $index + strlen($matches[1]);
            $this->record(substr($this->string(), $pos, $index_-$pos), $type);
            $child = new LuminousBashScanner($this->string());
            $child->pos($index_);
            $child->interpolated = true;
            $child->init();
            $child->main();
            $this->record($child->tagged(), 'INTERPOLATED', true);
            $pos = $child->pos();
            $this->pos($pos);
          }
        }
      }
      elseif($this->scan('/\d*[<>]+&?\d*/')) {
        $this->record($this->match(), 'KEYWORD');
      }

      elseif($this->scan("/[^_\-a-zA-Z$'\"\#\{\}\(\)\[\]<>&\d`\n]+/") !== null) {
        $this->record($this->match(), null);
      }
      else 
        $this->record($this->get(), null);
    }
  }

  public static function guess_language($src, $info) {
    $p = 0.0;
    if (preg_match('%\\b (?:bash|csh|ksh|zsh|sh) \\b%x', 
      $info['shebang'])
    ) 
      return 1.0;

    // strange conditional syntax -- if [ -z ...  ]
    if (preg_match('/ (if|while) \s++ \\[\s++-\w/x', $src)) $p += 0.10;

    // quoted vars used in comparison: if [ "$somevar" ...
    if (preg_match('/"\\$\w++"/', $src)) $p += 0.05;

    // case ... esac has to be worth something
    if (strpos($src, 'case') < strpos($src, 'esac')) $p += 0.1;

    return $p;
  }
}
