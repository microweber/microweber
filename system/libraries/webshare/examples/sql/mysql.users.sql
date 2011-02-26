CREATE TABLE users (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(50),
	digesta1 VARCHAR(32),
	email VARCHAR(80),
	UNIQUE(username)
);

INSERT INTO users (username,digesta1,email) VALUES
('admin',  '87fd274b7b6c01e48d7c2f965da8ddf7','admin@example.org');
