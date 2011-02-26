BEGIN TRANSACTION;
CREATE TABLE locks ( id integer primary key asc, owner text, timeout text, created integer, token text, scope integer, depth integer, uri text);
COMMIT;
