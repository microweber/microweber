CREATE TABLE users (
	id integer primary key asc, 
	username TEXT,
	digesta1 TEXT,
	email TEXT,
	UNIQUE(username)
);

INSERT INTO users (username,digesta1,email) VALUES
('admin',  '87fd274b7b6c01e48d7c2f965da8ddf7','admin@example.org');
