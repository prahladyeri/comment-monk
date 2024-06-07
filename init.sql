-- init.sql
--
-- database initialization routines
--
-- @author Prahlad Yeri <prahladyeri@yahoo.com>
-- @license GPL v3

drop table if exists comments;
drop table if exists posts;
drop table if exists users;

create table users (
	id integer primary key,
	email text not null,
	pwd_hash text not null,
	name text not null,
	website text not null, -- comments will be posted to this site
	enabled int not null default 1, -- site is enabled or not
	notify int not null default 1, -- send new comment notifications or not
	created_at datetime default (datetime(CURRENT_TIMESTAMP, 'localtime')),
	modified_at datetime default (datetime(CURRENT_TIMESTAMP, 'localtime')),
	unique (email),
	check (enabled in (0, 1))
);

create table posts (
	id integer primary key,
	user_id integer not null references users (id),
	uri text not null -- /blog/2024/05/some-slug.html
);

create table comments (
	id integer primary key,
	reply_to_id integer references comments (id),
	post_id integer not null references posts (id),
	message text not null,
	name text not null,
	email text not null,
	website text,
	ip text  not null, -- $_SERVER['REMOTE_ADDR']
	notify int not null default 0,
	approved int not null default 0,
	created_at datetime default CURRENT_TIMESTAMP,
	modified_at datetime default CURRENT_TIMESTAMP,
	check (notify in (0, 1)),
	check (approved in (0, 1))
);



-- create default data
-- create a default admin user who handles to dashboard
-- insert into users(username, hash, name, website)
-- values("admin", "xxxx", 'Admin', 'https://example.com/');