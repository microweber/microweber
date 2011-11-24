htmlSQL - Version 0.5 - README
---------------------------------------------------------------------
AUTHOR: Jonas John (http://www.jonasjohn.de/)


DESCRIPTION:
---------------------------------------------------------------------
htmlSQL is a experimental PHP class which allows you to access HTML
values by an SQL like syntax. This means that you don't have to write
complex functions (regular expressions) to extract specific values.
The htmlSQL queries look like this:

SELECT href,title FROM a WHERE $class == "list"
       ^ Attributes    ^       ^ search query (can be empty)
         to return     ^ 
                       ^ HTML tag to search in 
                         "*" is possible = all tags
                               
This query returns an array with all links that contain
the attribute class="list".

All web transfers in htmlSQL are using the awesome Snoopy class 
(package version 1.2.3 - URL: http://snoopy.sourceforge.net/)
But for file or string queries Snoopy is not required. You find all
Snoopy related documents (copyright, readme, etc) in the snoopy_data/ 
folder.


HOW TO USE:
---------------------------------------------------------------------
Just include the "snoopy.class.php" and the "htmlsql.class.php" files 
into your PHP scripts and look at the examples (examples/) to get an
idea of how to use the htmlSQL class. It should be very simple :-)


BACKGROUND / IDEA:
---------------------------------------------------------------------
I had this idea while extracting some data from a website. As I realized
that the algorithms and functions to extract links and other tags are 
often the same - I had the idea to combine all functions to an universal
usable class. While drinking a coffee and thinking on that problem, I 
thought it would be cool to access HTML elements by using SQL. So I 
started creating this class... 


WARNING:
---------------------------------------------------------------------
The eval() function is used for the WHERE statement. Make sure that all 
user data is checked and filtered against malicious PHP code. 
Never trust user input! 


TODO:
---------------------------------------------------------------------
- enhance the HTML parser
- test htmlSQL with invalid and bad HTML files
- replace the ugly eval() method for the WHERE statement
  with an own method
- more error checks
- include the LIMIT function/method like in SQL


LICENSE:
---------------------------------------------------------------------
htmlSQL uses a modified BSD license, you find the full license text 
in the "htmlsql.class.php". 

