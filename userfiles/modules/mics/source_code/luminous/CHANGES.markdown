Luminous Changelog since 0.6.0
==============================

##v0.6.3 (22/06/11):

- New Stuff:
  - Ada language support
  - Cache can be stored in a MySQL table. See (the docs)[http://luminous.asgaard.co.uk/index.php/docs/show/cache/]

- Fixes
  - Disabled cache purging by default in the cache superclass, previously it
    was set to an hour and may have been invoked accidentally if you 
    insantiated a cache object yourself for some reason.
  - Check before invoking the command line arg parser that Luminous is really
    what's being executed, so that you can now include it from another 
    CLI program.

-Languages fixes:
  - Fix recognition of Perl's shell command strings (backticks) when the 
    string involves escape characters
  - Fix bug with Perl heredoc recognition not waiting until the next line to
    invoke heredoc parsing mode
  - Fix bug with Python not correctly recognising numeric formats with a 
    leading decimal point
  - Fix Ruby's %w and %W operators such that their contents are recognised as 
    strings split by whitespace, not one continual string
  - Highlight "${var}" string interpolation syntax in PHP scanner


##v0.6.2 (15/05/11):

- General: 
  - The User API's configuration settings has been changed internally, using
    the luminous::set() method will throw exceptions if you try to do something
    nonsensical.
  - Each configuration option is now documented fully in Doxygen 
    (LumiousOptions class).
  - High level user API's docs are bundled in HTML.
  - PHP 5.2 syntax error fixed in LaTeX formatter (did not affect 5.3+)

- New Stuff:
  - HTML full-page formatter, which produces a standalone HTML document. Use 
    'html-full' as the formatter.
  - Command line interface. Run ``php luminous.php --help`` to see the usage
  - Language guessing. Generally accurate for large sources of common 
    languages, with no guarantees in other situations. See 
    ``luminous::guess_language`` and ``luminous::guess_lanuage_full`` 
    in Doxygen for details. 

- Language fixes:
  - C/C++ had its #if 0... #endif nesting slightly wrong, it now works
    properly
  - Diff scanner should no longer get confused over formats (i.e. original, 
    context, or unified) if a line starts with a number.
  - PHP now recognises backtick delimited 'strings'
  - Ruby heredoc detection previously had a minor but annoying bug where 
    a heredoc declaration would kill all highlighting on the remainder of 
    that line. This now works correctly.
  - SQL recognises a much more complete set of words, though non-MySQL dialects
    are still under-represented

##v0.6.1 (29/04/11):

- General:
    - Certain versions of PCRE trigger *a lot* of bugs in the regular 
      expressions, which seemed to backtrack a lot even on very simple
      strings. Most (if not all) of these expressions have been rewritten
      to avoid this.
    - The above previously threw an exception: this is now true only if the
      debug flag is set, otherwise the failure is handled.
    - The User API should catch any exceptions Luminous throws in non-debug
      code. If one is caught, Luminous returns the input string wrapped in a 
      pre tag.
    - 'plain' is used as a default scanner in the User API (previously an
      exception was thrown if a scanner was unknown)
    - Fix bug where the User API's 'relative root' would collapse double slashes
      in protocols (i.e. http:// => http:/)
    - User API now throws Exception if the highlighting function is called with
      non-string arguments
    - Some .htaccesses are provided to prevent search engines/bots crawling the
      Luminous directories (many of the files aren't supposed to be executed
      individually and will therefore populate error logs should a bot
      discover a directory)
    - Minor tweaks to the geonyx theme
    - Obsolete JavaScript has been removed and replaced with a much less
      intrusive behaviour of double click the line numbers to hide them,
      js inclusion is disabled by default by User API.
    - Infinite loop bug in the abstract formatter/word wrap method fixed 
      (although this wasn't actually reachable by any of the formatters)

- Language fixes:
    - Pod/cut style comments in Perl should now work all the time
    - C/C++'s "#if 0 ... #endif" blocks (which are highlighted as comments) 
      now nest
    - Python recognises a list of exceptions as types

- New Stuff:
    - Go language support

-  Internal/Development:
    - Unit test of stateful scanner much more useful
    - Formatter base class unit test (tests/unit/formatter.php)
    - Syntax test for scanners (syntax.php)
    - Stateful scanner throws an exception if the initial state is popped
      (downgraded from an assertion)
    - Stateful scanner safety check no longer requires that an iteration
      advances the pointer as long as the state is changed
    - Coding standards applied in all formatters
    - All scanning classes have complete API documentation
    - Paste test (interface.php) works properly with Unicode

## v0.6.0 (16/04/11):
- 0.6.0 is a near-total rewrite with a lot of changes. The hosting has 
  moved from Google Code to GitHub and most code is freshly written.
- Changelog is restarted
