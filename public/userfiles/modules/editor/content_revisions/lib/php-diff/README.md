PHP Diff Class
--------------

Introduction
------------
A comprehensive library for generating differences between
two hashable objects (strings or arrays). Generated differences can be
rendered in all of the standard formats including:
 * Unified
 * Context
 * Inline HTML
 * Side by Side HTML

The logic behind the core of the diff engine (ie, the sequence matcher)
is primarily based on the Python difflib package. The reason for doing
so is primarily because of its high degree of accuracy.

Example Use
-----------
A quick usage example can be found in the example/ directory and under
example.php.

More complete documentation will be available shortly.

Merge files using jQuery
------------------------
Xiphe has build a jQuery plugin with that you can merge the compared
files. Have a look at [jQuery-Merge-for-php-diff](https://github.com/Xiphe/jQuery-Merge-for-php-diff).

Todo
----
 * Ability to ignore blank line changes
 * 3 way diff support
 * Performance optimizations

License (BSD License)
---------------------
Copyright (c) 2009 Chris Boulton <chris.boulton@interspire.com>
All rights reserved.
 
Redistribution and use in source and binary forms, with or without 
modification, are permitted provided that the following conditions are met:

 - Redistributions of source code must retain the above copyright notice,
   this list of conditions and the following disclaimer.
 - Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution.
 - Neither the name of the Chris Boulton nor the names of its contributors 
   may be used to endorse or promote products derived from this software 
   without specific prior written permission.

```
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
POSSIBILITY OF SUCH DAMAGE.
```
