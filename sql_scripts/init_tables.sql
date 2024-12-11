DROP TABLE IF EXISTS users;

CREATE TABLE  users (
    id SERIAL PRIMARY KEY,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    phone varchar(255) NOT NULL,
    password varchar(255) NOT NULL
);

INSERT INTO users (name, email, phone, password) VALUES
    ('admin', 'admin@localhost', '12-12-12', 'admin');

DROP TABLE IF EXISTS posts;

CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    content varchar(255) NOT NULL,
    direct_link BOOLEAN NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    author_id INTEGER NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users (id)
);

INSERT INTO posts (content, direct_link, author_id) VALUES
    ('image1.jpg', false, 1),
    ('image2.jpg', false, 1),
    ('image3.jpg', false, 1);

