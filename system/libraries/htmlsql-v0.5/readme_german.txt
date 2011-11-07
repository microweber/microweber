htmlSQL - Version 0.5 - README
---------------------------------------------------------------------
AUTHOR: Jonas John (http://www.jonasjohn.de/)

BESCHREIBUNG:
---------------------------------------------------------------------
htmlSQL ist eine experimentelle PHP Klasse mit der man auf HTML
Elemente über eine SQL ähnliche Syntax zugreifen kann. Das
bedeutet das man nicht mehr über komplizierte Funktionen
bestimmte Tags extrahieren muss, sondern einfach eine Query
wie diese ausführt:

SELECT href,title   FROM  a   WHERE $class == "liste"
       ^ HTML Attrib.     ^         ^ Abfrage (kann auch leer sein)
         die zurück-      ^
         gegeben          ^ HTML Tags die durchsucht werden sollen 
         werden sollen      "*" ist hier möglich = alle Tags
                              
Diese Abfrage gibt einen Array aller Links mit dem Attribut class="liste"
zurück.

Alle HTTP Verbindungen in htmlSQL benützen die wunderbare Snoopy Klasse
(Package Version 1.2.3 - URL: http://snoopy.sourceforge.net/). 
Allerdings wird Snoopy nicht für "file" oder "string" Queries benötigt.
Alle Snoopy betreffenden Dokumente (z.B: Copyright-Infos, Readme, usw.)
befinden sich im "snoopy_data/" Unterordner.


INSTALLATION / ANWENDUNG:
---------------------------------------------------------------------
Um htmlSQL in eigenen Projekten zu benützen ist es nur notwendig die
zwei Dateien "snoopy.class.php" und die "htmlsql.class.php" zu laden
(mit include oder z.B. require). Danach kann htmlSQL, wie in den 
Beispielen (siehe examples/-Ordner), angesprochen werden. Dies sollte
nicht allzu schwer sein :-)


HINTERGRUND / GESCHICHTE:
---------------------------------------------------------------------
Ich hatte die Idee zu dieser Klasse als ich Daten von einer Web-Seite
extrahiert habe und dabei merkte das sich die Funktionen und Quelltexte
oftmals wiederholen. Da kam mir die Idee das ganze zu vereinfachen und
eine universelle Klasse dafür zu entwickeln. 


WARNUNG:
---------------------------------------------------------------------
Für die Abfragen wird die eval()-Funktion benützt. Deshalb sollten alle
vom Besucher abhängige Daten wie z.b. IDs geprüft oder ggf. gefiltert 
werden da es ansonsten möglich wäre schadhaften PHP Quelltext auszuführen.
Vertraue niemals Benutzereingaben!


TODO:
---------------------------------------------------------------------
- den internen HTML Parser verbessern
- ein eigenes Query system entwickeln und nicht
  das PHP eigene nutzen ( Die eval()-Lösung gefällt mir nicht wirklich)
- Mehr Fehlerprüfungen
- LIMIT Funktion einbauen


ANWENDUNGSGEBIETE VON HTMLSQL:
---------------------------------------------------------------------
- Daten von anderen Web-Seiten auslesen
- HTML basierte Datenbanken?
- XML Daten auslesen


LIZENZ:
---------------------------------------------------------------------
htmlSQL benützt eine modifizierte BSD Lizenz, welche ziemlich offen ist.
Der Lizenztext befindet sich in der "htmlsql.class.php". 
Kurz zusammengefasst besagt er folgendes: 

- Die htmlSQL Klasse kann frei in kommerziellen und nicht-kommerziellen 
  Projekten benützt werden
- Die Klasse darf mit oder ohne Änderungen frei weitergegeben werden
- Der Copyright-Hinweis darf nicht entfernt werden
- Der Autor übernimmt keine Haftung für eventuelle Schäden
- Der Name des Autors oder anderen beteiligten Autoren darf nur mit
  schriftlicher Genehmigung benützt werden um für Produkte, welche 
  htmlSQL benützen, zu werben


