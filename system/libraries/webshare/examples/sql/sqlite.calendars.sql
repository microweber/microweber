CREATE TABLE calendarobjects ( 
	id integer primary key asc, 
    calendardata text, 
    uri text, 
    calendarid integer, 
    lastmodified integer
);

CREATE TABLE calendars (
    id integer primary key asc, 
    principaluri text, 
    displayname text, 
    uri text,
	ctag integer, 
    description text,
	calendarorder integer,
    calendarcolor text,
	timezone text,
	components text
);
