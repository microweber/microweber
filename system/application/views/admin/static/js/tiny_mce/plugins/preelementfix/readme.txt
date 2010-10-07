PRE ELEMENT FIX

DESCRIPTION

This plugin tries to fix some issues I have had with the PRE element. 
Especially when I want to use the SyntaxHighlighter for my content.
(http://code.google.com/p/syntaxhighlighter/)

I have found that editors behaves different and nonintuive regarding to this element so I decided to try fixing it.

Here are the issues that I have found:

Enter key:
MSIE and WebKit creates a new sibling PRE element each time the users hits enter. 
Opera and Firefox creates a BR element.

Tab key:
When pressing the tab key in Firefox nothing happens. 
WebKit creates a tab char inside a span element.

In my opinion editing content inside the PRE element should:

1. Perserve white space 
2. Contain no X/HTML child nodes.
3. Do not create a new sibbling PRE element each time I press the enter key.
4. Tab key should create a tab character. This is just nice to have and since MISE handles this other should to :).

This plugin tries to fix the above issues.

Tested with MSIE 7, Fx 3, Safari 3.2

NOTE! Opera 9.6 has some quirks when typing inside the PRE element. 
I am not sure if this is a bug in Opera or TinyMCE. Hopefully this will be fixed soon.


INSTALLATION

- Unzip the file to the plugin directory of your TinyMCE installation.
- Add "preelementfix" (without the quotes) to your plugin TinyMCE configuration.


KNOWN ISSUES

- Tab key is not working in Opera.
- Opera inserts characters at wrong places.


HISTORY
 
0.2
- The last newline before the closing PRE element is now removed making it more cross browser.
